<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 27.02.2017
 * Time: 14:19
 */

namespace Tallanto\Api\Entity;


class Subject extends AbstractIdentifiableEntity {

  use BranchesTrait;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $description;

  /**
   * @var string
   */
  protected $status;

  /**
   * @var bool
   */
  protected $calendar_hidden;

  /**
   * @var string
   */
  protected $default_stake_id;

  /**
   * @var string
   */
  protected $date_start;

  /**
   * @var string
   */
  protected $date_finish;

  /**
   * Subject constructor.
   *
   * @param array $data
   */
  public function __construct($data) {
    parent::__construct($data);

    // Correct boolean to look like boolean
    $this->calendar_hidden = $this->calendar_hidden ? TRUE : FALSE;
    // Sanitize branch
    $this->branches = $this->sanitizeBranch($this->branches);
  }

}