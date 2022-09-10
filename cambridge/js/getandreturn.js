$('#get').on('click', function() {
	var box = $('.ck-box');
	for (var i = 0; i < box.length; i++) {
		if (box[i].checked) {
			var value = box[i].id;
			var act = value.split('-')[0];
			var ioid = value.split('-')[1];
			if (act == 'rev') {
				$.ajax({
					url: '../lib/fromajaxcall.php',
					data: {
						action: 'ajaxPass',
						value: ioid
					},
					type: 'post',
					success: function(response) {
						location.reload();
					}
				});
			} else if(act == 'get') {
				if ($('#rev-'+ioid).prop('checked')) {
					$.ajax({
						url: '../lib/fromajaxcall.php',
						data: {
							action: 'ajaxGet',
							value: ioid
						},
						type: 'post',
						success: function(response) {
							location.reload();
						}
					});
				} else {
					alert('審核後才能領取!');
					location.reload();
				}
			} else if(act == 'can') {
				if (!$('#rev-'+ioid).prop('checked')) {
					$.ajax({
						url: '../lib/fromajaxcall.php',
						data: {
							action: 'ajaxCancel',
							value: ioid
						},
						type: 'post',
						success: function(response) {
							location.reload();
						}
					});
				} else {
					alert('無法取消審核通過申請!');
					location.reload();
				}
			} 
		}
	}
});
$('#return').on('click', function() {
	var box = $('.ck-box');
	for (var i = 0; i < box.length; i++) {
		if (box[i].checked) {
			var value = box[i].id;
			value = value.split('-')[1];
			$.ajax({
				url: '../lib/fromajaxcall.php',
				data: {
					action: 'ajaxReturn',
					value: value
				},
				type: 'post',
				success: function(response) {
					location.reload();
				}
			});
		}
	}
});