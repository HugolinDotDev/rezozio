<?php

set_include_path('..'.PATH_SEPARATOR);
require('lib/watchdog_service.php');

$args = new RequestParameters('post');
$args->defineNonEmptyString('source');

if (!$args->isValid()) 
{
    produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
    return;
}

if (strlen($args->source) >= 280)
{
    produceError('Le message est trop long il doit être <= 280 caractères');
    return;
}

$data = new DataLayer();
$messageId = $data->postMessage($_SESSION['ident'], $args->source);
produceResult($messageId);
