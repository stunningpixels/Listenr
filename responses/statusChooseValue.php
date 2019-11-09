<?php

class StatusChooseValue extends Status {

  public static function recNewMessage($message) {
    $campaign_id = intval(Actions::getSession($message['sender']['id']));
    Actions::sendChooseValue($message['sender']['id'], $campaign_id);
  }

  public static function recPostback($message) {

    if(isset($message['postback']['payload']) && $message['postback']['payload'] == "BACK") {
      Actions::updateSenderStatus($message['sender']['id'], 'SENDER_CHOOSE_CAMPAIGN');
      Actions::sendWelcomeMessage($message['sender']['id']);
      Actions::sendChooseCampaign($message['sender']['id']);
    }else if(isset($message['postback']['payload'])) {
      $selected_value = DB::queryFirstRow("SELECT `values`.*, `campaigns`.title FROM `values` INNER JOIN campaigns ON values.campaign_id = campaigns.id WHERE `values`.id=%i", $message['postback']['payload']);
      $unselected_value = DB::queryFirstRow("SELECT `values`.*, `campaigns`.title, `campaigns`.title FROM `values` INNER JOIN campaigns ON values.campaign_id = campaigns.id WHERE `campaigns`.id=%i AND `values`.id !=%i", $selected_value['campaign_id'], $selected_value['id']);
      Actions::sendMessage($message['sender']['id'], "Matching you with a person who's {$unselected_value['text']}...");
      $selected_queue = Actions::getQueue($selected_value['campaign_id'], $selected_value['id']);
      $unselected_queue = Actions::getQueue($unselected_value['campaign_id'], $unselected_value['id']);
      if(count($unselected_queue) > 0) {
        DB::delete('queue', "sender=%i", $unselected_queue[0]["sender"]);
        Actions::updateSenderStatus($message['sender']['id'], 'SENDER_IN_CONVERSATION');
        Actions::updateSenderStatus($unselected_queue[0]["sender"], 'SENDER_IN_CONVERSATION');
        Actions::setSession($message['sender']['id'], $unselected_queue[0]["sender"]);
        Actions::setSession($unselected_queue[0]["sender"], $message['sender']['id']);
        Actions::startConversation($message['sender']['id'], $unselected_queue[0]["sender"]);
      }else {
        Actions::updateSenderStatus($message['sender']['id'], 'SENDER_IN_QUEUE');
        DB::insert('queue', array(
          'sender' => $message['sender']['id'],
          'campaign_id' => $selected_value['campaign_id'],
          'value_id' => $selected_value['id']
        ));
        Actions::sendShare($message['sender']['id']);
        Actions::sendButtons($message['sender']['id'], "You're number " . (count($selected_queue) + 1) . " in the queue, as soon as someone {$unselected_value['text']} connects the conversation will begin", array("LEAVE" => "Leave queue"));

      }

    }else {
      $campaign_id = intval(Actions::getSession($message['sender']['id']));
      Actions::sendChooseValue($message['sender']['id'], $campaign_id);
    }
  }

}
