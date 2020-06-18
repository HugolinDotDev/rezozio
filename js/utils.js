function isAuth()
{
    if("user" in document.body.dataset)
        return true;
    else
        return false;
}

function produceToast(message, type = "error")
{   
    if (document.body.contains(document.getElementById('toast')))
        document.getElementById('toast').remove();
    let aside = document.createElement('aside');
    aside.id = "toast";
    aside.appendChild(document.createElement('h3'));
    aside.appendChild(document.createElement('p'));
    if (type == "success")
    {
        aside.children[0].innerHTML = "Succès";
        aside.classList = "success";
    }
    else if (type == "error")
    {
        aside.children[0].innerHTML = "Échec";
        aside.classList = "error";
    }
    aside.children[1].innerHTML = message;
    aside.style.display = "block";
    document.body.appendChild(aside);
    aside.addEventListener("click", hideToast);
    setTimeout(() => {
      aside.remove();
    }, 5000);
}

function hideToast()
{
    this.removeEventListener("click", hideToast);
    this.remove();
}

function displayModules(modules_array)
{
    for (let i = 0; i < document.getElementById('content').children.length; i++)
    {
        if (modules_array.includes(document.querySelector('section#content').children[i].id))
            document.querySelector('section#content').children[i].style.display = "block";
        else
            document.querySelector('section#content').children[i].style.display = "none";
    }
}

function errorMessage(error)
{
    produceToast(error.message);
}

function getAvatar(userId) {
    let changeAvatar = function(blob) {
        for (elt of document.querySelectorAll('.avatar_' + userId))
            elt.src = URL.createObjectURL(blob);
    };
    fetchBlob('services/getAvatar.php?userId=' + userId + '&size=small')
        .then(changeAvatar);
}

function getMessageAvatar(userId, messageId) {
    let changeAvatar = function(blob) {
        for (elt of document.querySelectorAll('.avatar_' + messageId))
            elt.src = URL.createObjectURL(blob);
    };
    fetchBlob('services/getAvatar.php?userId=' + userId + '&size=small')
        .then(changeAvatar);
}

function produceMessage(obj)
{
    let message = document.createElement('div');
    message.className = "message";
    let string_date = obj.datetime;
    if (/.*[+-][0-9]{2}$/.exec(string_date))
        string_date += ':00';
    let date = new Date(string_date);
    let inner = '<div class="message_header">' +
                '<img class="avatar_' + obj.id + '" alt="avatar" src="" />' +
                '<span class="pseudo">' + obj.pseudo + '</span>' +
                '<span class="author">@' + obj.author + '</span></div>' +
                '<div class="date_container"><span class="date">Le ' + date.toLocaleString() + '</span>' +
                '</div><div class="message_text"><p>' +
                obj.content + '</p></div>';
    message.innerHTML = inner;
    return message;
}

function produceLoadMoreButton(id)
{
    let loadMoreButton = document.createElement('a');
    loadMoreButton.href = "#";
    loadMoreButton.classList  = "more_messages";
    loadMoreButton.textContent = "Voir +";
    loadMoreButton.id = id;
    return loadMoreButton;
}