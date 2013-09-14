$('.loggable-link').click(function() {
	var data = $(this).data();
	var url = data.logUrl;
	
	$.post(url, data);
});