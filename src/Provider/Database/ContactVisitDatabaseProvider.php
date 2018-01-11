<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;

class ContactVisitDatabaseProvider extends AnythingVisitDatabaseProvider {

  /**
   * Prepares WHERE SQL clause
   *
   * @return string
   */
  protected function getWhereClause() {
    // Format WHERE clause of the SQL statement
    $where_clause = '';
    if (!empty($this->query)) {
      $where_clause .= ' AND mcc.contact_id = :query_exact';
    }
    if (!is_null($this->getIfModifiedSince())) {
      $where_clause .= ' AND mc.date_modified > :modified_since';
    }

    return $where_clause;
  }


}