<?php

date_default_timezone_set('UTC');

require('./meekrodb.2.3.class.php');
require('./logger.php');
require('./actions.php');
require('./router.php');
require('./responses/status.php');

require('./responses/statusSenderUnknown.php');
require('./responses/statusChooseCampaign.php');
require('./responses/statusChooseValue.php');
require('./responses/statusInConversation.php');
require('./responses/statusInQueue.php');

require('./config.php');


try {

  $post_data = file_get_contents('php://input');

  Logger::log($post_data);
  Router::route($post_data);

} catch (Exception $e) {

  Logger::error('Caught exception: ' . $e->getMessage());

}
