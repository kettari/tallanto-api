<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


class ContactEmailDatabaseProvider extends AbstractEmailDatabaseProvider {

  /**
   * @return string
   */
  protected function getEmailBeanModule() {
    return 'Contacts';
  }

}