<?php
class OnAir {
	private $config;

	private $platforms;
	private $coverProvider;
	private $meta;

	public function __construct() {
		if($_REQUEST['type'] == 'Music' && !isset($_REQUEST['title'])) {
			throw new Exception("Missing parameters for type Music");
		}

		if(!isset($_REQUEST['artist'])
		|| !isset($_REQUEST['type'])
		|| !isset($_REQUEST['duration'])) {
			throw new Exception("Missing parameters");
		}
			
		$this->allowedHosts = array();
		$this->platforms = array();
		$this->textFilters = array();

		$this->meta = new Metadata();
		$this->meta->Artist = $this->filter($_REQUEST['artist']);
		$this->meta->Type = $this->filter($_REQUEST['type']);

		$this->meta->Oneliner = $this->meta->Artist;
		if(isset($_REQUEST['title'])) {
			$this->meta->Title = $this->filter($_REQUEST['title']);
			$this->meta->Oneliner .= " - ".$this->meta->Title;
		}

		$this->meta->Duration = intval($this->filter($_REQUEST['duration']));
	}	

	public function loadConfig(array $config) {
		$this->config = $config;

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
			die();
		}
	}

	public function addPlatform(PlatformInterface $platform) {
		$this->platforms[] = $platform;
	}

	private function filter($value) {
		return stripslashes(html_entity_decode(utf8_encode($value), ENT_QUOTES));
	}

	public function run() {
		if(isset($this->config['allowedHosts']) 
		&& is_array($this->config['allowedHosts'])) {
			$allow = false;
			foreach($this->config['allowedHosts'] as $host) {
				if(gethostbyname($host) == $_SERVER['REMOTE_ADDR']) {
					$allow = true;
				}
			}

			if(!$allow) {
				throw new Exception("Unknown host ".$_SERVER['REMOTE_ADDR']);
			}
		}
		
		if(isset($this->config['minDuration'])
		&& $this->config['minDuration'] > 0
		&& ($this->meta->Duration < $this->config['minDuration'])) {
			throw new Exception("Song too short - duration less than ".$this->config['minDuration']." seconds");
		}

		foreach($this->config['textFilters'] as $filter) {
			$this->meta->Artist = preg_replace("/".$filter."/i", "", $this->meta->Artist);
			$this->meta->Title = preg_replace("/".$filter."/i", "", $this->meta->Title);
		}

		$this->meta->CoverArt = $this->coverProvider->getCover($this->meta->Artist, $this->meta->Title);

		foreach($this->platforms as $platform) {
			$platform->send($this->meta);
		}

		echo "OK";
	}

	public static function Log($message, $filename = "errors.log") {
		file_put_contents($filename, "[".date("d/m/Y h:i:s")."] ".$message."\n", FILE_APPEND);
		echo $message."\n";
	}
}
?>
