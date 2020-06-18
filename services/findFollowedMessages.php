<?php

set_include_path('..'.PATH_SEPARATOR);
require_once('lib/watchdog_service.php');

$args = new RequestParameters();
$args->defineInt('before', [
    'default' => 2147483647
]);
$args->defineInt('count', [
    'default' => 15,
    'min_value' => 1
]);

if (!$args->isValid()) 
{
    produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
    return;
}

$data = new DataLayer();
$messages = $data->findFollowedMessages($_SESSION['ident'], $args->before, $args->count);
produceResult($messages);
