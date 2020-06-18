<?php

set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new RequestParameters();
$args->defineNonEmptyString('userId');

if (!$args->isValid()) 
{
    produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
    return;
}

$data = new DataLayer();
$user = $data->getUser($args->userId);
if (is_null($user)) 
{
    produceError("L'utilisateur n'existe pas");
    return;
}
produceResult($user);
