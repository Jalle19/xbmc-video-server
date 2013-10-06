// keep track of which season containers we have populated
var populatedSeasons = [];

$('.episode-toggle').click(function() {
	var contentUrl = $(this).data().contentUrl;
	var contentId = $(this).data().contentId;
	var contentContainer = $('#' + contentId);
	
	// only populate once
	if ($.inArray(contentId, populatedSeasons) === -1) {
		$.ajax({
			url: contentUrl,
			success: function(data) {
				contentContainer.html(data);

				// trigger unveiling of thumbnails
				$(".lazy").unveil(50);
			}
		});
		
		populatedSeasons.push(contentId);
	}
	
});