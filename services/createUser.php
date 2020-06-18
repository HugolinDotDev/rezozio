<?php

set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new RequestParameters('post');
$args->defineNonEmptyString('userId');
$args->defineNonEmptyString('password');
$args->defineNonEmptyString('pseudo');

if (!$args->isValid()) 
{
    produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
    return;
}


if (!filter_input(INPUT_POST, 'userId', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-zA-Z_]{3,25}$/']]))
{
    produceError("L'identifiant doit être > 3 caractères et être composé de lettres non accentuées, chiffres ou underscore");
    return;
}

$data = new DataLayer();

if ($data->userExists($args->userId))
{
    $message = "L'utilisateur " . $args->userId . " existe déjà";
    produceError($message);
    return;
}

$new_user = $data->createUser($args->userId, $args->password, $args->pseudo);
if (is_null($new_user))
{
    produceError("L'identifiant ou le pseudo est trop long, il doit être < 25 caractères");
    return;
}

produceResult($new_user);
