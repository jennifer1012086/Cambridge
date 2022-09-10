$('.role-icon')[0].classList.add('bg-lgray');
$('#role').on('change', function() {
    var admin = 4;
    var teacher = 1;
    var cs = $('#class');
    var role_icon = $('.role-icon')[0];
    if ($(this).prop('value') == admin) {
        role_icon.classList.remove('bg-lgray');
        role_icon.classList.add('bg-red');
        cs.css('display', 'none');
    } else if($(this).prop('value') == teacher) {
        role_icon.classList.add('bg-lgray');
        cs.css('display', 'block');
    } else {
        role_icon.classList.add('bg-lgray');
        cs.css('display', 'none');
    }
});