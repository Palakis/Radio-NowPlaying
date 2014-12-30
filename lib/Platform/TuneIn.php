<?php
class Platform_TuneIn implements PlatformInterface {
	private $partnerId;
	private $partnerKey;
	private $stationId;

	public function __construct(array $config) {
		$this->partnerId = $config['partnerId'];
		$this->partnerKey = $config['partnerKey'];
		$this->stationId = $config['stationId'];
	}

	public function send($artist, $title, $type, $coverUrl) {
		$url = "http://air.radiotime.com/Playing.ashx";
		$url .= "?partnerId=".$this->partnerId;
		$url .= "&partnerKey=".$this->partnerKey;
		$url .= "&id=".$this->stationId;
		$url .= "&title=".$title;
		$url .= "&artist=".$artist;
		
		SimpleHTTP::get($url);
	}
}
?>
