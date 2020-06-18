window.addEventListener('load', initFollowed);

function initFollowed()
{
    let followed = document.getElementById('followed');
    followed.addEventListener('click', loadFollowed);
}

function loadFollowed()
{
    let url = 'services/getSubscriptions.php';
    fetchFromJson(url)
        .then(processAnswer)
        .then(displayFollowed, errorMessage);
}

function displayFollowed(res)
{
    displayModules(['subscriptions']);
    let subscriptions = document.getElementById('subscriptions');
    subscriptions.innerHTML = "<h2>Vos abonnements 🧞‍♂️</h2>";
    for (f of res)
    {
        let followed = produceFollowed(f);
        subscriptions.appendChild(followed);
        getAvatar(f.userId);
        document.forms['form_unfollow_' + f.userId].addEventListener('submit', sendUnfollow);
    }
    for (elt of document.querySelectorAll('div.followed_id'))
    {
        if (elt.children[2].textContent == ('@' + document.body.dataset.user))
            elt.addEventListener('click', loadUserProfile);
        else
            elt.addEventListener('click', loadProfile);
    }
}

function produceFollowed(obj)
{
    let followed = document.createElement('div');
    followed.className = "followed";
    let inner = '<div class=followed_id>' +
                '<img class="avatar_' + obj.userId + '" alt="" src="" />' +
                '<span class="pseudo">' + obj.pseudo + '</span>' +
                '<span class="userId">@' + obj.userId + '</span>' +
                '</div><div class="followed_action">' + 
                '<form method="post" id="form_unfollow_' + obj.userId + '" action="services/unfollow.php">' +
                '<input type="hidden" name="target" value="' + obj.userId + '" />' +
                '<button type="submit" name="valid"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><ellipse style="fill:#D21F3C;" cx="256" cy="256" rx="256" ry="255.832"/><rect x="113.2" y="228" style="fill:#FFFFFF;" width="285.672" height="56"/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></button>' +
                '</form></div>';
    followed.innerHTML = inner;
    return followed;
}

function sendUnfollow(evt)
{
    evt.preventDefault();
    let args = new FormData(this);
    let init = {
        method: 'post',
        body: args,
        credentials: 'same-origin'
    };
    let arr = this.id.split('_');
    let splited = arr.slice(0, 2);
    splited.push(arr.slice(2).join('_'));
    fetchFromJson('services/unfollow.php', init)
        .then(processAnswer)
        .then(() => {
            produceToast("Vous êtes désabonné de " + splited[2], "success");
        })
        .then(loadFollowed, errorMessage);
}