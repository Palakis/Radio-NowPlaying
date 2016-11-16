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

class Platform_JSONPlaylist implements PlatformInterface {
	private $filename;
	private $maxKeepTime;

	public function __construct(array $config) {
		$this->filename = $config['filename'];
		$this->maxKeepTime = $config['maxKeepTime'];
	}

	public function send(Metadata $meta) {
		if(!file_exists($this->filename)) {
			file_put_contents($this->filename, json_encode(array()));
		}
		
		$oldData = json_decode(file_get_contents($this->filename), true);
		$newData = array();
		
		foreach($oldData as $value) {
			$start_time = intval($value['start_time']);
			$current_time = time();
			$time_diff = $current_time - $start_time;
			
			if($time_diff < $this->maxKeepTime) {
				$newData[] = $value;
			}
		}	

		$newData[] = array(
			'artist' => $meta->Artist, 
			'title' => $meta->Title, 
			'type' => $meta->Type, 
			'cover' => $meta->CoverArt,
			'preview' => $meta->Preview,
			'start_time' => time()
		);

		file_put_contents($this->filename, json_encode($newData));
	}
}

?>
