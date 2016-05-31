<?php
class DataProvider_Deezer implements DataProviderInterface {
	protected $endpoint = "http://api.deezer.com/2.0/";
  protected $artist;
  protected $title;

	public function __construct($artist, $title) {
    $this->artist = $artist;
    $this->title = $title;
	}

  public function getCover() {
		$artist = $this->artist;
		$title = $this->title;
		
    $query = urlencode($artist." ".$title);
		$data = SimpleHTTP::get($this->endpoint."search?q=".$query);

    $json = json_decode($data);
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
