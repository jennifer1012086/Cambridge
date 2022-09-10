var uid = 0;
const pen = document.getElementsByClassName('fa-pen');
const save = document.getElementsByClassName('fa-save');
const limit = document.getElementsByName('limit');
document.getElementsByTagName('tbody')[0].addEventListener('click', function(e) {
    if (e.target.tagName === 'I') {
        let value = e.target.getAttribute('data-value');
        pen[value].classList.toggle('hide');
        save[value].classList.toggle('hide');
        if ($(e.target).hasClass('fa-pen')) {
            let num = limit[value].textContent;
            limit[value].innerHTML = `<input type="number" value="${num}">`
        } else if ($(e.target).hasClass('fa-save')) {
        	// add
        	let tmp = limit[value].querySelector('input').value;
        	let oid = $(e.target).attr('id').split('-')[1];
            let info = [oid, uid];
            limit[value].innerHTML = tmp;
            if (!isNaN(oid) && uid != 0) {
				$.ajax({
					url: '../lib/frommoreajax.php',
					data: {
						action: 'ajaxModifySLimit',
						value1: info,
						value2: tmp
					},
					type: 'post'
				});
			}
        }
    }
});
var sub = {'zh-tw': '次分類', 'en': 'Sub Category'};
$("#main").on("change", function() {
	let value = $(this).prop('value');
	$("#sub").empty();
	$("#sub").append("<option selected disabled hidden>"+sub[lang]+"</option>");
	$.ajax({
		url: '../lib/fromajaxcall.php',
		data: {
			action: 'ajaxGetSub',
			value: value
		},
		type: 'post',
		success: function (response) {
			if (response != '') {
				var arr = JSON.parse(response);
				for (var i = 0; i < arr.length; i++) {
					var name = $(arr[i]['name']).attr(lang);
					$("#sub").append("<option value='"+arr[i]['catid']+"'>"+name+"</option>");
				}
			}
		}
	});
});
$("#sub").on("change", function() {
	let value = $(this).prop('value');
	uid =  $('#user').prop('value');
	$("#item").empty();
	if (!isNaN(value) && uid != 0) {
		$.ajax({
			url: '../lib/frommoreajax.php',
			data: {
				action: 'ajaxGetItemLimit',
				value1: value,
				value2: uid
			},
			type: 'post',
			success: function (response) {
				if (response != '') {
					let arr = JSON.parse(response);
					for (let i = 0; i < arr.length; i++) {
						let item = $(arr[i]['item']).attr(lang);
						let color = $(arr[i]['color']).attr(lang);
						let info = "\t/"+color+"\t/"+arr[i]['size'];
						if (color == '' || color == undefined || color == null) {
							if (arr[i]['size'] == '' || arr[i]['size'] == undefined || arr[i]['size'] == null)
								info = '';
							else
								info = "\t/"+arr[i]['size'];
						} else if(arr[i]['size'] == '' || arr[i]['size'] == undefined || arr[i]['size'] == null) {
							info = "\t/"+color;
						}
						$("#item").append('<tr><th>'+item+info+'</th><th name="limit" data-value="'+i+'">'+arr[i]['num']+'</th><th><i id="pen-'+arr[i]['oid']+'" class="fas fa-pen" data-value="'+i+'"></i><i id="save-'+arr[i]['oid']+'" class="fas fa-save hide" data-value="'+i+'"></i></th></tr>');
					}
				}
			}
		});
	}
});


