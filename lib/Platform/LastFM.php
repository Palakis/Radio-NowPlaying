<?php
/*
Radio-NowPlaying	
Copyright (C) 2016 Stéphane Lepin <stephane.lepin@gmail.com>

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 3.0 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
Lesser General Public License for more details.
	
You should have received a copy of the GNU Lesser General Public
License along with this library. If not, see <https://www.gnu.org/licenses/>
*/

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
