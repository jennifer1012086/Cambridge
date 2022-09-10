$('.ckbox').prop('checked', true);
subContent();

$('#all').on('click', function() {
    if($('.ckbox').prop('checked') != true) {
        $('.ckbox').prop('checked', true);
    } else {
        $('.ckbox').prop('checked', false);
    }
    subContent();
});
$('.sub-check').on('click', function() {
    subContent();
    // child
    var str = $(this).prop('name').split('_')[1];
    var child = $('#subMenu'+str).find('li');
    if($(this).prop('checked') == true) {
        for (var i = 0; i < child.length; i++) 
            child[i].children[0].children[0].checked = true;
    } else {
        for (var i = 0; i < child.length; i++) 
            child[i].children[0].children[0].checked = false;
    }
});
$('.item-check').on('click', function() {
    var str2 = $(this).attr('id').split('_')[1];
    var len = $('#subMenu'+str2).find('li').length;
    if (len == 1) {
        if($(this).prop('checked') == true)
            ($('input[name = "sub_'+str2+'"]')[0]).checked = true;
        else
            ($('input[name = "sub_'+str2+'"]')[0]).checked = false;
        subContent();
    } else {
        var str3 = $(this).attr('id').split('_')[1]+'_'+$(this).attr('id').split('_')[2];
        var itemname = ($('#itemname_'+str3)[0]).innerHTML;
        var array = $('.itemname');
        var count = 0;
        for (var i = 0; i < array.length; i++) {
            if (array[i].innerHTML == itemname) {
                if($(this).prop('checked') == true) {
                    array[i].parentNode.style.display = 'table-row';
                } else {
                    array[i].parentNode.style.display = 'none';
                }
            }
        }
        var count = 0;
        var list = $('#subMenu'+str2).find('li');
        for (var i = 0; i < list.length; i++) {
            if (list[i].children[0].children[0].checked)
                count++;
        }
        if (count == list.length)
            ($('input[name = "sub_'+str2+'"]')[0]).checked = true;
        else if (count == 0)
            ($('input[name = "sub_'+str2+'"]')[0]).checked = false;
    }
});
function subContent() {
    var arr = $('.sub-check');
    for (var i = 0; i < arr.length; i++) {
        var id = arr[i].id;
        if(arr[i].checked == true) {
            $('.td-'+id.split('-')[1]).css('display','table-row');
            $('.td-'+id.split('-')[1]).addClass('td-show');
        } else {
            $('.td-'+id.split('-')[1]).css('display','none');
            $('.td-'+id.split('-')[1]).removeClass('td-show');
        }
    }
}
