<?php
class Platform_JSONPlaylist implements PlatformInterface {
	private $filename;
	private $maxKeepTime;

	public function __construct(array $config) {
		$this->filename = $config['filename'];
		$this->maxKeepTime = $config['maxKeepTime'];
	}

	public function send($artist, $title, $type, $coverUrl) {
		if(!file_exists($this->filename)) {
			file_put_contents($this->filename, json_encode(array()));
		}
		
		$oldData = json_decode(file_get_contents($this->filename), true);
		$newData = array();
		
		foreach($oldData as $value) {
			$start_time = intval($value['start_time']);
			$current_time = time();
			$time_diff = $current_time - $start_time;
			
			if($time_diff < $this->maxKeepTime) {
				$newData[] = $value;
			}
		}	

		$newData[] = array(
			'artist' => $artist, 
			'title' => $title, 
			'type' => $type, 
			'cover' => $coverUrl,
			'start_time' => time()
		);

		file_put_contents($this->filename, json_encode($newData));
	}
}
?>
