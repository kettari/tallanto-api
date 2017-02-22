<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 22.02.2017
 * Time: 18:16
 */

namespace Intruder;


use Tallanto\Api\Entity\Contact;
use Tallanto\Api\Exception\HeaderNotFoundException;
use Tallanto\Api\Exception\InvalidHeaderException;
use Tallanto\Api\Exception\NotLoggedException;
use Tallanto\Api\Exception\OperationNotAuthorizedException;
use Tallanto\Api\Intruder\AbstractIntruder;

class ContactIntruder extends AbstractIntruder {

  /**
   * Creates new contact in the Tallanto.
   *
   * @param \Tallanto\Api\Entity\Contact $contact
   * @return string
   * @throws \Tallanto\Api\Exception\HeaderNotFoundException
   * @throws \Tallanto\Api\Exception\InvalidHeaderException
   * @throws \Tallanto\Api\Exception\NotLoggedException
   * @throws \Tallanto\Api\Exception\OperationNotAuthorizedException
   */
  public function createContact(Contact $contact) {
    if (!$this->is_logged) {
      throw new NotLoggedException('Not logged to the Tallanto server.');
    }

    $url = $this->getUrl();
    $this->retrieveResource($url, [$this, 'setOptContactCreate'], $contact);

    // Add log message
    if (!is_null($this->logger)) {
      $this->logger->debug('Attempted to create contact entity in the Tallanto at "{url}" with result: {login_result}',
        [
          'url'          => $url,
          'login_result' => ($this->isLastOperationAuthorized()) ? 'success' : 'failure',
        ]);
    }

    // If Tallanto created the resource, retrieve its ID from the Location header
    if ($this->isLastOperationAuthorized()) {
      if (isset($this->last_headers['Location'])) {
        if (preg_match('/record=([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})/',
            $this->last_headers['Location'], $matches) && isset($matches[1])
        ) {
          return $matches[1];
        } else {
          throw new InvalidHeaderException('Location header found but it is invalid: no entity ID is found.');
        }
      } else {
        throw new HeaderNotFoundException('Location header expected and was not found in the response from the Tallanto server.');
      }
    } else {
      throw new OperationNotAuthorizedException('Attempt to create new contact was not authorized by the Tallanto server.');
    }
  }

  /**
   * Sets cURL option to send POST to create contact entity.
   *
   * @param resource $handler
   * @param Contact $contact
   */
  private function setOptContactCreate($handler, $contact) {
    $data = $this->getContactDataStructure();
    $data['first_name'] = $contact->getFirstName();
    $data['last_name'] = $contact->getLastName();
    $data['type_client_c'] = (!empty($contact->getType())) ? $contact->getType() : 'Listener';

    curl_setopt($handler, CURLOPT_POST, TRUE);
    curl_setopt($handler, CURLOPT_POSTFIELDS, $data);
    curl_setopt($handler, CURLOPT_REFERER, $this->getUrl());

    // Add log message
    if (!is_null($this->logger)) {
      $this->logger->debug('setOptContactCreate()');
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
      'relate_to'                           => NULL,
      'relate_id'                           => NULL,
      'offset'                              => 1,
      'opportunity_id'                      => NULL,
      'case_id'                             => NULL,
      'bug_id'                              => NULL,
      'email_id'                            => NULL,
      'inbound_email_id'                    => NULL,
      'first_name'                          => NULL,
      'last_name'                           => NULL,
      'sex_c'                               => NULL,
      'type_client_c'                       => NULL,
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
      'assigned_user_name'                  => 'Корниенко Андрей',
      'assigned_user_id'                    => 'crm_owner',
      'teacher_name'                        => NULL,
      'teacher_id'                          => NULL,
      'last_contact_date'                   => NULL,
      'filial_multiselect'                  => 'true',
      'filial[]'                            => 'lubyanka',
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
}