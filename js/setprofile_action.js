window.addEventListener("load", initSetProfile);

function initSetProfile()
{
    let profile = document.getElementById('profile');
    profile.addEventListener('click', displaySetProfile);
}

function displaySetProfile()
{
    displayModules(['set_profile']);
    document.forms.form_set_profile.addEventListener('submit', setProfile);
    document.forms.form_set_avatar.addEventListener('submit', setAvatar);
}

function setProfile(evt)
{
    evt.preventDefault();
    let args = new FormData(this);
    let init = {
        method: 'post',
        body: args,
        credentials: 'same-origin'
    };
    fetchFromJson('services/setProfile.php', init)
        .then(processAnswer)
        .then(() => {
            produceToast("Votre profil a été mis à jour", "success");
        })
        .then(loadAllMessages, errorMessage);
}

function setAvatar(evt)
{
    evt.preventDefault();
    let args = new FormData(this);
    let init = {
        method: 'post',
        body: args,
        credentials: 'same-origin'
    };
    fetchFromJson('services/uploadAvatar.php', init)
        .then(processAnswer)
        .then(() => {
            produceToast("Votre photo de profil a été mise à jour", "success");
        })
        .then(() => {
            getCurrentAvatar(document.body.dataset.user);
        })
        .then(loadAllMessages, errorMessage);
}