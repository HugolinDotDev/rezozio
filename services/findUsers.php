<?php

set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require_once('lib/session_start.php');

$args = new RequestParameters();
$args->defineNonEmptyString('searchedString');

if (!$args->isValid()) 
{
    produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
    return;
}

if (strlen($args->searchedString) < 3)
{
    produceError("La recherche doit comporter au moins 3 caractères");
    return;
}

$data = new DataLayer();
$users = $data->findUsers($args->searchedString);
produceResult($users);
