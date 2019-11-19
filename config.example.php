<?php

if (getenv('SERVER_ENVIRONMENT') == 'TEST_ENVIRONMENT') {

  echo "Using TEST_SERVER\n";

  $MOCK_PORT =  getenv('MOCK_PORT');
  Actions::$API_ENDPOINT = 'http://localhost:' . $MOCK_PORT . '/messages';

  /* DB SETUP */
  DB::$user = 'root';
  DB::$password = '';
  DB::$dbName = 'listenr_test';
  DB::$host = 'localhost:3306';
  DB::$encoding = 'utf8';

} else {

  // // Live app:
  $ACCESS_TOKEN = '';
  Actions::$API_ENDPOINT = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$ACCESS_TOKEN;

  /* DB SETUP */
  DB::$user = '';
  DB::$password = '';
  DB::$dbName = 'listenr';
  DB::$host = '';
  DB::$encoding = 'utf8'; // defaults to latin1 if omittedd

}