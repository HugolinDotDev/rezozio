<?php

set_include_path('..'.PATH_SEPARATOR);
require('lib/watchdog_service.php');

$args = new RequestParameters('post');
$args->defineString('password');
$args->defineString('pseudo');
$args->defineString('description');

if (!$args->isValid()) 
{
    produceError('Argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
    return;
}

if (strlen($args->pseudo) > 25)
{
    produceError("Le pseudo est trop long (> 25 caractères)");
    return;
}
else if (strlen($args->description) > 1024)
{
    produceError("La description est trop longue (> 1024 caractères)");
    return;
}
else
{
    $data = new DataLayer();
    $identity = $data->setProfile($_SESSION['ident'], $args->password, $args->pseudo, $args->description);
    produceResult($identity);
}