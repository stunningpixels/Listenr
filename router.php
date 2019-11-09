<?php

class Router {

  public static function route() {

    // Facebook webhook verification
    if ($_GET['hub_verify_token']) {
      echo $_GET['hub_challenge'];
      exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $message = $input['entry'][0]['messaging'][0];
    $sender_id = $message['sender']['id'];
    switch(self::getSenderStatus($sender_id)) {

      case 'SENDER_UNKNOWN':
        StatusSenderUnknown::routeMessage($message);
        break;

      case 'SENDER_CHOOSE_CAMPAIGN':
        StatusChooseCampaign::routeMessage($message);
        break;

      case 'SENDER_CHOOSE_VALUE':
        StatusChooseValue::routeMessage($message);
        break;

      case 'SENDER_IN_QUEUE':
        StatusInQueue::routeMessage($message);
        break;

      case 'SENDER_IN_CONVERSATION':
        StatusInConversation::routeMessage($message);
        break;

    }
  }

  private static function getSenderStatus($sender_id) {
    $record = DB::queryFirstRow("SELECT * FROM client_status WHERE sender = %i", $sender_id);
    if(isset($record['status'])) {
      return $record['status'];
    }else {
      return 'SENDER_UNKNOWN';
    }
  }

}
