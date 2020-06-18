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
if ($data->isFollowing($_SESSION['ident'], $args->target))
{
    $data->unfollow($_SESSION['ident'], $args->target);
    produceResult(true);
}
else
{
    $message = "Vous n'êtes pas déjà abonné à " . $args->target;
    produceError($message);
    return;
}
