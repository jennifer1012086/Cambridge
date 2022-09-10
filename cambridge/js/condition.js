$('.table').attr('style', 'display: none');

$('#condition').on('change', function() {
	if ($(this).val() == 1) {
		$('.vd-option').attr('style', 'display: none');
		$('.table').attr('style', 'display: none');
		$('.teacher').attr('style', '');
	} else if($(this).val() == 2) {
		$('.vd-option').attr('style', 'display: none');
		$('.table').attr('style', 'display: none');
		$('.class').attr('style', '');
	} else if($(this).val() == 3) {
		$('.vd-option').attr('style', 'display: none');
		$('.table').attr('style', 'display: none');
		$('.activity').attr('style', '');
	}
});
$('#condition-content').on('change', function() {
	let condition = $('#condition').val();
	let value = $(this).val();
	if (condition == 1) {
		$("#teacher-body").empty();
		$.ajax({
			url: '../lib/fromajaxcall.php',
			data: {
				action: 'ajaxGetApplyItemByTeacher',
				value: value
			},
			type: 'post',
			success: function (response) {
				if (response != '') {
					var arr = JSON.parse(response);
					for (var i = 0; i < arr.length; i++) {
						var parent = $(arr[i]['parent']).attr(lang);
						var sub = $(arr[i]['name']).attr(lang);
						var item = $(arr[i]['item']).attr(lang);
						$("#teacher-body").append("<tr><td data-title='"+mc+"'>"+parent+"</td><td data-title='"+sc+"'>"+sub+"</td><td data-title='"+iname+"'>"+item+"</td><td data-title='"+aq+"'>"+arr[i]['total']+"</td><td data-title='"+remain+"'>"+arr[i]['limit']+"</td></tr>");
					}
				}
			}
		});
	} else if(condition == 2) {
		$("#ac-body").empty();
		$.ajax({
			url: '../lib/fromajaxcall.php',
			data: {
				action: 'ajaxGetApplyItemByClass',
				value: value
			},
			type: 'post',
			success: function (response) {
				if (response != '') {
					var arr = JSON.parse(response);
					for (var i = 0; i < arr.length; i++) {
						var parent = $(arr[i]['parent']).attr(lang);
						var sub = $(arr[i]['name']).attr(lang);
						var item = $(arr[i]['item']).attr(lang);
						$("#ac-body").append("<tr><td data-title='"+mc+"'>"+parent+"</td><td data-title='"+sc+"'>"+sub+"</td><td data-title='"+iname+"'>"+item+"</td><td data-title='"+aq+"'>"+arr[i]['total']+"</td></tr>");
					}
				}
			}
		});
	} else if(condition == 3) {
		$("#ac-body").empty();
		$.ajax({
			url: '../lib/fromajaxcall.php',
			data: {
				action: 'ajaxGetApplyItemByActivity',
				value: value
			},
			type: 'post',
			success: function (response) {
				if (response != '') {
					var arr = JSON.parse(response);
					for (var i = 0; i < arr.length; i++) {
						var parent = $(arr[i]['parent']).attr(lang);
						var sub = $(arr[i]['name']).attr(lang);
						var item = $(arr[i]['item']).attr(lang);
						$("#ac-body").append("<tr><td data-title='"+mc+"'>"+parent+"</td><td data-title='"+sc+"'>"+sub+"</td><td data-title='"+iname+"'>"+item+"</td><td data-title='"+aq+"'>"+arr[i]['total']+"</td></tr>");
					}
				}
			}
		});
	}
});