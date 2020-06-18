window.addEventListener("load", initAuth);

var currentUser = null;

function initAuth()
{
    let register_btn = document.getElementById('register_btn');
    let login_btn = document.getElementById('login_btn');
    register_btn.addEventListener("click", displayRegisterForm);
    login_btn.addEventListener("click", displayLoginForm);
    document.forms.form_login.addEventListener('submit', sendLogin);
    document.forms.form_register.addEventListener('submit', sendRegister);
    document.querySelector('#logout').addEventListener('click', sendLogout);

    if (isAuth())
    {
        register_btn.style.display = "none";
        login_btn.style.display = "none";
        setLogged(JSON.parse(document.body.dataset.user));
    }
    else
    {
        register_btn.style.display = "block";
        login_btn.style.display = "block";
        setGuest();
    }    
}

function displayRegisterForm()
{
    displayModules(["register"]);
}

function displayLoginForm()
{
    displayModules(["authentication"]);
}


function sendLogin(evt)
{
    evt.preventDefault();
    let args = new FormData(this);
    let init = {
        method: 'post',
        body: args,
        credentials: 'same-origin'
    };
    fetchFromJson('services/login.php', init)
        .then(processAnswer)
        .then((res) => {
            produceToast("Vous êtes maintenant connecté", "success");
            return res;
        })
        .then(setLogged, errorMessage);
}

function sendLogout(evt)
{
    evt.preventDefault();
    let init = {
        method: 'post',
        credentials: 'same-origin'
    };
    fetchFromJson('services/logout.php', init)
        .then(processAnswer)
        .then(() => {
            produceToast("Vous êtes maintenant déconnecté", "success");
        })
        .then(setGuest);
}

function sendRegister(evt)
{
    evt.preventDefault();
    let args = new FormData(this);
    let init = {
        method: 'post',
        body: args,
        credentials: 'same-origin'
    };
    fetchFromJson('services/createUser.php', init)
        .then(processAnswer)
        .then(() => {
            produceToast("Le compte a bien été crée, vous pouvez vous connecter avec celui-ci", "success");
        })
        .then(setGuest, errorMessage);
}

function setLogged(user)
{
    currentUser = user;
    document.body.dataset.user = currentUser;
    for (let elt of document.querySelectorAll('a.guest'))
       elt.style.display = "none";
    for (let elt of document.querySelectorAll('a.logged'))
       elt.style.display = "block";
    document.querySelector('#currentUser').innerHTML= 'Hey <a id="currentUserProfile" href="#"><strong>' + currentUser + '</strong></a>';
    let img = document.createElement('img');
    img.id = 'current_avatar_' + currentUser;
    let currentUserProfile = document.getElementById('currentUserProfile');
    currentUserProfile.appendChild(img);
    currentUserProfile.addEventListener('click', loadUserProfile);
    getCurrentAvatar(user);
    loadFollowedMessages();
}

function setGuest()
{
    document.body.removeAttribute('data-user');
    for (let elt of document.querySelectorAll('a.logged'))
       elt.style.display = "none";
    for (let elt of document.querySelectorAll('a.guest'))
       elt.style.display = "block";
    currentUser = null; 
    delete(document.body.dataset.user);
    document.querySelector('#currentUser').innerHTML = '';
    loadAllMessages();
}

function getCurrentAvatar(userId) {
    let changeAvatar = function(blob) {
        let img = document.getElementById('current_avatar_' + userId);
        img.src = URL.createObjectURL(blob);
    };
    fetchBlob('services/getAvatar.php?userId=' + userId + '&size=small')
        .then(changeAvatar);
}