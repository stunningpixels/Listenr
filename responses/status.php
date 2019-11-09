<?php

abstract class Status {

  public static function routeMessage($message) {
    if(isset($message['message'])) {
      static::recNewMessage($message);
    }else if(isset($message['postback'])) {
      static::recPostback($message);
    }
  }

}
