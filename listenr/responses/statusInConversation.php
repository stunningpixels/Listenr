<?php

class StatusInConversation extends Status {

  public static function recNewMessage($message) {
    if(strtolower($message["message"]["text"]) ===  "end") {
      // End conversation
      Actions::confirmEnd($message["sender"]["id"]);
    }else {
      // Relay message
      $partner_sender = Actions::getSession($message["sender"]["id"]);
      Actions::sendMessage($partner_sender, $message["message"]["text"]);
    }
  }

  public static function recPostback($message) {
    if(!isset($message['postback']['payload'])) {
      return;
    }
    if($message['postback']['payload'] == 0) {
      $partner_sender = Actions::getSession($message["sender"]["id"]);
      Actions::sendMessage($partner_sender, "Your partner has ended the conversation");
      Actions::sendMessage($message["sender"]["id"], "The conversation has ended");
      Actions::reset($partner_sender);
      Actions::reset($message["sender"]["id"]);
    }else {
      Actions::sendMessage($message["sender"]["id"], "Resuming chat");
    }
  }

}
