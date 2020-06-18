<?php

set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require_once('lib/session_start.php');

if (!isset($_SESSION['ident']))
{
    $args = new RequestParameters('post');
    $args->defineNonEmptyString('login');
    $args->defineNonEmptyString('password');

    if (!$args->isValid()) 
    {
        produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
        return;
    }

    $data = new DataLayer();
    $user = $data->login($args->login, $args->password);
    if (is_null($user))
    {
        produceError('Le login ou le mot de passe est incorrect');
        return;
    }

    $_SESSION['ident'] = $user['userId'];
    produceResult($args->login);
}
else
{
    produceError("Vous êtes déjà authentifié");
    return;
}