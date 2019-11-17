<?php

/* COMMON ACTIONS */

class Actions {

  // set in config.php (fb url + access token)
  public static $API_ENDPOINT = '';

  public static function updateSenderStatus($sender, $newStatus, $insert = false) {
    if($insert) {
      DB::insert('client_status', array(
        'sender' => $sender,
        'status' => $newStatus
      ));
    }else {
      DB::update('client_status', array(
        'status' => $newStatus
      ), "sender=%i", $sender);
    }
  }

  public static function sendWelcomeMessage($sender) {
    self::sendMessage($sender, "Hi there!\nWelcome to listenr, a way to connect with people that hold different views from yourself, in the hope that we can start to understand each other a little better 👫");
    self::sendMessage($sender, "In a moment you'll have to opportunity to chat 100% anonymously with another human");
  }

  public static function sendChooseCampaign($sender) {
    $records = DB::query("SELECT * FROM `campaigns`");
    $campaigns = array();
    foreach($records as $record) {
      $campaigns[$record['id']] = $record['title'];
    }
    self::sendButtons($sender, "To get started, please choose from one of the following topics:", $campaigns);
  }

  public static function sendChooseValue($sender, $campaign_id) {
    $records = DB::query("SELECT * FROM `values` WHERE campaign_id=%i", $campaign_id);
    $values = array();
    foreach($records as $record) {
      $values[$record['id']] = $record['text'];
    }
    $values["BACK"] = "Back to topics";
    self::sendButtons($sender, "And which one of the following options best describes your views? ", $values);
  }

  public static function reset($sender) {
    DB::delete('queue', "sender=%i", $sender);
    self::updateSenderStatus($sender, 'SENDER_CHOOSE_CAMPAIGN');
    self::setSession($sender, "");
    self::sendChooseCampaign($sender);
  }

  public static function confirmEnd($sender) {
    self::sendButtons($sender, "Please confirm...", array("End the chat", "Keep chatting"));
  }

  public static function getSession($sender) {
    return DB::queryFirstRow("SELECT * FROM `client_status` WHERE sender=%i", $sender)['session'];
  }

  public static function setSession($sender, $data) {
    echo "SETTING SESSION";
    DB::update('client_status', array(
      'session' => $data
    ), "sender=%i", $sender);
  }

  public static function getQueue($campaign, $value) {
    return DB::query("SELECT * FROM `queue` WHERE campaign_id=%i AND value_id=%i ORDER BY `timestamp`", $campaign, $value);
  }

  public static function startConversation($sendera, $senderb) {
    self::sendMessage($sendera, "You're now chatting, say hello :)\n\nRemember to be nice and type 'end' at any point to stop the conversation");
    self::sendMessage($senderb, "You're now chatting, say hello :)\n\nRemember to be nice and type 'end' at any point to stop the conversation");
  }

  public static function sendMessage($sender, $message) {
    $data = array(
      "recipient" => array("id" => $sender),
      "message" => array("text" => $message)
    );
    return sendMessageToFB($data);
  }

  public static function sendShare($sender) {
    self::sendMessage($sender, "Don't like queuing? Share Listenr with your friends to get more people in conversation");
    $data = array(
      "recipient" => array("id" => $sender),
      "message" => array(
        "attachment" => array(
          "type" => "template",
          "payload" => array(
            "template_type" => "generic",
            "elements" => array(
              array(
                "title" => "Listenr, bursting the bubble",
                "subtitle" => "Don't like queuing? Share Listenr with your friends to get more people in conversation",
                "default_action" => array(
                  "type" => "web_url",
                  "url" => "https://m.me/listenrconnect"
                ),
                "buttons" => array(
                  array(
                    "type" => "element_share"
                  )
                )
              )
            )
          )
        ))
    );
    return sendMessageToFB($data);
  }

  public static function sendButtons($sender, $message, $buttons) {
    $data = array(
      "recipient" => array("id" => $sender),
      "message" => array(
        "attachment" => array(
          "type" => "template",
          "payload" => array(
            "template_type" => "button",
            "text" => $message,
            "buttons" => buttonsArray($buttons)
          )
        ))
    );
    return sendMessageToFB($data);
  }

  public static function sendQuickReply($sender, $message, $buttons) {
    $data = array(
      "recipient" => array("id" => $sender),
      "message" => array(
        "text" => $message,
        "quick_replies" => buttonsArray($buttons)
      )
    );
    return sendMessageToFB($data);
  }

  private static function sendMessageToFB($data) {
    $ch = curl_init(self::$API_ENDPOINT);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    return curl_exec($ch);
  }

  private static function buttonsArray($buttons) {
    $buttons_array = array();
    foreach($buttons as $key => $button) {
      array_push($buttons_array, array(
        "content_type" => "text",
        "title" => $button,
        "payload" => $key
      ));
    }
    return $buttons_array;
  }

}
