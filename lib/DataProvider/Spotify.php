<?php
class DataProvider_Spotify implements DataProviderInterface {
	protected $endpoint = "https://api.spotify.com";
	protected $track;

	public function __construct($artist, $title) {
  	$this->track = $this->getTrack($artist, $title);
	}

  public function getCover() {
  	if($this->track != null) {
      return $this->track['album']['images'][1]['url'];
    } else {
      throw new Exception('No cover art found');
    }
  }

  public function getPreview() {
    if($this->track != null) {
    	return $this->track['preview_url'];
    } else {
  		throw new Exception('No preview found');
    }
  }

  protected function getTrack($artist, $title) {
    $url = $this->endpoint;

    $query = urlencode($artist." ".$title);

    $data = SimpleHTTP::get($this->endpoint."/v1/search?type=track&q=".$query);
    $data = json_decode($data, true);

    return $data['tracks']['items'][0];
  }
}
?>
