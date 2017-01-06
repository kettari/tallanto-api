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

class ClassRetriever extends BaseRetriever {

  /**
   * Loads data from the Tallanto database and returns aggregator object
   * filled with data
   *
   * @param string $class_id
   * @param int $items_limit
   * @return \Tallanto\Api\Entity\ClassAggregator
   */
  public function load($class_id = NULL, $items_limit = 100) {
    // Prepare SQL and execute
    $where_clause = '';
    if (!is_null($class_id)) {
      $where_clause = ' AND mc.id = :class_id';
    }
    $sql = $this->getSql($where_clause);
    $stmt = $this->connection->getConnection()->executeQuery(
      $sql,
      [
        'class_id' => $class_id,
        'limit'    => $items_limit,
      ]
    );

    $class_aggregator = new ClassAggregator();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $this->logger->debug('Data row fetched', ['row' => $row]);
    }

    return $class_aggregator;
  }

  /**
   * @param string $where_clause
   * @return string
   * @internal param int $limit
   */
  protected function getSql($where_clause) {
    return sprintf('
      SELECT 
        -- Class
        mc.id,
        mc.date_start,
        mc.date_finish,
        mc.name,
        mc.status,
        mc.filial,
        mc.audience,
        
        -- Subject
        ms.name AS subject_name,
        -- Translations
        tl_fil.translate AS filial_translated,
        tl_aud.translate AS audience_translated,
        -- Contacts visited the class
        mcc.most_class_contacts_status AS visit_status,
        
        -- Tickets
        ma.id AS ticket_id,
        ma.name AS ticket_name,
        ma.start_date AS ticket_start_date,
        ma.finish_date AS ticket_finish_date,
        ma.cost AS ticket_cost,
        
        -- Ticket templates
        mta.id AS template_id,
        mta.name AS template_name,
        
        -- Contact
        c.id AS contact_id,
        c.first_name,
        c.last_name,
        c.phone_home,
        c.phone_mobile,
        c.phone_work,
        c.phone_other,
        c.phone_fax,
        
        -- Employee(s)
        u.first_name AS employee_first_name,
        u.last_name AS employee_last_name,
        u.id AS employee_id
        
      FROM most_class mc
      
      LEFT JOIN most_subject ms ON ms.id = mc.subject_id
      LEFT JOIN translated_lists_for_report tl_fil ON tl_fil.list_name = "filial_list" AND CONCAT("^", tl_fil.value, "^") = mc.filial
      LEFT JOIN translated_lists_for_report tl_aud ON tl_aud.list_name = "audience_list" AND CONCAT("^", tl_aud.value, "^") = mc.audience
      LEFT JOIN most_class_employees_c mce ON mce.deleted = 0 AND mce.most_class_id = mc.id
      LEFT JOIN most_class_contacts_c mcc ON mcc.deleted = 0 AND mcc.most_class_id = mc.id AND mcc.most_class_contacts_status = "visit"
      LEFT JOIN most_abonements ma ON ma.deleted = 0 AND ma.id = mcc.most_class_abonements
      LEFT JOIN most_template_abonements mta ON mta.deleted = 0 AND mta.id = ma.template_id
      LEFT JOIN contacts c ON c.deleted = 0 AND c.id = mcc.contact_id
      LEFT JOIN users u ON u.id = mce.employee_id
      
      WHERE mc.deleted = 0%s
      
      ORDER BY mc.date_start, subject_name, last_name
      
      LIMIT :limit
    ', $where_clause);
  }

}