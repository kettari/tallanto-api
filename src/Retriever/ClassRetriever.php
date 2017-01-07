<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 20:01
 */

namespace Tallanto\Api\Retriever;


use PDO;

use Tallanto\Api\Entity\ClassAggregator;
use Tallanto\Api\Entity\ClassEntity;
use Tallanto\Api\Entity\Contact;
use Tallanto\Api\Entity\Ticket;
use Tallanto\Api\Entity\User;
use Tallanto\Api\Entity\Visit;

class ClassRetriever extends BaseRetriever {

  /**
   * Loads data from the Tallanto database and returns aggregator object
   * filled with data
   *
   * @param string $class_id
   * @param int $items_limit
   * @return \Tallanto\Api\Entity\ClassAggregator
   */
  public function load($class_id = NULL, $items_limit = 1000) {
    // Prepare SQL and execute
    $where_clause = '';
    if (!is_null($class_id)) {
      $where_clause = ' AND mc.id = :class_id';
    }
    $sql = $this->getSql($where_clause, $items_limit);
    // Well, we do have big selects =)
    $this->connection->getConnection()->executeQuery('SET SQL_BIG_SELECTS = 1');
    $stmt = $this->connection->getConnection()->executeQuery(
      $sql,
      [
        'class_id' => $class_id,
      ],
      [
        PDO::PARAM_STR,
      ]
    );

    // Fetch all results into associative array
    $dataset = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $this->logger->debug('Fetched {rows_count} record(s)', ['rows_count' => count($dataset)]);
    // Group possibly multiple emails to contacts and remove email information
    // from the dataset
    $emails_map = $this->groupContactEmails($dataset);
    // Group possibly multiple teachers
    $teachers_map = $this->groupTeachers($dataset);
    // Remove duplicate entries (after removing emails, teachers, etc.)
    $dataset = array_map('unserialize', array_unique(array_map('serialize', $dataset)));
    $this->logger->debug('Rows count after shrinking the dataset: {rows_count}', ['rows_count' => count($dataset)]);
    // Group classes
    $classes_map = $this->groupClasses($dataset);
    // Iterate classes
    $class_aggregator = new ClassAggregator();
    foreach ($classes_map as $class_id => $visit_rows) {
      $class = $this->getClass(reset($visit_rows), $teachers_map);
      // Iterate visits
      $visits = [];
      foreach ($visit_rows as $single_visit) {
        $visits[] = $this->getVisit($single_visit, $class, $emails_map);
      }
      $class->setVisits($visits);
      $class_aggregator->add($class);
    }

    return $class_aggregator;
  }

  /**
   * Get the SQL.
   *
   * About prepared statements and LIMIT problem
   *
   * @see http://stackoverflow.com/a/20467350/7194257
   *
   * @param string $where_clause
   * @param integer $items_limit
   * @return string
   */
  protected function getSql($where_clause, $items_limit) {
    return sprintf('
      SELECT 
        mc.id AS class_id,
        mc.date_start,
        mc.date_finish,
        mc.name,
        mc.status,
        mc.filial,
        mc.audience,
        mc.profit,
        
        ms.name AS subject_name,
        
        tl_fil.translate AS filial_translated,
        tl_aud.translate AS audience_translated,
        tl_type_c.translate AS type_client_translated,
        
        mcc.most_class_contacts_status AS visit_status,
        
        ma.id AS ticket_id,
        ma.name AS ticket_name,
        ma.start_date AS ticket_start_date,
        ma.finish_date AS ticket_finish_date,
        ma.cost AS ticket_cost,
        
        mta.id AS template_id,
        mta.name AS template_name,
        
        c.id AS contact_id,
        c.first_name,
        c.last_name,
        c.phone_home,
        c.phone_mobile,
        c.phone_work,
        c.phone_other,
        c.phone_fax,
        
        cs.type_client_c,
        
        e.email_address,
        
        u.first_name AS teacher_first_name,
        u.last_name AS teacher_last_name,
        u.id AS teacher_id
        
      FROM most_class mc
      
      LEFT JOIN most_subject ms ON ms.id = mc.subject_id
      LEFT JOIN translated_lists_for_report tl_fil ON tl_fil.list_name = "filial_list" AND CONCAT("^", tl_fil.value, "^") = mc.filial
      LEFT JOIN translated_lists_for_report tl_aud ON tl_aud.list_name = "audience_list" AND CONCAT("^", tl_aud.value, "^") = mc.audience
      LEFT JOIN most_class_employees_c mce ON mce.deleted = 0 AND mce.most_class_id = mc.id
      LEFT JOIN most_class_contacts_c mcc ON mcc.deleted = 0 AND mcc.most_class_id = mc.id
      LEFT JOIN most_abonements ma ON ma.deleted = 0 AND ma.id = mcc.most_class_abonements
      LEFT JOIN most_template_abonements mta ON mta.deleted = 0 AND mta.id = ma.template_id
      LEFT JOIN contacts c ON c.deleted = 0 AND c.id = mcc.contact_id
      LEFT JOIN contacts_cstm cs ON cs.id_c = c.id
      LEFT JOIN translated_lists_for_report tl_type_c ON tl_type_c.list_name = "type_client_list" AND tl_type_c.value = cs.type_client_c
      LEFT JOIN email_addr_bean_rel eb ON eb.bean_id = c.id AND eb.bean_module = "Contacts" AND eb.deleted = 0
      LEFT JOIN email_addresses e ON e.id = eb.email_address_id AND e.deleted = 0
      LEFT JOIN users u ON u.id = mce.employee_id
      
      WHERE mc.deleted = 0%s
      
      LIMIT %d', $where_clause, $items_limit);
  }

  /**
   * Retrieve emails and return array [contact_id => [email1, email2, ...]]
   * and remove email info from the original dataset
   *
   * @param array $dataset
   * @return array
   */
  protected function groupContactEmails(array &$dataset) {
    $emails = [];
    foreach ($dataset as $key => $row) {
      $emails[$row['contact_id']][] = $row['email_address'];
      unset($dataset[$key]['email_address']);
    }

    return $emails;
  }

  /**
   * Retrieve teachers and return array [class_id => [user1, user2, ...]]
   * and remove teacher info from the original dataset
   *
   * @param array $dataset
   * @return array
   */
  protected function groupTeachers(array &$dataset) {
    $teachers = [];
    foreach ($dataset as $key => $row) {
      if (!isset($teachers[$row['class_id']]) && !isset($teachers[$row['class_id']][$row['teacher_id']])) {
        $teachers[$row['class_id']][$row['teacher_id']] = new User([
          'id'         => $row['teacher_id'],
          'first_name' => $row['teacher_first_name'],
          'last_name'  => $row['teacher_last_name'],
        ]);
      }
      unset($dataset[$key]['teacher_id']);
      unset($dataset[$key]['teacher_first_name']);
      unset($dataset[$key]['teacher_last_name']);
    }

    return $teachers;
  }

  /**
   * Group one dataset by classes [class_id => [$row]]
   *
   * @param array $dataset
   * @return array
   */
  protected function groupClasses(array $dataset) {
    $classes = [];
    foreach ($dataset as $row) {
      $classes[$row['class_id']][] = $row;
    }

    return $classes;
  }

  /**
   * Retrieve data from the row and return Class object
   *
   * @param array $row
   * @param array $teachers_map
   * @return \Tallanto\Api\Entity\ClassEntity
   */
  protected function getClass(array $row, array $teachers_map) {
    return new ClassEntity([
      'id'                  => $row['class_id'],
      'name'                => $row['name'],
      'date_start'          => strtotime($row['date_start']),
      'date_finish'         => strtotime($row['date_finish']),
      'status'              => $row['status'],
      'filial'              => $row['filial'],
      'audience'            => $row['audience'],
      'subject_name'        => $row['subject_name'],
      'filial_translated'   => $row['filial_translated'],
      'audience_translated' => $row['audience_translated'],
      'profit'              => $row['profit'],
      'teachers'            => (isset($teachers_map[$row['class_id']]))
        ? $teachers_map[$row['class_id']] : [],
    ]);
  }

  /**
   * Retrieve data from the row and return Visit object
   *
   * @param array $row
   * @param ClassEntity $class
   * @param array $emails_map
   * @return \Tallanto\Api\Entity\Visit
   */
  protected function getVisit(array $row, $class, array $emails_map) {
    $contact = new Contact([
      'id'                     => $row['contact_id'],
      'first_name'             => $row['first_name'],
      'last_name'              => $row['last_name'],
      'phone_home'             => $row['phone_home'],
      'phone_mobile'           => $row['phone_mobile'],
      'phone_work'             => $row['phone_work'],
      'phone_other'            => $row['phone_other'],
      'phone_fax'              => $row['phone_fax'],
      'type_client_c'          => $row['type_client_c'],
      'email_addresses'        => (isset($emails_map[$row['contact_id']]))
        ? $emails_map[$row['contact_id']] : [],
      'manager_first_name'     => 'UNDEFINED',
      'manager_last_name'      => 'UNDEFINED',
      'type_client_translated' => $row['type_client_translated'],
    ]);
    $ticket = new Ticket([
      'id'            => $row['ticket_id'],
      'name'          => $row['ticket_name'],
      'start_date'    => $row['ticket_start_date'],
      'finish_date'   => $row['ticket_finish_date'],
      'template_name' => $row['template_name'],
      'template_id'   => $row['template_id'],
      'owner'         => $contact,
      'cost'          => $row['ticket_cost'],
    ]);

    return new Visit([
      'class'   => $class,
      'contact' => $contact,
      'ticket'  => $ticket,
      'status'  => $row['visit_status'],
    ]);
  }

}