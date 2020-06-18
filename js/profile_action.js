let o_messages_count = 15;
let o_user = null;
let o_before = 2147483647;

function loadProfile()
{
    o_messages_count = 15;
    o_user = null;
    o_before = 2147483647;
    let userId = this.children[2].textContent.replace('@', '');
    let url = "services/getProfile?userId=" + userId;
    fetchFromJson(url)
        .then(processAnswer)
        .then(displayProfile, errorMessage);
}

function displayProfile(res)
{
    o_user = res.userId;
    displayModules(['get_profile', 'other_thread']);
    let profile = document.getElementById('get_profile');
    profile.children[1].children[1].textContent = res.pseudo;
    profile.children[1].children[2].textContent = "@" + res.userId;
    profile.children[1].children[3].textContent = res.description;
    getLargeAvatar(res.userId);
    let url = "services/findMessages?author=" + res.userId;
    if (isAuth())
    {
        fetchFromJson("services/getFollowers.php")
            .then(processAnswer)
            .then(isAFollower, errorMessage);
    }
    fetchFromJson(url)
        .then(processAnswer)
        .then(displayProfileMessages, errorMessage);
}

function getLargeAvatar(userId)
{
    let changeAvatar = function(blob) {
        let img = document.querySelector('#get_profile img');
        img.src = URL.createObjectURL(blob);
    };
    fetchBlob('services/getAvatar.php?userId=' + userId + '&size=large')
        .then(changeAvatar);
}

function displayProfileMessages(res)
{
    let other_thread = document.getElementById('other_thread');
    other_thread.innerHTML = "<h2>Ses derniers messages 🔥</h2>";
    for (m of res)
    {
        let message = produceMessage(m);
        other_thread.appendChild(message);
        getMessageAvatar(m.author, m.id);
        if (m.id < o_before)
            o_before = m.id;
    }
    let loadButton = produceLoadMoreButton('loadMoreProfileMessages');
    other_thread.appendChild(loadButton);
    loadButton.addEventListener('click', loadMoreProfileMessages);
}

function loadMoreProfileMessages(evt)
{
    evt.preventDefault();
    if (document.querySelectorAll('#other_thread div.message').length > 0 && (document.querySelectorAll('#other_thread div.message').length % 15) == 0)
    {
        o_messages_count += 15;
        let url = "services/findMessages?author=" + o_user + "&count=" + o_messages_count + "&before=" + o_before;
        fetchFromJson(url)
            .then(processAnswer)
            .then(displayMoreProfileMessages, errorMessage);
    }
}

function displayMoreProfileMessages(res)
{
    let other_thread = document.getElementById('other_thread');
    other_thread.removeChild(other_thread.lastElementChild);
    for (m of res)
    {
        let message = produceMessage(m);
        other_thread.appendChild(message);
        getMessageAvatar(m.author, m.id);
        if (m.id < o_before)
            o_before = m.id;
    }
    let loadButton = produceLoadMoreButton('loadMoreProfileMessages');
    other_thread.appendChild(loadButton);
    loadButton.addEventListener('click', loadMoreProfileMessages);
}

function isAFollower(res)
{
    let profile = document.getElementById('get_profile');
    state = false;
    for (u of res)
    {
        if (u.userId == o_user)
            state = true;
    }
    if (state)
        profile.children[1].children[4].textContent = "L'utilisateur vous suit";
    else
        profile.children[1].children[4].textContent = "L'utilisateur ne vous suit pas";

    fetchFromJson('services/getSubscriptions.php')
        .then(processAnswer)
        .then(isAFollowed, errorMessage);
}

function isAFollowed(res)
{
    let state = false;
    for (u of res)
    {
        if (u.userId == o_user)
            state = true;
    }
    if (state)
        unfollowProfileForm();
    else
        followProfileForm();
}

function unfollowProfileForm()
{
    document.forms.form_get_profile.removeEventListener('submit', sendFollowProfile);
    let form = document.getElementById('form_get_profile');
    form.action = "services/unfollow.php";
    let input = document.getElementById('get_profile_target');
    input.value = o_user;
    let button = document.getElementById('get_profile_submit');
    button.innerHTML = 'Ne plus suivre<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><ellipse style="fill:#D21F3C;" cx="256" cy="256" rx="256" ry="255.832"/><rect x="113.2" y="228" style="fill:#FFFFFF;" width="285.672" height="56"/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>';
    document.forms.form_get_profile.addEventListener('submit', sendUnfollowProfile);
}

function followProfileForm()
{
    document.forms.form_get_profile.removeEventListener('submit', sendUnfollowProfile);
    let form = document.getElementById('form_get_profile');
    form.action = "services/follow.php";
    let input = document.getElementById('get_profile_target');
    input.value = o_user;
    let button = document.getElementById('get_profile_submit');
    button.innerHTML = 'Suivre<svg height="512pt" viewBox="0 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg"><path fill="#D21F3C" d="m256 0c-141.164062 0-256 114.835938-256 256s114.835938 256 256 256 256-114.835938 256-256-114.835938-256-256-256zm0 0" fill="#2196f3"/><path d="m368 277.332031h-90.667969v90.667969c0 11.777344-9.554687 21.332031-21.332031 21.332031s-21.332031-9.554687-21.332031-21.332031v-90.667969h-90.667969c-11.777344 0-21.332031-9.554687-21.332031-21.332031s9.554687-21.332031 21.332031-21.332031h90.667969v-90.667969c0-11.777344 9.554687-21.332031 21.332031-21.332031s21.332031 9.554687 21.332031 21.332031v90.667969h90.667969c11.777344 0 21.332031 9.554687 21.332031 21.332031s-9.554687 21.332031-21.332031 21.332031zm0 0" fill="#fafafa"/></svg>';
    document.forms.form_get_profile.addEventListener('submit', sendFollowProfile);
}

function sendUnfollowProfile(evt)
{
    evt.preventDefault();
    let args = new FormData(this);
    let init = {
        method: 'post',
        body: args,
        credentials: 'same-origin'
    };
    fetchFromJson('services/unfollow.php', init)
        .then(processAnswer)
        .then(() => {
            produceToast("Vous êtes désabonné de " + o_user, "success");
        })
        .then(followProfileForm, errorMessage);
}

function sendFollowProfile(evt)
{
    evt.preventDefault();
    let args = new FormData(this);
    let init = {
        method: 'post',
        body: args,
        credentials: 'same-origin'
    };
    fetchFromJson('services/follow.php', init)
        .then(processAnswer)
        .then(() => {
            produceToast("Vous êtes maintenant abonné à " + o_user, "success");
        })
        .then(unfollowProfileForm, errorMessage);
}