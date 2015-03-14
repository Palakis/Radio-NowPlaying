<?php
class DataProvider_Deezer implements DataProviderInterface {
	protected $endpoint = "http://api.deezer.com/2.0/";
        protected $artist;
        protected $title;

	public function __construct($artist, $title) {
                $this->artist = $artist;
                $this->title = $title;
	}

	protected function prepareForSearch($text) {
		return preg_replace("/Feat.+/i", "", $text);
	}

        public function getCover() {
		$artist = $this->prepareForSearch($this->artist);
		$title = $this->prepareForSearch($this->title);

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
                        throw new Exception('No cover art found');
                }
        }

        public function getPreview() {
                throw new Exception('Feature not implemented');
        }
}
?>
