<?php

date_default_timezone_set('UTC');

require('./meekrodb.2.3.class.php');
require('./actions.php');
require('./router.php');
require('./responses/status.php');

require('./responses/statusSenderUnknown.php');
require('./responses/statusChooseCampaign.php');
require('./responses/statusChooseValue.php');
require('./responses/statusInConversation.php');
require('./responses/statusInQueue.php');

require('./config.php');

// Logging
$log_file = 'requests.log';

file_put_contents($log_file, date("Y-m-d H:i:s") . file_get_contents('php://input') . "\n", FILE_APPEND);

Router::route();
