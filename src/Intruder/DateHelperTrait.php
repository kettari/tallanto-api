<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 18.08.2017
 * Time: 22:25
 */

namespace Tallanto\Api\Intruder;


trait DateHelperTrait
{

  /**
   * @param \DateTime|string $date
   * @return \DateTime
   */
  protected function parseDate($date)
  {
    if (($date instanceof \DateTime) || is_null($date) || empty($date)) {
      return $date;
    }

    // 13-digit date
    if (preg_match(
        '/\/Date\((\d+)\)\//',
        $date,
        $matches
      ) && isset($matches[1])) {
      return \DateTime::createFromFormat(
        'U',
        floor($matches[1] / 1000),
        NowDateHelper::getUtc()
      );
    }

    // Several variants of string date
    if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}\:\d{2}\:\d{2}$/', $date)) {
      $format = 'Y-m-d H:i:s';
    } elseif (preg_match(
      '/^\d{4}-\d{2}-\d{2}T\d{2}\:\d{2}\:\d{2}$/',
      $date
    )) {
      $format = 'Y-m-d\TH:i:s';
    } elseif (preg_match(
      '/^\d{4}-\d{2}-\d{2}T\d{2}\:\d{2}\:\d{2}Z$/',
      $date
    )) {
      $format = 'Y-m-d\TH:i:s\Z';
    } else {
      throw new \RuntimeException('Unknown date format: '.$date);
    }

    return \DateTime::createFromFormat($format, $date, NowDateHelper::getUtc());
  }


}