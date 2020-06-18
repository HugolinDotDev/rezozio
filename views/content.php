<section id="content">
    <div id="get_user_profile">
        <h2>Votre profil 👻</h2>
        <div>
            <img id="user_large_avatar" src="" alt="" />
            <span class="user_pseudo"></span>
            <span class="user_userId"></span>
            <p class="description"></p>
        </div>
    </div>

    <div id="post_user_message">
        <h2>Quelque chose à dire ? 👀</h2>
        <form action="services/postMessage.php" method="post" id="form_post_user">
            <textarea spellcheck="false" id="source_u" name="source" placeholder="280 caractères maximum" minlength="1" maxlength="280" required></textarea><br/>
            <button type="submit" name="valid" value="post">Poster 📩</button>
        </form>
    </div>

    <div id="user_thread">
    </div>

    <div id="get_profile">
        <h2>Profil 👻</h2>
        <div>
            <img id="large_avatar" src="" alt="" />
            <span class="user_pseudo"></span>
            <span class="user_userId"></span>
            <p class="description"></p>
            <span class="follower"></span>
            <form id="form_get_profile" class="followed" method="post">
                <input type="hidden" id="get_profile_target" name="target" value="" />
                <button id="get_profile_submit" type="submit" name="valid"></button>
            </form>
        </div>
    </div>

    <div id="other_thread">
    </div>

    <div id="set_profile">
        <h2>Modifier votre profil 🔓</h2>
        <form action="services/setProfile.php" method="post" id="form_set_profile">
            <div class="dark_form_wrapper">
                <label for="pseudo">Pseudo :</label><br/>
                <input type="text" name="pseudo" id="pseudo" /><br/>
                <label for="password">Mot de passe :</label><br/>
                <input type="password" name="password" id="password" /><br/>
                <label for="description">Description :</label><br/>
                <textarea name="description" id="description" placeholder="1024 caractères maximum" minlength="1" maxlength="1024"></textarea><br/>
            </div>
            <button id="setprofile_button" type="submit" name="valid" value="reg">Actualiser le profil 🔄</button>
        </form>

        <form action="services/uploadAvatar.php" method="post" id="form_set_avatar">
            <div class="dark_form_wrapper">
                <label for="avatar">Photo de profil :</label><br/>
                <input type="file" id="image" name="image" required="required"/>
            </div>
            <button id="setavatar_button" type="submit" name="valid" value="reg">Actualiser la photo de profil 🔄</button>
        </form>
    </div>

    <div id="post_message">
        <h2>Quelque chose à dire ? 👀</h2>
        <form action="services/postMessage.php" method="post" id="form_post">
            <textarea spellcheck="false" id="source" name="source" placeholder="280 caractères maximum" minlength="1" maxlength="280" required></textarea><br/>
            <button id="post_button" type="submit" name="valid" value="post">Poster 📩</button>
        </form>
    </div>

    <div id="thread">
    </div>

    <div id="search_user">
        <h2>Rechercher un utilisateur 👤</h2>
        <form id="form_search_user" action="services/findUsers.php">
            <div class="dark_form_wrapper">
                <label for="searchedString">Identifiant / Pseudo de l'utilisateur :</label><br/>
                <input type="text" name="searchedString" id="searchedString" required /><br/>
            </div>
            <button id="search_user_button" type="submit" name="valid" value="search_user">Rechercher 🔍</button>
        </form>
    </div>

    <div id="found_users">
    </div>

    <div id="authentication">
        <h2>Authentification 🔐</h2>
        <form action="services/login.php" method="post" id="form_login">
            <div class="dark_form_wrapper">
                <label for="login">Identifiant :</label><br/>
                <input type="text" name="login" id="login" required /><br/>
                <label for="password_l">Mot de passe :</label><br/>
                <input type="password" name="password" id="password_l" required /><br/>
            </div>
            <button id="login_button" type="submit" name="valid" value="auth">Se connecter 🔌</button>
        </form>
    </div>

    <div id="register">
        <h2>Créer un compte 🔏</h2>
        <form action="services/createUser.php" method="post" id="form_register">
            <div class="dark_form_wrapper">
                <label for="userId">Identifiant :</label><br/>
                <input type="text" name="userId" id="userId" required /><br/>
                <label for="pseudo_r">Pseudo :</label><br/>
                <input type="text" name="pseudo" id="pseudo_r" required /><br/>
                <label for="password_r">Mot de passe :</label><br/>
                <input type="password" name="password" id="password_r" required /><br/>
            </div>
            <button id="register_button" type="submit" name="valid" value="reg">S'enregistrer 🗳</button>
        </form>
    </div>

    <div id="subscriptions">
    </div>

    <div id="followers_m">
    </div>

    <div id="credits">
        <h2>Crédits | Remerciements 🎈</h2>
        <div>
            <ul>
                <li><a href="https://twitter.com">Twitter</a> pour l'inspiration de l'interface utilisateur</li>
                <li>
                    <a href="https://www.flaticon.com/authors/freepik">Freepik</a> pour les icônes suivants :
                    <ul>
                        <li>Icône d'oiseau pour l'entête du site</li>
                        <li>Icône des abonnements</li>
                        <li>Icône des abonnés</li>
                        <li>Icône du profil</li>
                    </ul>
                </li>
                <li><a href="https://www.flaticon.com/authors/those-icons">Those Icons</a> pour l'icône de recherche</li>
                <li><a href="https://www.flaticon.com/authors/bqlqn">bqlqn</a> pour l'icône de la page d'accueil</li>
                <li><a href="https://www.flaticon.com/authors/dmitri13">dmitri13</a> pour l'icône de déconnexion</li>
                <li><a href="https://www.flaticon.com/authors/pixel-perfect">Pixel perfect</a> pour l'icône d'abonnement</li>
                <li><a href="https://www.flaticon.com/authors/alfredo-hernandez">Alfredo Hernandez</a> pour l'icône de désabonnement</li>
                <li><a href="https://pixabay.com/fr/users/wanderercreative-855399/">WandererCreative</a> pour la photo de profil par défaut</li>
                <li><a href="https://www.flaticon.com">Le site Flaticon</a> pour faciliter la recherche d'icônes</li>
                <li>Ma copine pour son soutien moral</li>
            </ul>
        </div>
    </div>

</section>
