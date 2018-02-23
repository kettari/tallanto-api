<?php

namespace Tallanto\Api\Intruder;


use Tallanto\Api\Exception\NotLoggedException;


class TicketFreezerIntruder extends AbstractIntruder
{

  /**
   * Freeze the ticket in the Tallanto.
   *
   * @param string $ticketId
   * @return void
   * @throws \Exception
   */
  public function freezeTicket($ticketId)
  {
    if (!$this->is_logged) {
      throw new NotLoggedException('Not logged to the Tallanto server.');
    }
    $this->retrieveResource(
      $this->getUrl(),
      [$this, 'setOptTicketFreeze'],
      $ticketId
    );

    $this->retrieveIdentifier();
  }

  /**
   * Unfreeze the ticket in the Tallanto.
   *
   * @param string $ticketId
   * @return void
   * @throws \Exception
   */
  public function unFreezeTicket($ticketId)
  {
    if (!$this->is_logged) {
      throw new NotLoggedException('Not logged to the Tallanto server.');
    }
    $this->retrieveResource(
      $this->getUrl(),
      [$this, 'setOptTicketUnfreeze'],
      $ticketId
    );

    $this->retrieveIdentifier();
  }

  /**
   * Sets cURL option to send POST to freeze the ticket.
   *
   * @param resource $handler
   * @param string $ticketId
   */
  protected function setOptTicketFreeze($handler, $ticketId)
  {
    curl_setopt($handler, CURLOPT_POST, true);
    curl_setopt(
      $handler,
      CURLOPT_POSTFIELDS,
      sprintf(
        'module=most_abonements&action=Freezing&record=%s&'.
        'return_module=most_abonements&return_action=DetailView&return_id=%s',
        $ticketId,
        $ticketId
      ).
      '&isDuplicate=false&offset=1&freezing=%D0%97%D0%B0%D0%BC%D0%BE%D1%80%D0%BE%D0%B7%D0%B8%D1%82%D1%8C'
    );

    // Add log message
    if (!is_null($this->logger)) {
      $this->logger->debug('setOptTicketFreeze()');
    }
  }

  /**
   * Sets cURL option to send POST to freeze the ticket.
   *
   * @param resource $handler
   * @param string $ticketId
   */
  protected function setOptTicketUnfreeze($handler, $ticketId)
  {
    curl_setopt($handler, CURLOPT_POST, true);
    curl_setopt(
      $handler,
      CURLOPT_POSTFIELDS,
      sprintf(
        'module=most_abonements&action=Unfreezing&record=%s&'.
        'return_module=most_abonements&return_action=DetailView&return_id=%s',
        $ticketId,
        $ticketId
      ).
      '&isDuplicate=false&offset=1&unfreezing=%D0%A0%D0%B0%D0%B7%D0%BC%D0%BE%D1%80%D0%BE%D0%B7%D0%B8%D1%82%D1%8C'
    );

    // Add log message
    if (!is_null($this->logger)) {
      $this->logger->debug('setOptTicketUnfreeze()');
    }
  }

  /**
   * Validates data before posting to the Tallanto server. Throws errors if
   * found errors.
   *
   * @param array $data
   * @return void
   */
  protected function validate($data)
  {
    // Nothing to validate
  }


}