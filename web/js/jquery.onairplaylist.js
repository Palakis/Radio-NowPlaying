$.fn.onairplaylist = function(url, interval) {
	setInterval($.proxy(function() {
		var timestamp = new Date().getTime();		
		$.getJSON(url+"?req="+timestamp, null, $.proxy(function(data) {
			data = data.reverse();
			$("tr:gt(0)", this).remove();
			$.each(data, $.proxy(function(i, item) {
				var time = new Date();
				time.setTime(item.start_time * 1000);
				
				var shownTitle = item.artist;
				if(item.type == "Music") {
					shownTitle += " - " + item.title;
				}				

				var readableTime = ("0" + time.getHours()).slice(-2) + ":" + ("0" + time.getMinutes()).slice(-2);
				var preciseReadableTime = readableTime + ":" + ("0" + time.getSeconds()).slice(-2);
				$(this).append("<tr> <td>" + readableTime + "</td> <td>" + shownTitle + "</td> </tr>");
			}, this));
		}, this));
	}, this), interval);
};
