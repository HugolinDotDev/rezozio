let messages_count = 15;
let user = null;
let before = 2147483647;

function loadUserProfile()
{
    let currentUserProfile = document.getElementById('currentUserProfile');
    messages_count = 15;
    user = null;
    before = 2147483647;
    let url = "services/getProfile?userId=" + currentUserProfile.children[0].textContent;
    fetchFromJson(url)
        .then(processAnswer)
        .then(displayUserProfile, errorMessage);
}

function displayUserProfile(res)
{
    user = res.userId;
    displayModules(['get_user_profile', 'post_user_message', 'user_thread']);
    document.forms.form_post_user.addEventListener('submit', postUserMessage);
    let user_profile = document.getElementById('get_user_profile');
    user_profile.children[1].children[1].textContent = res.pseudo;
    user_profile.children[1].children[2].textContent = "@" + res.userId;
    user_profile.children[1].children[3].textContent = res.description;
    getLargeUserAvatar(res.userId);
    let url = "services/findMessages?author=" + res.userId;
    fetchFromJson(url)
        .then(processAnswer)
        .then(displayUserMessages, errorMessage);
}

function getLargeUserAvatar(userId)
{
    let changeAvatar = function(blob) {
        let img = document.getElementById('user_large_avatar');
        img.src = URL.createObjectURL(blob);
    };
    fetchBlob('services/getAvatar.php?userId=' + userId + '&size=large')
        .then(changeAvatar);
}

function postUserMessage(evt)
{
    evt.preventDefault();
    let args = new FormData(this);
    let init = {
        method: 'post',
        body: args,
        credentials: 'same-origin'
    };
    fetchFromJson('services/postMessage.php', init)
        .then(processAnswer)
        .then(() => {
            produceToast("Le message a été posté", "success");
        })
        .then(loadUserProfile, errorMessage);
}

function displayUserMessages(res)
{
    let user_thread = document.getElementById('user_thread');
    user_thread.innerHTML = "<h2>Vos derniers messages 🔥</h2>";
    for (m of res)
    {
        let message = produceMessage(m);
        user_thread.appendChild(message);
        getMessageAvatar(m.author, m.id);
        if (m.id < before)
            before = m.id;
    }
    let loadMoreUserMessage = produceLoadMoreButton('loadMoreUserMessage');
    user_thread.appendChild(loadMoreUserMessage);
    loadMoreUserMessage.addEventListener('click', loadMoreUserMessages);
}

function loadMoreUserMessages(evt)
{
    evt.preventDefault();
    if (document.querySelectorAll('#user_thread div.message').length > 0 && (document.querySelectorAll('#user_thread div.message').length % 15) == 0)
    {
        messages_count += 15;
        let url = "services/findMessages?author=" + user + "&count=" + messages_count + "&before=" + before;
        fetchFromJson(url)
            .then(processAnswer)
            .then(displayMoreUserMessages, errorMessage);
    }
}

function displayMoreUserMessages(res)
{
    let user_thread = document.getElementById('user_thread');
    user_thread.removeChild(user_thread.lastElementChild);
    for (m of res)
    {
        let message = produceMessage(m);
        user_thread.appendChild(message);
        getMessageAvatar(m.author, m.id);
        if (m.id < before)
            before = m.id;
    }
    let loadMoreUserMessage = produceLoadMoreButton('loadMoreUserMessage');
    user_thread.appendChild(loadMoreUserMessage);
    loadMoreUserMessage.addEventListener('click', loadMoreUserMessages);
}