<?php
class SimpleHTTP {
	public static function get($url, $auth = null, $useragent = "NowPlaying(Mozilla Compatible)") {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
		if($auth != null && is_array($auth)) {
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_USERPWD, $auth["username"].":".$auth["password"]);
		}

		$response = curl_exec($curl);
		curl_close($curl);
		return $response;		
	}

	public static function post($url, array $data, $auth = null, $useragent = "NowPlaying(Mozilla Compatible)") {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
		if($auth != null && is_array($auth)) {
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_USERPWD, $auth["username"].":".$auth["password"]);
		}

		$datastring = "";
		foreach($data as $key => $value) {
			$value = urlencode($value);
			$datastring .= $key.'='.$value.'&';
		}
		rtrim($datastring, '&');

		curl_setopt($curl, CURLOPT_POST, count($data));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $datastring);

		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
}
?>
