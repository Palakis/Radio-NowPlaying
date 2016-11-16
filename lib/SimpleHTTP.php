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

class SimpleHTTP {
	public static function get($url, $auth = null, $useragent = "NowPlaying(Mozilla Compatible)") {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		if($auth != null && is_array($auth)) {
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_USERPWD, $auth["username"].":".$auth["password"]);
		}

		$response = curl_exec($curl);

		if(curl_errno($curl) != 0) {
			throw new Exception("CURL internal error - ".curl_error($curl));
		}

		$returnCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($returnCode != 200) {
			throw new Exception("HTTP error code ".$returnCode. " for URL '".$url."'");
		}
 
		curl_close($curl);
		return $response;		
	}

	public static function post($url, array $data, $auth = null, $useragent = "NowPlaying(Mozilla Compatible)") {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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

		if(curl_errno($curl) != 0) {
			throw new Exception("CURL error : ".curl_error($curl));
		}

		$returnCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($returnCode != 200) {
			throw new Exception("HTTP error code ".$returnCode. " for URL '".$url."'");
		}

		curl_close($curl);
		return $response;
	}
}

?>
