<?php
     require_once('views/lib/HTMLfunctions.php');

     $userData = ""; 

     if (isset($user))
          $userData = 'data-user=' . htmlentities(json_encode($user));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
     <meta charset="UTF-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <title>Rézozio</title>
     <link rel="stylesheet" href="style/main.css" />
     <link rel="stylesheet" href="style/modules.css" />
     <script src="js/fetchUtils.js"></script>
     <script src="js/utils.js"></script>
     <script src="js/profile_action.js"></script>
     <script src="js/credits_action.js"></script>
     <script src="js/messages_action.js"></script>
     <script src="js/followed_action.js"></script>
     <script src="js/followers_action.js"></script>
     <script src="js/search_action.js"></script>
     <script src="js/setprofile_action.js"></script>
     <script src="js/userprofile_action.js"></script>
     <script src="js/authentication.js"></script>
</head>
<?= '<body id="top" ' . $userData . '>' ?>
     <header>
          <div id="container">
               <div id="logo">
                    <img src="images/bird.svg" alt="rézozio logo" />
                    <h1>Rézozio</h1>
               </div>
               <nav>
                    <span id="currentUser" class="logged"></span>
                    <a class="guest" id="login_btn" href="#">Se connecter</a>
                    <a class="guest" id="register_btn" href="#">S'enregistrer</a>
               </nav>
          </div>
     </header>
