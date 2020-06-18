window.addEventListener('load', initMessages);

let g_messages_count = 15;
let g_user = null;
let g_before = 2147483647;

function initMessages()
{
    let home = document.querySelector('#home');
    home.addEventListener("click", loadAllMessages);
    if (isAuth())
    {
        loadFollowedMessages();
    }
    else
    {
        loadAllMessages();
    }
}

function loadFollowedMessages()
{
    fetchFromJson('services/findFollowedMessages.php')
        .then(processAnswer)
        .then(displayFollowedMessages, errorMessage);   
}

function displayFollowedMessages(res)
{
    g_messages_count = 15;
    g_user = null;
    g_before = 2147483647;
    displayModules(["post_message", "thread"]);
    document.forms.form_post.addEventListener('submit', postMessage);
    let thread = document.getElementById('thread');
    thread.innerHTML = "<h2>Derniers messages (abonnements) 🔥</h2>";
    produceFilters();
    for (m of res)
    {
        let message = produceMessage(m);
        thread.appendChild(message);
        getMessageAvatar(m.author, m.id);
        if (m.id < g_before)
            g_before = m.id;
    }
    for (elt of document.querySelectorAll('div.message_header'))
    {
        if (elt.children[2].textContent == ('@' + document.body.dataset.user))
            elt.addEventListener('click', loadUserProfile);
        else
            elt.addEventListener('click', loadProfile);
    }
    let loadButton = produceLoadMoreButton('loadMoreFollowedMessages');
    thread.appendChild(loadButton);
    loadButton.addEventListener('click', loadMoreFollowedMessages);
}

function loadMoreFollowedMessages(evt)
{
    evt.preventDefault();
    if (document.querySelectorAll('#thread div.message').length > 0 && (document.querySelectorAll('#thread div.message').length % 15) == 0)
    {
        g_messages_count += 15;
        let url = "services/findFollowedMessages?count=" + g_messages_count + "&before=" + g_before;
        fetchFromJson(url)
            .then(processAnswer)
            .then(displayMoreFollowedMessages, errorMessage);
    }
}

function displayMoreFollowedMessages(res)
{
    let thread = document.getElementById('thread');
    thread.removeChild(thread.lastElementChild);
    for (m of res)
    {
        let message = produceMessage(m);
        thread.appendChild(message);
        getMessageAvatar(m.author, m.id);
        if (m.id < g_before)
            g_before = m.id;
    }
    for (elt of document.querySelectorAll('div.message_header'))
    {
        if (elt.children[2].textContent == ('@' + document.body.dataset.user))
            elt.addEventListener('click', loadUserProfile);
        else
            elt.addEventListener('click', loadProfile);
    }
    let loadButton = produceLoadMoreButton('loadMoreFollowedMessages');
    thread.appendChild(loadButton);
    loadButton.addEventListener('click', loadMoreFollowedMessages);
}

function loadAllMessages()
{
    fetchFromJson('services/findMessages.php')
        .then(processAnswer)
        .then(displayAllMessages, errorMessage);
}

function displayAllMessages(res)
{
    g_messages_count = 15;
    g_user = null;
    g_before = 2147483647;
    displayModules(["post_message", "thread"]);
    document.forms.form_post.addEventListener('submit', postMessage);
    let thread = document.getElementById('thread');
    thread.innerHTML = "<h2>Derniers messages 🔥</h2>";
    produceFilters();
    for (m of res)
    {
        let message = produceMessage(m);
        thread.appendChild(message);
        getMessageAvatar(m.author, m.id);
        if (m.id < g_before)
            g_before = m.id;
    }
    for (elt of document.querySelectorAll('div.message_header'))
    {
        if (elt.children[2].textContent == ('@' + document.body.dataset.user))
            elt.addEventListener('click', loadUserProfile);
        else
            elt.addEventListener('click', loadProfile);
    }
    let loadButton = produceLoadMoreButton('loadMoreHomeMessages');
    thread.appendChild(loadButton);
    loadButton.addEventListener('click', loadMoreAllMessages);
}

function loadMoreAllMessages(evt)
{
    evt.preventDefault();
    if (document.querySelectorAll('#thread div.message').length > 0 && (document.querySelectorAll('#thread div.message').length % 15) == 0)
    {
        g_messages_count += 15;
        let url = "services/findMessages?count=" + g_messages_count + "&before=" + g_before;
        fetchFromJson(url)
            .then(processAnswer)
            .then(displayMoreAllMessages, errorMessage);
    }
}

function displayMoreAllMessages(res)
{
    let thread = document.getElementById('thread');
    thread.removeChild(thread.lastElementChild);
    for (m of res)
    {
        let message = produceMessage(m);
        thread.appendChild(message);
        getMessageAvatar(m.author, m.id);
        if (m.id < g_before)
            g_before = m.id;
    }
    for (elt of document.querySelectorAll('div.message_header'))
    {
        if (elt.children[2].textContent == ('@' + document.body.dataset.user))
            elt.addEventListener('click', loadUserProfile);
        else
            elt.addEventListener('click', loadProfile);
    }
    let loadButton = produceLoadMoreButton('loadMoreAllMessages');
    thread.appendChild(loadButton);
    loadButton.addEventListener('click', loadMoreAllMessages);
}

function postMessage(evt)
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
        .then(loadAllMessages, errorMessage);
}

function produceFilters()
{
    let thread = document.getElementById('thread');
    let filters = document.createElement('div');
    filters.className = "filters";

    let followed = document.createElement('span');
    followed.if = "followed_button";
    followed.innerHTML = "Abonnements"
    filters.appendChild(followed);
    followed.addEventListener('click', loadFollowedMessages);

    let all = document.createElement('span');
    all.id = "all_button";
    all.innerHTML = "Tous";
    filters.appendChild(all);
    all.addEventListener('click', loadAllMessages);

    let form = document.createElement('form');
    form.id = "form_author";
    form.innerHTML = '<input type="text" name="author" min-length="3" placeholder="@auteur" required/>' +
                     '<button type="submit">🔍</button>';
    filters.appendChild(form);

    thread.appendChild(filters);
    document.forms.form_author.addEventListener('submit', sendAuthorMessages);
}

function sendAuthorMessages(evt)
{
    evt.preventDefault();
    let args = new FormData(this);
    let init = {
        method: 'post',
        body: args,
        credentials: 'same-origin'
    };
    g_user = this.children[0].value;
    fetchFromJson('services/findMessages', init)
        .then(processAnswer)
        .then(displayAuthorMessages, errorMessage);
}

function displayAuthorMessages(res)
{
    g_messages_count = 15;
    g_before = 2147483647;
    displayModules(["post_message", "thread"]);
    document.forms.form_post.addEventListener('submit', postMessage);
    let thread = document.getElementById('thread');
    thread.innerHTML = "<h2>Ses derniers messages 🔥</h2>";
    produceFilters();
    for (m of res)
    {
        let message = produceMessage(m);
        thread.appendChild(message);
        getMessageAvatar(m.author, m.id);
        if (m.id < g_before)
            g_before = m.id;
    }
    for (elt of document.querySelectorAll('div.message_header'))
    {
        if (elt.children[2].textContent == ('@' + document.body.dataset.user))
            elt.addEventListener('click', loadUserProfile);
        else
            elt.addEventListener('click', loadProfile);
    }
    let loadButton = produceLoadMoreButton('loadMoreAuthorMessages');
    thread.appendChild(loadButton);
    loadButton.addEventListener('click', loadMoreAuthorMessages);
}

function loadMoreAuthorMessages(evt)
{
    evt.preventDefault();
    if (document.querySelectorAll('#thread div.message').length > 0 && (document.querySelectorAll('#thread div.message').length % 15) == 0)
    {
        g_messages_count += 15;
        let url = "services/findMessages?count=" + g_messages_count + "&before=" + g_before + "&author=" + g_user;
        fetchFromJson(url)
            .then(processAnswer)
            .then(displayMoreAuthorMessages, errorMessage);
    }
}

function displayMoreAuthorMessages(res)
{
    let thread = document.getElementById('thread');
    thread.removeChild(thread.lastElementChild);
    for (m of res)
    {
        let message = produceMessage(m);
        thread.appendChild(message);
        getMessageAvatar(m.author, m.id);
        if (m.id < g_before)
            g_before = m.id;
    }
    for (elt of document.querySelectorAll('div.message_header'))
    {
        if (elt.children[2].textContent == ('@' + document.body.dataset.user))
            elt.addEventListener('click', loadUserProfile);
        else
            elt.addEventListener('click', loadProfile);
    }
    let loadButton = produceLoadMoreButton('loadMoreAuthorMessages');
    thread.appendChild(loadButton);
    loadButton.addEventListener('click', loadMoreAuthorMessages);
}