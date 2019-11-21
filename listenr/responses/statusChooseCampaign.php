<?php

class StatusChooseCampaign extends Status {

  public static function recNewMessage($message) {
    Actions::sendChooseCampaign($message['sender']['id']);
  }

  public static function recPostback($message) {
    if(isset($message['postback']['payload'])) {
      $record = DB::queryFirstRow("SELECT * FROM campaigns WHERE id=%i", $message['postback']['payload']);
      Actions::sendChooseValue($message['sender']['id'], $record['id']);
      Actions::updateSenderStatus($message['sender']['id'], 'SENDER_CHOOSE_VALUE');
      Actions::setSession($message['sender']['id'], $record['id']);
    }else {
      Actions::sendChooseCampaign($message['sender']['id']);
    }
  }

}
