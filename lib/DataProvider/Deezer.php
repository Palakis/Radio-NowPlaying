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

class DataProvider_Deezer implements DataProviderInterface {
	protected $endpoint = "http://api.deezer.com/2.0/";
  protected $artist;
  protected $title;

	public function __construct($artist, $title) {
    $this->artist = $artist;
    $this->title = $title;
	}

  public function getCover() {
		$artist = $this->artist;
		$title = $this->title;
		
    $query = urlencode($artist." ".$title);
		$data = SimpleHTTP::get($this->endpoint."search?q=".$query);

    $json = json_decode($data);
    if($json->total > 0) {
      return $json->data[0]->album->cover."?size=medium";
    } else {
    	throw new Exception('No cover art found');
    }
	}

  public function getPreview() {
    throw new Exception('Feature not implemented');
  }
}

?>
