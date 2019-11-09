<?php

class StatusInQueue extends Status {

  public static function recNewMessage($message) {
    $selected_value = DB::queryFirstRow("SELECT `values`.*, `campaigns`.title FROM `values` INNER JOIN campaigns ON values.campaign_id = campaigns.id WHERE `values`.id=%i", $message['postback']['payload']);
    Actions::sendButtons($message['sender']['id'], "You're number " . (count($selected_queue) + 1) . " in the queue", array("LEAVE" => "Leave queue"));
  }

  public static function recPostback($message) {
    if(isset($message['postback']['payload']) && $message['postback']['payload'] == "LEAVE") {
      Actions::reset($message['sender']['id']);
    }
  }

}
