<?php
class LastFmAPI {
	private $apiKey;
	private $apiSecret;
	private $session;
	
	private $apiRoot = "https://ws.audioscrobbler.com/2.0/";

	public function __construct($apiKey, $apiSecret) {
		$this->apiKey = $apiKey;
		$this->apiSecret = $apiSecret;
	}

	public function login($username, $password) {
		$sk = $this->getSession($username, $password);
		if($sk != null) {
			$this->session = $sk;
		} else {
			throw new Exception('LastFM login failure');
		}
	}

	public function callMethod($method, array $opts) {
		$opts['sk'] = $this->session;
		return $this->apiCall($method, $opts);
	}

	private function getSession($username, $password) {
		$result = $this->apiCall("auth.getMobileSession", array(
			"username" => $username,
			"password" => $password
		));
		if(isset($result['session']['key'])){
			return $result['session']['key'];
		} else {
			return null;
		}
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
