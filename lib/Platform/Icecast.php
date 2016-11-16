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

class Platform_Icecast implements PlatformInterface {
	private $host;
	private $port;
	private $username;
	private $password;
	private $mount;

	public function __construct(array $config) {
		$this->host = $config['host'];
		$this->port = $config['port'];
		$this->username = $config['username'];
		$this->password = $config['password'];
		$this->mount = $config['mount'];
	}

	public function send(Metadata $meta) {
		if(is_array($this->mount)) {
			foreach($this->mount as $mnt) {
				$this->sendToMount($meta->Oneliner, $mnt);
			}
		} else if(is_string($this->mount)) {
			$this->sendToMount($meta->Oneliner, $this->mount);
		}
	}

	private function sendToMount($oneliner, $mount) {
		$url = "http://".$this->host.":".$this->port."/admin/metadata?mode=updinfo";
		$url .= "&mount=".$mount;		
		$url .= "&song=".rawurlencode(utf8_decode($oneliner));

		SimpleHTTP::get($url, array(
			"username" => $this->username, 
			"password" => $this->password
		));
	}
}

?>
