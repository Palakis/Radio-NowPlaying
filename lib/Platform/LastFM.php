<?php
class Platform_LastFM implements PlatformInterface {
	private $apiKey;
	private $apiSecret;
	private $username;
	private $password;
	
	private $apiRoot = "https://ws.audioscrobbler.com/2.0/";

	public function __construct(array $config) {
		$this->apiKey = $config['apiKey'];
		$this->apiSecret = $config['apiSecret'];
		$this->username = $config['username'];
		$this->password = $config['password'];
	}

	public function send($artist, $title, $type, $coverUrl) {
		if($type != "Music") {
			return;
		}

		$this->callMethod("track.updateNowPlaying", array(
			"artist" => $artist,
			"track" => $title
		));
		$this->callMethod("track.scrobble", array(
			"artist[0]" => $artist,
			"track[0]" => $title,
			"timestamp[0]" => time()
		));
	}

	private function callMethod($method, array $opts) {
		$opts['sk'] = $this->getSession();
		return $this->apiCall($method, $opts);
	}

	private function getSession() {
		$result = $this->apiCall("auth.getMobileSession", array(
			"username" => $this->username,
			"password" => $this->password
		));
		return $result['session']['key'];
	}

	private function apiCall($method, array $options) {
		// Options initiales
		$options["format"] = "json";
		$options["method"] = $method;
		$options["api_key"] = $this->apiKey;				
		
		// Signature de la requete
		$options['api_sig'] = $this->signRequest($options);
		
		// Appel API
		$result = SimpleHTTP::post($this->apiRoot, $options);
		return json_decode($result, true);
	}

	private function signRequest($options) {
		unset($options['format']);
		unset($options['callback']);

		// Tri des options par ordre alphabétique
		ksort($options);

		// Génération et ajout de la signature
		$sig = "";
		foreach($options as $key => $value) {
			$sig .= $key.$value;
		}
		
		return md5($sig.$this->apiSecret);
	}
}
?>
