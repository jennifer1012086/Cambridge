$('.option').attr('style', 'display: none');
$('#append-condition').on('change', function() {
	$('.option').attr('style', 'display: none');
	if($(this).val() == 1) {
		$('.option-1').attr('style', '');
	} else if($(this).val() == 2) {
		$('.option-2').attr('style', '');
	} else if($(this).val() == 3) {
		$('.option-3').attr('style', '');
	}
});
var sub = {'zh-tw': '次分類', 'en': 'Sub Category'};
$("#main").on("change", function() {
	var value = $(this).prop('value');
	$("#sub").empty();
	// todo lang
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