<?php
class CoverArt_LastFM implements CoverArtInterface {
	private $lastfm;
	private $config;

	public function __construct() {
		$this->config = $config['platforms']['LastFM'];
	}

	public function getCover($artist, $title) {

	}
}
?>