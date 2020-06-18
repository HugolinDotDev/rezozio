<?php

set_include_path('..'.PATH_SEPARATOR);
require('lib/watchdog_service.php');

$userId = $_SESSION['ident'];
session_destroy();
produceResult($userId);
