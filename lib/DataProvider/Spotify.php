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

	public function __construct($artist, $title) {
  	$this->track = $this->getTrack($artist, $title);
	}

  public function getCover() {
  	if($this->track != null) {
      return $this->track['album']['images'][1]['url'];
    } else {
      throw new Exception('No cover art found');
    }
  }

  public function getPreview() {
    if($this->track != null) {
    	return $this->track['preview_url'];
    } else {
  		throw new Exception('No preview found');
    }
  }

  protected function getTrack($artist, $title) {
    $url = $this->endpoint;

    $query = urlencode("artist:\"".$artist."\" ".$title);

    $data = SimpleHTTP::get($this->endpoint."/v1/search?type=track&q=".$query);
    $data = json_decode($data, true);

    return $data['tracks']['items'][0];
  }
}

?>
