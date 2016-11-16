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

class Platform_JSONFile implements PlatformInterface {
	private $filename;

	public function __construct(array $config) {
		$this->filename = $config['filename'];
	}

	public function send(Metadata $meta) {
		$data = array();

		$data['artist'] = $meta->Artist;
		$data['title'] = $meta->Title;
		$data['type'] = $meta->Type;
		$data['cover'] = $meta->CoverArt;
		$data['preview'] = $meta->Preview;
		$data['start_time'] = time();

		file_put_contents($this->filename, json_encode($data));
	}
}

?>
