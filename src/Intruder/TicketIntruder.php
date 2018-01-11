<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 22.02.2017
 * Time: 18:16
 */

namespace Tallanto\Api\Intruder;


use Tallanto\Api\Entity\Ticket;


use Tallanto\Api\Exception\NotLoggedException;
use Tallanto\Api\Exception\ValidationHttpException;


class TicketIntruder extends AbstractIntruder {

  use DateHelperTrait;

  /**
   * Creates new ticket in the Tallanto.
   *
   * @param \Tallanto\Api\Entity\Ticket $ticket
   * @return string
   * @throws \Tallanto\Api\Exception\ConflictHttpException
   * @throws \Tallanto\Api\Exception\HeaderNotFoundException
   * @throws \Tallanto\Api\Exception\InvalidHeaderException
   * @throws \Tallanto\Api\Exception\NotLoggedException
   * @throws \Tallanto\Api\Exception\OperationNotAuthorizedException
   * @throws \Exception
   */
  public function saveTicket(Ticket $ticket) {
    if (!$this->is_logged) {
      throw new NotLoggedException('Not logged to the Tallanto server.');
    }
    $this->retrieveResource($this->getUrl(), [$this, 'setOptTicketSave'],
      $ticket);

    return $this->retrieveIdentifier();
  }

  /**
   * Sets cURL option to send POST to create ticket entity.
   *
   * @param resource $handler
   * @param Ticket $ticket
   * @throws \Tallanto\Api\Exception\ValidationHttpException
   */
  protected function setOptTicketSave($handler, $ticket) {
    // Map data fields
    $data = $this->getTicketDataStructure();
    $data['record'] = $ticket->getId();
    $data['date_start'] = $ticket->getStartDate();
    $data['date_finish'] = $ticket->getFinishDate();
    // template_name
    $data['template_id'] = $ticket->getTemplateId();
    $data['contact_id'] = $ticket->getContactId();
    // owner
    $data['cost'] = $ticket->getCost();
    // cost_standard
    $data['duration'] = $ticket->getDuration();
    $data['num_visit'] = $ticket->getNumVisit();
    // num_visit_left
    $data['assigned_user_id'] = $ticket->getManagerId();

    // Crutch for dates
    $dateStart = $this->parseDate($data['date_start']);
    $dateFinish = $this->parseDate($data['date_finish']);
    if ($dateStart instanceof \DateTime) {
      $data['date_start'] = $dateStart->format('d.m.Y');
    }
    if ($dateFinish instanceof \DateTime) {
      $data['date_finish'] = $dateFinish->format('d.m.Y');
    }
    //die(print_r($data, true));

    // Convert fields array to form suitable for multipartBuildQuery()
    $multi_data = [];
    foreach ($data as $name => $value) {
      $multi_data[] = ['name' => $name, 'value' => $value];
    }
    // Map [] data fields. They can have duplicated names - it's OK
    if (is_array($ticket->getBranches())) {
      foreach ($ticket->getBranches() as $branch) {
        $multi_data[] = ['name' => 'filial[]', 'value' => $branch];
      }
    }

    // Validate data
    $this->validate($multi_data);

    // Set cURL options to use POST method
    $boundary = '----WebKitFormBoundary'.$this->generateRandomString(16);
    curl_setopt($handler, CURLOPT_HTTPHEADER,
      ['Content-Type: multipart/form-data; boundary='.$boundary]);
    curl_setopt($handler, CURLOPT_POST, TRUE);
    curl_setopt($handler, CURLOPT_POSTFIELDS,
      $this->multipartBuildQuery($multi_data, $boundary));
    curl_setopt($handler, CURLOPT_REFERER, $this->getUrl());

    // Add log message
    if (!is_null($this->logger)) {
      $this->logger->debug('setOptTicketSave()');
    }
  }

  /**
   * Returns array template for the Tallanto Contact entity
   *
   * @return array
   */
  private function getTicketDataStructure() {
    return [
      'module'                           => 'most_abonements',
      'record'                           => NULL,
      'isDuplicate'                      => 'false',
      'action'                           => 'Save',// manual_close='Закрыть'
      'return_module'                    => NULL,
      'return_action'                    => NULL,
      'return_id'                        => NULL,
      'module_tab'                       => NULL,
      'contact_role'                     => NULL,
      'relate_to'                        => 'most_abonements',
      'relate_id'                        => NULL,
      'offset'                           => '1',
      'template_name'                    => NULL,
      'template_id'                      => '',
      'name'                             => NULL,
      'contact_name'                     => NULL,
      'contact_id'                       => '',
      'num_visit_type'                   => 'number',
      'num_visit'                        => NULL,
      'cost'                             => NULL,
      'type'                             => 'Standart',
      'group_ticket'                     => '0',
      'form'                             => 'common',
      'class_cost_for_inclusive'         => NULL,
      'date_start'                       => NULL,
      'duration'                         => NULL,
      'date_finish'                      => NULL,
      'recharge_money'                   => '0',
      'most_finances_type'               => 'cash',
      'assigned_user_name'               => NULL,
      //'API',
      'assigned_user_id'                 => NULL,
      //'cc983c75-ab32-36b9-44dc-58ad928b9d1a',
      'filial_multiselect'               => 'true',
      'max_number_absent'                => NULL,
      'yui-picker-r'                     => '255',
      'yui-picker-g'                     => '255',
      'yui-picker-b'                     => '255',
      'yui-picker-h'                     => '0',
      'yui-picker-s'                     => '0',
      'yui-picker-v'                     => '100',
      'yui-picker-hex'                   => 'FFFFFF',
      'color_in_class_contacts_subpanel' => NULL,
      'description'                      => NULL,
    ];
  }

  /**
   * Validates data before posting to the Tallanto server. Throws errors if
   * found errors.
   *
   * @param array $data
   * @throws \Tallanto\Api\Exception\ValidationHttpException
   */
  protected function validate($data) {
    // Enumerate all required fields, set to FALSE = not present
    $required = [
      'module'           => FALSE,
      'action'           => FALSE,
      'template_id'      => FALSE,
      'contact_id'       => FALSE,
      'assigned_user_id' => FALSE,
      'num_visit'        => FALSE,
      'cost'             => FALSE,
      'duration'         => FALSE,
      'filial[]'         => FALSE,
    ];
    // Validate format
    foreach ($data as $item) {
      if (isset($item['name']) && isset($item['value'])) {
        // Check for required fields
        if (isset($required[$item['name']])) {
          $required[$item['name']] = TRUE;
        }
        // Validate format
        switch ($item['name']) {
          case 'module':
            if ('most_abonements' != $item['value']) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (ticket): must be exactly "most_abonements", given "%s"',
                $item['name'], $item['value']));
            }
            break;
          case 'action':
            if ('Save' != $item['value']) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (ticket): must be exactly "Save", given "%s"',
                $item['name'], $item['value']));
            }
            break;
          case 'record':
            if (!empty($item['value']) &&
              !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
                $item['value'])
            ) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (ticket): must be in UUID format, given "%s"',
                $item['name'], $item['value']));
            }
            break;
          case 'template_id':
          case 'contact_id':
          case 'assigned_user_id':
            if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
              $item['value'])
            ) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (ticket): must be in UUID format, given "%s"',
                $item['name'], $item['value']));
            }
            break;
          case 'num_visit':
          case 'cost':
          case 'duration':
            if (!is_numeric($item['value'])) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (ticket): must be numeric, given "%s"',
                $item['name'], $item['value']));
            }
            break;
          case 'date_start':
          case 'date_finish':
            if (!preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $item['value'])) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (ticket): must be in dd.mm.yyyy format, given "%s"',
                $item['name'], $item['value']));
            }
            break;
        }
      }
    }
    // Validate all required fields are present
    foreach ($required as $name => $present) {
      if (!$present) {
        throw new ValidationHttpException(sprintf('Validation failed for "%s" field (ticket): it is required',
          $name));
      }
    }
  }


}