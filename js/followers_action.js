window.addEventListener('load', initFollowers);

function initFollowers()
{
    let followers = document.getElementById('followers');
    followers.addEventListener('click', loadFollowers);
}

function loadFollowers()
{
    let url = 'services/getFollowers.php';
    fetchFromJson(url)
        .then(processAnswer)
        .then(displayFollowers, errorMessage);
}

function displayFollowers(res)
{
    displayModules(['followers_m']);
    let followers = document.getElementById('followers_m');
    followers.innerHTML = "<h2>Vos abonnés 🐑</h2>";
    for (f of res)
    {
        let follower = produceFollower(f);
        followers.appendChild(follower);
        getAvatar(f.userId);
        if (!f.mutual)
            document.forms['form_follow_' + f.userId].addEventListener('submit', sendFollow);
        else
            document.forms['form_unfollowbis_' + f.userId].addEventListener('submit', sendUnfollowBis);
    }
    for (elt of document.querySelectorAll('div.follower_id'))
    {
        if (elt.children[2].textContent == ('@' + document.body.dataset.user))
            elt.addEventListener('click', loadUserProfile);
        else
            elt.addEventListener('click', loadProfile);
    }
}

function produceFollower(obj)
{
    let follower = document.createElement('div');
    follower.className = "follower";
    let inner;
    if (!obj.mutual)
    {
        inner = '<div class=follower_id>' +
                '<img class="avatar_' + obj.userId + '" alt="" src="" />' +
                '<span class="pseudo">' + obj.pseudo + '</span>' +
                '<span class="userId">@' + obj.userId + '</span>' +
                '</div><div class="follower_action">' + 
                '<form method="post" id="form_follow_' + obj.userId + '" action="services/follow.php">' +
                '<input type="hidden" name="target" value="' + obj.userId + '" />' +
                '<button type="submit" name="valid"><svg height="512pt" viewBox="0 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg"><path fill="#D21F3C" d="m256 0c-141.164062 0-256 114.835938-256 256s114.835938 256 256 256 256-114.835938 256-256-114.835938-256-256-256zm0 0" fill="#2196f3"/><path d="m368 277.332031h-90.667969v90.667969c0 11.777344-9.554687 21.332031-21.332031 21.332031s-21.332031-9.554687-21.332031-21.332031v-90.667969h-90.667969c-11.777344 0-21.332031-9.554687-21.332031-21.332031s9.554687-21.332031 21.332031-21.332031h90.667969v-90.667969c0-11.777344 9.554687-21.332031 21.332031-21.332031s21.332031 9.554687 21.332031 21.332031v90.667969h90.667969c11.777344 0 21.332031 9.554687 21.332031 21.332031s-9.554687 21.332031-21.332031 21.332031zm0 0" fill="#fafafa"/></svg></button>' +
                '</form></div>';
    }
    else
    {
        inner = '<div class=follower_id>' +
                '<img class="avatar_' + obj.userId + '" alt="" src="" />' +
                '<span class="pseudo">' + obj.pseudo + '</span>' +
                '<span class="userId">@' + obj.userId + '</span>' +
                '</div><div class="follower_action">' + 
                '<span class="mutual">Suivi mutuel</span>' +
                '<form method="post" id="form_unfollowbis_' + obj.userId + '" action="services/unfollow.php">' +
                '<input type="hidden" name="target" value="' + obj.userId + '" />' +
                '<button type="submit" name="valid"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><ellipse style="fill:#D21F3C;" cx="256" cy="256" rx="256" ry="255.832"/><rect x="113.2" y="228" style="fill:#FFFFFF;" width="285.672" height="56"/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></button>' +
                '</form></div>';
    }
    follower.innerHTML = inner;
    return follower;
}

function sendFollow(evt)
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
    fetchFromJson('services/follow.php', init)
        .then(processAnswer)
        .then(() => {
            produceToast("Vous êtes maintenant abonné à " + splited[2], "success");
        })
        .then(loadFollowers, errorMessage);
}

function sendUnfollowBis(evt)
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
        .then(loadFollowers, errorMessage);
}