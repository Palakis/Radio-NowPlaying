<?php
class OnAir {
	private $allowedHosts;
	private $platforms;
	private $textFilters;
	
	private $title;
	private $artist;
	private $type;
	private $coverUrl;
	private $duration;

	private $hostCheck;
	private $durationCheck;
	private $minDuration;

	private $coverProvider;

	public function __construct() {
		if(!isset($_REQUEST['artist'])
		|| !isset($_REQUEST['title'])
		|| !isset($_REQUEST['type'])
		|| !isset($_REQUEST['duration'])) {
			throw new Exception("Missing parameters");
		}
			
		$this->allowedHosts = array();
		$this->platforms = array();
		$this->textFilters = array();

		$this->hostCheck = true;
		$this->durationCheck = true;
		$this->minDuration = 30;

		$this->artist = $this->filter($_REQUEST['artist']);
		$this->title = $this->filter($_REQUEST['title']);
		$this->type = $this->filter($_REQUEST['type']);
		$this->duration = intval($this->filter($_REQUEST['duration']));
	}	

	public function loadConfig(array $config) {
		$this->textFilters = $config['textFilters'];
		$this->allowedHosts = $config['allowedHosts'];

		try {
			$class = 'CoverArt_'.$config['coverArtProvider'];
			$this->coverProvider = new $class();

			foreach($config['platforms'] as $key => $value) {
				$class = 'Platform_'.$key;
				$this->addPlatform(new $class($value));
			}
		}
		catch(Exception $ex) {
			$this::Log($ex->getMessage());
		}
	}

	public function addPlatform(PlatformInterface $platform) {
		$this->platforms[] = $platform;
	}

	public function disableHostCheck() {
		$this->hostCheck = false;
	}

	public function disableDurationCheck() {
		$this->durationCheck = false;
	}

	private function filter($value) {
		return stripslashes(html_entity_decode(utf8_encode($value), ENT_QUOTES));
	}

	public function run() {
		if($this->hostCheck) {
			$allow = false;
			foreach($this->allowedHosts as $host) {
				if(gethostbyname($host) == $_SERVER['REMOTE_ADDR']) {
					$allow = true;
				}
			}

			if(!$allow) {
				throw new Exception("Unknown host ".$_SERVER['REMOTE_ADDR']);
			}
		}
		

		if($this->durationCheck && ($this->duration < $this->minDuration)) {
			throw new Exception("Song too short - duration less than 30 seconds");
		}

		foreach($this->textFilters as $filter) {
			$this->artist = preg_replace("/".$filter."/i", "", $this->artist);
			$this->title = preg_replace("/".$filter."/i", "", $this->title);
		}

		$this->coverUrl = $this->coverProvider->getCover($this->artist, $this->title);

		foreach($this->platforms as $platform) {
			$platform->send($this->artist, $this->title, $this->type, $this->coverUrl);
		}

		echo "OK";
	}

	public static function Log($message) {
		file_put_contents("errors.log", "[".date("d/m/Y h:i:s")."] ".$message."\n", FILE_APPEND);
		die("Error : ".$message);
	}
}
?>
