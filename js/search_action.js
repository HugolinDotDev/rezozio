window.addEventListener('load', initSearch);

function initSearch()
{
    let search = document.getElementById('search');
    search.addEventListener('click', displaySearch);
}

function displaySearch()
{
    displayModules(['search_user']);
    document.forms.form_search_user.addEventListener('submit', loadUsers);
}

function loadUsers(evt)
{
    evt.preventDefault()
    let args = new FormData(this);
    let init = {
        method: 'post',
        body: args,
        credentials: 'same-origin'
    };
    fetchFromJson('services/findUsers.php', init)
        .then(processAnswer)
        .then(displayFoundUsers, errorMessage);
}

function displayFoundUsers(res)
{
    displayModules(['search_user', 'found_users']);
    let found_users = document.getElementById('found_users');
    found_users.innerHTML = "<h2>Utilisateurs trouvés 🕵️‍♀️</h2>";
    for (u of res)
    {
        let user = produceFoundUser(u);
        found_users.appendChild(user);
        getAvatar(u.userId);
    }
    for (elt of document.querySelectorAll('div.found_user'))
    {
        if (elt.children[2].textContent == ('@' + document.body.dataset.user))
            elt.addEventListener('click', loadUserProfile);
        else
            elt.addEventListener('click', loadProfile);
    }
}

function produceFoundUser(obj)
{
    let user = document.createElement('div');
    user.className = "found_user";
    let inner = '<img class="avatar_' + obj.userId + '" alt="" src="" />' +
                '<span class="pseudo">' + obj.pseudo + '</span>' +
                '<span class="userId">@' + obj.userId + '</span>';
    user.innerHTML = inner;
    return user;
}