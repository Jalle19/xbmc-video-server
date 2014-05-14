$('body').on('click', '.close-video-player-button', function() {
	// reload the page when the video player is closed. This causes the 
	// transcoders to stop
	// TODO: Do this in a smarter fashion (through a URL or something)
	location.reload();
});

