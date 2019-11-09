<?php

class StatusSenderUnknown extends Status {

  public static function recNewMessage($message) {
    Actions::updateSenderStatus($message['sender']['id'], 'SENDER_CHOOSE_CAMPAIGN', true);
    Actions::sendWelcomeMessage($message['sender']['id']);
    Actions::sendChooseCampaign($message['sender']['id']);
  }

  public static function recPostback($message) {
    Actions::updateSenderStatus($message['sender']['id'], 'SENDER_CHOOSE_CAMPAIGN', true);
    Actions::sendWelcomeMessage($message['sender']['id']);
    Actions::sendChooseCampaign($message['sender']['id']);
  }

}
