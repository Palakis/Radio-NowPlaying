$.fn.onair = function(url, interval) {
	var errorMessage = "Impossible de récupérer les informations"
	setInterval($.proxy(function() {
		var timestamp = new Date().getTime();
		$.getJSON(url+"?req="+timestamp, null, $.proxy(function(data) {
			if(data.type == "Music") {
				$(this).text(data.artist + " - " + data.title);
			}
			else if(data.type == "Show") {
				$(this).text(data.artist);
			}
			else {
				console.log(data);
			}
		}, this), $.proxy(function(error) {
			$(this).text(errorMessage);
		}, this));
	}, this), interval);
};
