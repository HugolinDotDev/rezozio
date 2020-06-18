<?php

set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require_once('lib/session_start.php');

$args = new RequestParameters();
$args->defineNonEmptyString('messageId');

if (!$args->isValid()) 
{
    produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
    return;
}

$data = new DataLayer();
$message = $data->getMessage($args->messageId);
if (!is_null($message))
{
    produceResult($message);
}
else
{
    produceError("Le message n'existe pas");
    return;
}
