<?php
class Platform_JSONFile implements PlatformInterface {
	private $filename;

	public function __construct(array $config) {
		$this->filename = $config['filename'];
	}

	public function send($artist, $title, $type, $coverUrl) {
		$data = array();

		$data['artist'] = $artist;
		$data['title'] = $title;
		$data['type'] = $type;
		$data['cover'] = $coverUrl;
		$data['start_time'] = time();

		file_put_contents($this->filename, json_encode($data));
	}
}
?>
