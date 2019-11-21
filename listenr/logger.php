<?php

class Logger {

  public static function log($message) {
    $log_file = 'logs/requests.log';
    file_put_contents($log_file, date("Y-m-d H:i:s") . ': ' . $message . "\n", FILE_APPEND);
  }

  public static function error($message) {
    $error_file = 'logs/errors.log';
    file_put_contents($error_file, date("Y-m-d H:i:s") . ': ' . $message . "\n", FILE_APPEND);
  }

}