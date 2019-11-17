<?php

class StatusChooseValue extends Status {

  public static function recNewMessage($message) {
    $campaign_id = intval(Actions::getSession($message['sender']['id']));
    Actions::sendChooseValue($message['sender']['id'], $campaign_id);
  }

  public static function recPostback($message) {

    if(!isset($message['postback']['payload'])) {
      $campaign_id = intval(Actions::getSession($message['sender']['id']));
      Actions::sendChooseValue($message['sender']['id'], $campaign_id);
      return;
    }

    if($message['postback']['payload'] == "BACK") {
      Actions::updateSenderStatus($message['sender']['id'], 'SENDER_CHOOSE_CAMPAIGN');
      Actions::sendWelcomeMessage($message['sender']['id']);
      Actions::sendChooseCampaign($message['sender']['id']);
      return;
    }

    // get queue statuses
    $selected_value = DB::queryFirstRow("SELECT `values`.*, `campaigns`.title FROM `values` INNER JOIN campaigns ON values.campaign_id = campaigns.id WHERE `values`.id=%i", $message['postback']['payload']);
    $unselected_value = DB::queryFirstRow("SELECT `values`.*, `campaigns`.title, `campaigns`.title FROM `values` INNER JOIN campaigns ON values.campaign_id = campaigns.id WHERE `campaigns`.id=%i AND `values`.id !=%i", $selected_value['campaign_id'], $selected_value['id']);
    Actions::sendMessage($message['sender']['id'], "Matching you with a person who's {$unselected_value['text']}...");
    $selected_queue = Actions::getQueue($selected_value['campaign_id'], $selected_value['id']);
    $unselected_queue = Actions::getQueue($unselected_value['campaign_id'], $unselected_value['id']);

    // if there are people in the queue
    if(count($unselected_queue) > 0) {
      // conversations are available
      Actions::startConversation($message['sender']['id'], $unselected_queue[0]["sender"]);
    }else {
      // no conversations available adding to queue
      Actions::addToQueue($message['sender']['id'], $selected_queue, $selected_value, $unselected_value);
    }
  }

}
