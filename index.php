<?php
spl_autoload_register(function ($className) {
    include ("lib/{$className}.class.php");
});

require_once('lib/session_start.php');

if (isset($_SESSION['ident'])){
    $user = $_SESSION['ident'];
}

date_default_timezone_set ('Europe/Paris');
require_once('views/components/header.php');
require_once('views/components/sidebar.php');
require_once('views/content.php');
require_once('views/components/footer.php');
