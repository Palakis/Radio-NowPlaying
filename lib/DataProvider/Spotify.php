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

class DataProvider_Spotify implements DataProviderInterface {
	protected $endpoint = "https://api.spotify.com";
	protected $track;
	protected $config;

	public function __construct($artist, $title, $config) {
		$this->config = $config;

		if($this->config == null 
			|| $this->config['client_id'] == null
			|| $this->config['client_secret'] == null)
		{
			throw new Exception("incorrect Spotify configuration");
		}

		$this->track = $this->getTrack($artist, $title);
	}

	public function getCover() {
		if(!$this->track) {
			throw new Exception('No cover art found');
		}
		return $this->track['album']['images'][1]['url'];
	}

	public function getPreview() {
		if (!$this->track) {
			throw new Exception('No preview found');
		}
		return $this->track['preview_url'];
	}

	protected function getTrack($artist, $title) {
		$query = urlencode("artist:\"".$artist."\" ".$title);

		$access_token = $this->authenticate(
			$this->config['client_id'], 
			$this->config['client_secret']);

		$data = SimpleHTTP::get(
			$this->endpoint."/v1/search",
			[
				"type" => "track",
				"q" => $query
			],
			null,
			[ 'Authorization: Bearer '.$access_token ]
		);

		$data = json_decode($data, true);

		return $data['tracks']['items'][0];
	}

	protected function authenticate($client_id, $client_secret) {
		$resp = SimpleHTTP::post(
			"https://accounts.spotify.com/api/token",
			null,
			array('grant_type' => 'client_credentials'),
			array('username' => $client_id, 'password' => $client_secret)
		);

		$resp = json_decode($resp, true);

		if (!$resp['access_token']) {
			throw new Exception('Spotify auth failed');
		}

		return $resp['access_token'];      
	}
}

?>
