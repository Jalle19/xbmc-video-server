/**
 * Creates a relative URL to the specified route (very crude)
 * @param {string} route
 * @returns {string}
 */
function createUrl(route) {
	var path = window.location.pathname.split('/');
	path.pop();
	path[path.length - 1] = route;

	return path.join('/');
}

/**
 * Requests a library update and redirects to "homeUrl" when the request 
 * finishes
 * @param {string} previousLocation the location to redirect to once the 
 * request finishes
 */
function waitForLibraryUpdate(previousLocation) {
	var url = createUrl('backend/waitForLibraryUpdate');

	$.ajax({
		url: url,
		success: function () {
			window.location.href = previousLocation;
		}
	});
}

/**
 * Polls for a response and redirects to "homeUrl" once a response is receieved, 
 * meaning the backend has woken up. It gives up after 30 seconds.
 */
function pollForConnectivity() {
	var pollUrl = createUrl('backend/ajaxCheckConnectivity');

	// Start polling the server for updates
	setInterval(function() {
		$.ajax({
			url: pollUrl,
			dataType: "json",
			success: function(data) {
				if (data.status)
					window.location.href = createUrl('');
			},
			timeout: 30000
		});
	}, 2500);
}
