<?php
class Platform_LastFM implements PlatformInterface {
	private $lastfm;
	
	private $apiRoot = "https://ws.audioscrobbler.com/2.0/";

	public function __construct(array $config) {
		$this->lastfm = new LastFmAPI($config['apiKey'], $config['apiSecret']);
		$this->lastfm->login($config['username'], $config['password']);
	}

	public function send(Metadata $meta) {
		if($meta->Type != "Music") {
			return;
		}

		$this->lastfm->callMethod("track.updateNowPlaying", array(
			"artist" => $meta->Artist,
			"track" => $meta->Title
		));
		$this->lastfm->callMethod("track.scrobble", array(
			"artist[0]" => $meta->Artist,
			"track[0]" => $meta->Title,
			"timestamp[0]" => time()
		));
	}
}
?>
