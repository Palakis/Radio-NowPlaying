$.fn.onairplaylist = function(url, interval) {
	var timestamp = new Date().getTime();		
	$.getJSON(url+"?req="+timestamp, null, $.proxy(function(data) {
		data = data.reverse();
		$("tr", this).remove();
		$.each(data, $.proxy(function(i, item) {
			console.log(item);
			var time = new Date();
			time.setTime(item.start_time * 1000);

			var readableTime = ("0" + time.getHours()).slice(-2) + ":" + ("0" + time.getMinutes()).slice(-2);
			var preciseReadableTime = readableTime + ":" + ("0" + time.getSeconds()).slice(-2);

			var track = $('<tr>', {class: "track"});
			if(item.type == "Show") {
				track.css('background-color', 'orange');
			}

			var trackTime = $('<td>', {class: "track-time"});
			trackTime.text(readableTime);
			track.append(trackTime);

			if(item.type == "Show") {
				var showPlaceholder = $('<td>', {class: "track-show-placeholder", colspan: 3});
				showPlaceholder.text("Emission");
				track.append(showPlaceholder);
			} else {
				if(item.cover == null) {
					item.cover = "http://vps.rjrradio.fr/drupal/sites/all/themes/rjr2015/img/nocd.png"
				}
				var trackCoverImg = $('<img>', {src: item.cover});

				var trackCover = $('<td>', {class: "track-cover"});
				trackCover.append(trackCoverImg);
				track.append(trackCover);

				var trackInfo = $('<td>', {class: "track-info"});

				var trackInfoArtist = $('<div>', {class: "track-artist"});
				trackInfoArtist.text(item.artist);

				var trackInfoTitle = $('<div>', {class: "track-title"});
				trackInfoTitle.text(item.title);

				trackInfo.append(trackInfoArtist);
				trackInfo.append(trackInfoTitle);
				track.append(trackInfo);

				var trackPreview = $('<td>', {class: "track-preview"});
				if(item.preview != null) {
					var trackPreviewPlayer = $('<audio>', {src: item.preview, controls: "true"});
					trackPreview.append(trackPreviewPlayer);
				}
				track.append(trackPreview);
			}
			$(this).append(track);

		}, this));
	}, this));
};
