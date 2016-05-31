<?php
class OnAir {
	private $config; // Self-explanatory

	private $platforms; // Metadata destinations
	private $dataProvider; // External track data provider
	private $meta; // Metadata to send

	private $allowedHosts; // Hosts allowed to send metadata
	private $textFilters; // Regexes applied to text values

	public function __construct(array $config) {
		// Load config in this object
		$this->config = $config;

		// POST check
		if(isset($this->config['forcePost'])
		   && $this->config['forcePost']
		   && $_SERVER['REQUEST_METHOD'] != "POST") {
			throw new Exception('Unsupported method');
		}

		// "title" attribute presence check for type "Music"
		if($_REQUEST['type'] == 'Music' && !isset($_REQUEST['title'])) {
			throw new Exception("Missing parameters for type Music");
		}

		// Presence check for common parameters
		if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'Music') {
			if(!isset($_REQUEST['artist'])
			|| !isset($_REQUEST['type'])
			|| !isset($_REQUEST['duration'])) {
				throw new Exception("Missing parameters");
			}
		}

		// Check if the caller's hostname is allowed
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

		// Check if song has min duration
		if(isset($this->config['minDuration'])
		&& $_REQUEST['type'] == 'Music'
		&& $this->config['minDuration'] > 0
		&& (intval($_REQUEST['duration']) < $this->config['minDuration'])) {
			throw new Exception("Song too short - duration less than ".$this->config['minDuration']." seconds");
		}

		// Instanciate platform classes
		$this->loadPlatforms($this->config);
	}

	public function run() {
		// Init metadata values
		$this->meta = new Metadata();
		$this->meta->Artist = $this->filter($_REQUEST['artist']);
		$this->meta->Type = $this->filter($_REQUEST['type']);
		$this->meta->Duration = intval($this->filter($_REQUEST['duration']));

		// Generate a oneliner
		$this->meta->Oneliner = $this->meta->Artist;
		if(isset($_REQUEST['title'])) {
			$this->meta->Title = $this->filter($_REQUEST['title']);
			$this->meta->Oneliner .= " - ".$this->meta->Title;
		}

		// -- BEGIN PREPROCESSING --
		foreach($this->config['textFilters'] as $filter) {
			$this->meta->Artist = preg_replace("/".$filter."/i", "", $this->meta->Artist);
			$this->meta->Title = preg_replace("/".$filter."/i", "", $this->meta->Title);
		}
		// -- END PREPROCESSING --

		$this->loadDataProvider($this->meta->Artist, $this->meta->Title);

		// Processing additional metadata : cover art & audio preview
		$this->meta->CoverArt = null;
		$this->meta->Preview = null;
		if($this->meta->Type == 'Music') {
			// Cover art
			if(isset($_REQUEST['coverart']) && !empty($_REQUEST['coverart'])) {
				$data = $_REQUEST['coverart'];

				$imgdata = base64_decode($data);
				$f = finfo_open();
				$mime = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
				finfo_close($f);

				// TODO : base64 validation
				$this->meta->CoverArt = "data:".$mime.";base64,".$data;
			}
			elseif(isset($this->config['coverart_fallback'])
				&& $this->config['coverart'] == true) {
				try {
					$this->meta->CoverArt = $this->dataProvider->getCover();
				}
					catch(Exception $ex) {}
				}
			}

			// Audio preview
			try {
				$this->meta->Preview = $this->dataProvider->getPreview();
			}
			catch(Exception $ex) {}
		}

		foreach($this->platforms as $platform) {
			$platform->send($this->meta);
		}

		echo "OK";
	}

	public function loadPlatforms($config) {
		$this->platforms = array();
		try {
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

	public function loadDataProvider($artist, $title) {
		$class = 'DataProvider_'.$this->config['dataProvider'];
		$this->dataProvider = new $class($artist, $title);
	}

	public function addPlatform(PlatformInterface $platform) {
		$this->platforms[] = $platform;
	}

	private function filter($value) {
		return stripslashes(html_entity_decode(utf8_encode($value), ENT_QUOTES));
	}

	public static function Log($message, $filename = "errors.log") {
		file_put_contents($filename, "[".date("d/m/Y h:i:s")."] ".$message."\n", FILE_APPEND);
		echo $message."\n";
	}
}
?>
