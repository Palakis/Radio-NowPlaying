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

class Platform_Shoutcast implements PlatformInterface {
	private $host;
	private $port;
	private $password;

	public function __construct(array $config) {
		$this->host = $config['host'];
		$this->port = $config['port'];
		$this->password = $config['password'];
	}

	public function send(Metadata $meta) {
		$url = "http://".$this->host.":".$this->port."/admin.cgi?mode=updinfo";
		$url .= "&pass=".$this->password;
		$url .= "&song=".rawurlencode(utf8_decode($meta->Oneliner));
		
		try {
			SimpleHTTP::get($url);
		}
		catch(Exception $ex) {
			if(strstr($ex->getMessage(), "Empty reply from server") == false) {
				throw $ex;
			}
		}		
	}
}

?>
