<?php
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
