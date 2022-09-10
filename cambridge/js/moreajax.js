var sub = {'zh-tw': '次分類', 'en': 'Sub Category'};
var item = {'zh-tw': '品名', 'en': 'Item'};
$("#main").on("change", function() {
	var value = $(this).prop('value');
	$("#sub").empty();
	$("#item").empty();
	$('#remaining').val('');
	$('#accumulation').val('');
	$("#sub").append("<option selected disabled hidden>"+sub[lang]+"</option>");
	$("#item").append("<option selected disabled hidden>"+item[lang]+"</option>");
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
	var value = $(this).prop('value');
	$("#item").empty();
	$("#item").append("<option selected disabled hidden>"+item[lang]+"</option>");
	$('#remaining').val('');
	$('#accumulation').val('');
	if (!isNaN(value)) {
		$.ajax({
			url: '../lib/fromajaxcall.php',
			data: {
				action: 'ajaxGetItem',
				value: value
			},
			type: 'post',
			success: function (response) {
				if (response != '') {
					var arr = JSON.parse(response);
					for (var i = 0; i < arr.length; i++) {
						var item = $(arr[i]['item']).attr(lang);
						var color = $(arr[i]['color']).attr(lang);
						var info = "\t/"+color+"\t/"+arr[i]['size'];
						if (color == '' || color == undefined) {
							if (arr[i]['size'] == '' || arr[i]['size'] == undefined)
								info = '';
							else
								info = "\t/"+arr[i]['size'];
						} else if(arr[i]['size'] == '' || arr[i]['size'] == undefined || arr[i]['size'] == null) {
							info = "\t/"+color;
						}
						$("#item").append("<option value='"+arr[i]['oid']+"'>"+item+info+"</option>");
					}
				}
			}
		});
	}
});
$("#item").on("change", function() {
	var value = $(this).prop('value');
	if (!isNaN(value)) {
		$.ajax({
			url: '../lib/fromajaxcall.php',
			data: {
				action: 'ajaxGetItemInfo',
				value: value
			},
			type: 'post',
			success: function (response) {
				if (response != '') {
					var arr = JSON.parse(response);
					for (var i = 0; i < arr.length; i++) {
						var color = $(arr[i]['color']).attr(lang);
						$("#brand").val(arr[i]['com']);
						$("#size").val(arr[i]['size']);
						$("#color").val(color);
						$("#oid").val(value);
						$("#unit").val(arr[i]['unit']);
					}
				}
			}
		});
		$.ajax({
			url: '../lib/fromajaxcall.php',
			data: {
				action: 'ajaxGetIventory',
				value: value
			},
			type: 'post',
			success: function (response) {
				if (response != '') {
					var arr = JSON.parse(response);
					for (var i = 0; i < arr.length; i++) {
						$("#remaining").val(arr[i]['num']);
					}
				}
			}
		});
		var uid =  document.getElementById('uid').value;
		$.ajax({
			url: '../lib/frommoreajax.php',
			data: {
				action: 'ajaxGetAccLimit',
				value1: value,
				value2: uid
			},
			type: 'post',
			success: function (response) {
				if (response != '' && !isNaN(parseFloat(response))) {
					$("#accumulation").val(response);
				}
			}
		});
	}
});