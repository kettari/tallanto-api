<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 22.02.2017
 * Time: 18:16
 */

namespace Tallanto\Api\Intruder;

use Tallanto\Api\Entity\Contact;
use Tallanto\Api\Entity\Email;


use Tallanto\Api\Exception\NotLoggedException;
use Tallanto\Api\Exception\ValidationHttpException;


class ContactIntruder extends AbstractIntruder {

  /**
   * Creates new contact in the Tallanto.
   *
   * @param \Tallanto\Api\Entity\Contact $contact
   * @return string
   * @throws \Tallanto\Api\Exception\ConflictHttpException
   * @throws \Tallanto\Api\Exception\HeaderNotFoundException
   * @throws \Tallanto\Api\Exception\InvalidHeaderException
   * @throws \Tallanto\Api\Exception\NotLoggedException
   * @throws \Tallanto\Api\Exception\OperationNotAuthorizedException
   * @throws \Exception
   */
  public function saveContact(Contact $contact) {
    if (!$this->is_logged) {
      throw new NotLoggedException('Not logged to the Tallanto server.');
    }
    $this->retrieveResource($this->getUrl(), [$this, 'setOptContactSave'],
      $contact);

    return $this->retrieveIdentifier();
  }

  /**
   * Sets cURL option to send POST to create contact entity.
   *
   * @param resource $handler
   * @param Contact $contact
   * @throws \Tallanto\Api\Exception\ValidationHttpException
   */
  protected function setOptContactSave($handler, $contact) {
    // Map data fields
    $data = $this->getContactDataStructure();
    $data['record'] = $contact->getId();
    $data['first_name'] = $contact->getFirstName();
    $data['last_name'] = $contact->getLastName();
    $data['phone_mobile'] = $contact->getPhoneMobile();
    $data['phone_work'] = $contact->getPhoneWork();
    if (is_array($contact->getEmails())) {
      $i = 0;
      /** @var Email $email */
      foreach ($contact->getEmails() as $email) {
        $data['Contacts0emailAddress'.$i] = $email->getAddress();
        $data['Contacts0emailAddressVerifiedFlag'.$i] = 'true';
        $data['Contacts0emailAddressVerifiedValue'.$i] = $email->getAddress();
        $i++;
      }
    }
    $data['type_client_c'] = (!empty($contact->getType())) ? $contact->getType() : 'Listener';
    $data['assigned_user_id'] = $contact->getManagerId();

    // Convert fields array to form suitable for multipartBuildQuery()
    $multi_data = [];
    foreach ($data as $name => $value) {
      $multi_data[] = ['name' => $name, 'value' => $value];
    }
    // Map [] data fields. They can have duplicated names - it's OK
    if (is_array($contact->getBranches())) {
      foreach ($contact->getBranches() as $branch) {
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
      $this->logger->debug('setOptContactSave()');
    }
  }

  /**
   * Returns array template for the Tallanto Contact entity
   *
   * @return array
   */
  private function getContactDataStructure() {
    return [
      'module'                              => 'Contacts',
      'record'                              => NULL,
      'isDuplicate'                         => 'false',
      'action'                              => 'Save',
      'return_module'                       => NULL,
      'return_action'                       => NULL,
      'return_id'                           => NULL,
      'module_tab'                          => NULL,
      'contact_role'                        => NULL,
      'relate_to'                           => 'Contacts',
      'relate_id'                           => NULL,
      'offset'                              => 1,
      'opportunity_id'                      => NULL,
      'case_id'                             => NULL,
      'bug_id'                              => NULL,
      'email_id'                            => NULL,
      'inbound_email_id'                    => NULL,
      'first_name'                          => '',
      'last_name'                           => NULL,
      'sex_c'                               => NULL,
      'type_client_c'                       => '',
      'tags_multiselect'                    => 'true',
      'Contacts_email_widget_id'            => 0,
      'emailAddressWidget'                  => 1,
      'Contacts0emailAddress0'              => NULL,
      'Contacts0emailAddressPrimaryFlag'    => 'Contacts0emailAddress0',
      'Contacts0emailAddressVerifiedFlag0'  => 'true',
      'Contacts0emailAddressVerifiedValue0' => NULL,
      'useEmailWidget'                      => 'true',
      'marital_status_c'                    => NULL,
      'phone_mobile'                        => NULL,
      'send_sms'                            => 'mobile',
      'birthdate'                           => NULL,
      'phone_work'                          => NULL,
      'title'                               => NULL,
      'skype_c'                             => NULL,
      'account_name'                        => NULL,
      'account_id'                          => NULL,
      'barcode'                             => NULL,
      'number_card_c'                       => NULL,
      'discount'                            => NULL,
      'contract_number'                     => NULL,
      'soc_facebook_c'                      => NULL,
      'soc_vk_c'                            => NULL,
      'soc_linkedin_c'                      => NULL,
      'soc_instagram_c'                     => NULL,
      'primary_address_street'              => NULL,
      'primary_address_city'                => NULL,
      'primary_address_state'               => NULL,
      'primary_address_postalcode'          => NULL,
      'primary_address_country'             => NULL,
      'description'                         => NULL,
      'source'                              => NULL,
      'report_to_name'                      => NULL,
      'reports_to_id'                       => NULL,
      'campaign_name'                       => NULL,
      'campaign_id'                         => NULL,
      'interests_c'                         => NULL,
      'subject1_name'                       => NULL,
      'subject1_id'                         => NULL,
      'subject2_name'                       => NULL,
      'subject2_id'                         => NULL,
      'subject3_name'                       => NULL,
      'subject3_id'                         => NULL,
      'subject4_name'                       => NULL,
      'subject4_id'                         => NULL,
      'subject5_name'                       => NULL,
      'subject5_id'                         => NULL,
      'subject6_name'                       => NULL,
      'subject6_id'                         => NULL,
      'assigned_user_name'                  => NULL,
      'assigned_user_id'                    => '',
      'teacher_name'                        => NULL,
      'teacher_id'                          => NULL,
      'last_contact_date'                   => NULL,
      'filial_multiselect'                  => 'true',
      'filial[]'                            => NULL,
      'need_remind'                         => 0,
      'reminder_date'                       => NULL,
      'reminder_text'                       => NULL,
      'culinary_industry'                   => NULL,
      'utm_source'                          => NULL,
      'utm_medium'                          => NULL,
      'utm_campaign'                        => NULL,
      'utm_content'                         => NULL,
      'utm_term'                            => NULL,
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
      'first_name'       => FALSE,
      'type_client_c'    => FALSE,
      'filial[]'         => FALSE,
      'assigned_user_id' => FALSE,
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
            if ('Contacts' != $item['value']) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (contact): must be exactly "Contacts", given "%s"',
                $item['name'], $item['value']));
            }
            break;
          case 'action':
            if ('Save' != $item['value']) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (contact): must be exactly "Save", given "%s"',
                $item['name'], $item['value']));
            }
            break;
          case 'record':
            if (!empty($item['value']) &&
              !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
                $item['value'])
            ) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (contact): must be in UUID format, given "%s"',
                $item['name'], $item['value']));
            }
            break;
          case 'first_name':
          case 'type_client_c':
          case 'filial[]':
            if (empty($item['value'])) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (contact): must be non-empty',
                $item['name']));
            }
            break;
          case 'assigned_user_id':
            if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
              $item['value'])
            ) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (contact): must be in UUID format, given "%s"',
                $item['name'], $item['value']));
            }
            break;
          case 'phone_mobile':
          case 'phone_work':
            if (!empty($item['value']) &&
              !preg_match('/^[0-9]{11,18}$/', $item['value'])
            ) {
              throw new ValidationHttpException(sprintf('Validation failed for "%s" field (contact): must be numeric with length 11 to 18 characters, given "%s"',
                $item['name'], $item['value']));
            }
            break;
        }
      }
    }
    // Validate all required fields are present
    foreach ($required as $name => $present) {
      if (!$present) {
        throw new ValidationHttpException(sprintf('Validation failed for "%s" field (contact): it is required',
          $name));
      }
    }
  }
}