$("#change").on("change", function() {
	$.ajax({
		url: '../lib/fromajaxcall.php',
		data: {
			action: 'ajaxChangeLang',
			value: lang
		},
		type: 'post',
		success: function (response) {
			lang = response;
			location.reload();
		}
	});
});