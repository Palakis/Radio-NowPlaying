<?php
class Platform_LastFM implements PlatformInterface {
	private $lastfm;
	
	private $apiRoot = "https://ws.audioscrobbler.com/2.0/";

	public function __construct(array $config) {
		$this->lastfm = new LastFmAPI($config['apiKey'], $config['apiSecret']);
		$this->lastfm->login($config['username'], $config['password']);
	}

	public function send($artist, $title, $type, $coverUrl) {
		if($type != "Music") {
			return;
		}

		$this->lastfm->callMethod("track.updateNowPlaying", array(
			"artist" => $artist,
			"track" => $title
		));
		$this->lastfm->callMethod("track.scrobble", array(
			"artist[0]" => $artist,
			"track[0]" => $title,
			"timestamp[0]" => time()
		));
	}
}
?>
