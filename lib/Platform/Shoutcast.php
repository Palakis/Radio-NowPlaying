<?php
class Platform_Shoutcast implements PlatformInterface {
	private $host;
	private $port;
	private $password;

	public function __construct($host, $port, $password) {
		$this->host = $host;
		$this->port = $port;
		$this->password = $password;
	}

	public function __construct() {
		$this->host = $config['host'];
		$this->port = $config['port'];
		$this->password = $config['password'];
	}

	public function send($artist, $title, $type, $coverUrl) {
		$url = "http://".$this->host.":".$this->port."/admin.cgi?mode=updinfo";
		$url .= "&pass=".$this->password;
		$url .= "&song=".rawurlencode(utf8_decode($artist." - ".$title));
		
		SimpleHTTP::get($url);
	}
}
?>
