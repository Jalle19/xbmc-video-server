$('.episode-toggle').click(function() {
	var contentUrl = $(this).data().contentUrl;
	var contentContainer = $('#' + $(this).data().contentId);
	
	$.ajax({
		url: contentUrl,
		success: function(data) {
			contentContainer.html(data);
			
			// trigger unveiling of thumbnails
			$(".lazy").unveil(50);
		}
	});
	
});