window.addEventListener('load', initCredits);

function initCredits()
{
    let credits = document.getElementById('credits_l');
    credits.addEventListener('click', displayCredits);
}

function displayCredits()
{
    displayModules(['credits']);
}