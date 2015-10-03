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
 */
function waitForLibraryUpdate() {
	var url = createUrl('backend/waitForLibraryUpdate');

	$.ajax({
		url: url,
		success: function () {
			window.location.href = createUrl('');
		}
	});
}

function startPolling() {
	var pollUrl = createUrl('backend/ajaxCheckConnectivity');

	// Start polling the server for updates
	setInterval(function() {
		$.ajax({
			url: pollUrl,
			dataType: "json",
			success: function(data) {
				// Redirect to "homeUrl" if the response is good
				if (data.status)
					window.location.href = createUrl('');
			},
			timeout: 30000
		});
	}, 2500);
}
