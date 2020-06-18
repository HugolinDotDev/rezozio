<?php

set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new RequestParameters();
$args->defineString('author', [
    'default' => ""
]);
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
if ($args->author != "")
{
    if ($data->userExists($args->author))
    {
        $messages = $data->findMessages($args->author, $args->before, $args->count);
        produceResult($messages);
    }
    else
    {
        $message = "L'utilisateur " . $args->author . " n'existe pas";
        produceError($message);
    }
    
}
else
{
    $messages = $data->findMessages($args->author, $args->before, $args->count);
    produceResult($messages); 
}
