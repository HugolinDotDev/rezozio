<?php

set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require_once('lib/session_start.php');

$args = new RequestParameters();
$args->defineNonEmptyString('userId');
$args->defineString('size');

if ($args->size == "")
{
    $args->size = "small";
}

if (!$args->isValid()) 
{
    produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
    return;
}

$data = new DataLayer();
if ($data->userExists($args->userId))
{
    $descFile = $data->getAvatar($args->userId, $args->size);
    if ($descFile)
    {
        $flux = is_null($descFile['data']) ? fopen('../images/default.svg','r') : $descFile['data'];
        $mimeType = is_null($descFile['data']) ? 'image/svg+xml' : $descFile['mimetype'];
        header("Content-type: $mimeType");
        fpassthru($flux);
        exit();
    }
}
else
{
    $message = "L'utilisateur " . $args->userId . " n'existe pas";
    produceError($message);
}
