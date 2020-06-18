<?php

set_include_path('..'.PATH_SEPARATOR);
require('lib/watchdog_service.php');

$args = new RequestParameters();
$args->defineNonEmptyString('target');

if (!$args->isValid()) 
{
    produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
    return;
}

$data = new DataLayer();

if (!$data->userExists($args->target))
{
    produceError("L'utilisateur auquel vous voulez vous abonner n'existe pas");
    return;
}

if (!$data->isFollowing($_SESSION['ident'], $args->target))
{
    $data->follow($_SESSION['ident'], $args->target);
    produceResult(true);
}
else
{
    $message = "Vous êtes déjà abonné à " . $args->target;
    produceError($message);
    return;
}
