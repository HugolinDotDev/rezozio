<?php

set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require_once('lib/session_start.php');

$args = new RequestParameters();
$args->defineNonEmptyString('userId');

if (!$args->isValid()) 
{
    produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
    return;
}

$data = new DataLayer();

if($data->userExists($args->userId))
{
    $profile = $data->getProfile($args->userId, $_SESSION['ident']);
    if (!isset($_SESSION['ident']))
    {
        unset($profile['followed']);
        unset($profile['isFollower']);
    }
    produceResult($profile);
}
else
{
    $message = "L'utilisateur " . $args->userId . " n'existe pas";
    produceError($message);
}
