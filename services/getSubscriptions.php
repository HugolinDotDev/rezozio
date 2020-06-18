<?php

set_include_path('..'.PATH_SEPARATOR);
require('lib/watchdog_service.php');

$data = new DataLayer();
$subscriptions = $data->getSubscriptions($_SESSION['ident']);
produceResult($subscriptions);
