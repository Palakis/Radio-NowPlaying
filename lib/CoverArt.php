<?php
class CoverArt {
	private $endpoint = "http://api.deezer.com/2.0/";
	private $defaultImg = null;

	public function __construct() {

	}

	public function setDefaultImg($url) {
		$this->defaultImage = $url;
	}

	private function prepareForSearch($text) {
		return preg_replace("/Feat.+/i", "", $text);
	}

        public function getCover($artist, $title) {
		$artist = $this->prepareForSearch($artist);
		$title = $this->prepareForSearch($title);

                $url = $this->endpoint;

                $query = urlencode($artist." ".$title);

                $get = curl_init();
                curl_setopt($get, CURLOPT_URL, $url."search?q=".$query);
                curl_setopt($get, CURLOPT_HEADER, FALSE);
                curl_setopt($get, CURLOPT_RETURNTRANSFER, TRUE);

                $result = curl_exec($get);
                curl_close($get);

                $json = json_decode($result);
                if($json->total > 0) {
                        return $json->data[0]->album->cover."?size=medium";
                } else {
                        return $this->defaultImg;
                }
        }
}
?>
