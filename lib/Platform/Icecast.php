<?php
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

	public function send($artist, $title, $type, $coverUrl) {
		if(is_array($this->mount)) {
			foreach($this->mount as $mnt) {
				$this->sendToMount($artist, $title, $mnt);
			}
		} else if(is_string($this->mount)) {
			$this->sendToMount($artist, $title, $this->mount);
		}
	}

	private function sendToMount($artist, $title, $mount) {
		$url = "http://".$this->host.":".$this->port."/admin/metadata?mode=updinfo";
		$url .= "&mount=".$mount;		
		$url .= "&song=".rawurlencode(utf8_decode($artist." - ".$title));

		SimpleHTTP::get($url, array(
			"username" => $this->username, 
			"password" => $this->password
		));
	}
}
?>
