const nav = document.getElementById('btn-nav');
const user = document.getElementById('btn-user');
nav.addEventListener('click', function(e) {
    user.classList.toggle('hide');
});
user.addEventListener('click', function(e) {
    nav.classList.toggle('invisible');
})