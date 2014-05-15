// keep track of which season containers we have populated
var populatedSeasons = [];

$('.episode-toggle').click(function() {
	populateSeason(this);
});

function populateSeason(season) {
	var contentUrl = $(season).data().contentUrl;
	var contentId = $(season).data().contentId;
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
}

function populateAll() {
	$('.episode-toggle').each(function() {
		populateSeason(this);
	});
}