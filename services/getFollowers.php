<?php

set_include_path('..'.PATH_SEPARATOR);
require('lib/watchdog_service.php');

$data = new DataLayer();
$followers = $data->getFollowers($_SESSION['ident']);
for ($i = 0; $i < count($followers); $i++)
{
    if ($data->isFollowing($_SESSION['ident'], $followers[$i]['userId']))
        $followers[$i]['mutual'] = true;
    else
        $followers[$i]['mutual'] = false;
}
produceResult($followers);
