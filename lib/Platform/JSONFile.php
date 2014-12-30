<?php
class Platform_JSONFile implements PlatformInterface {
	private $filename;

	public function __construct(array $config) {
		$this->filename = $config['filename'];
	}

	public function send(Metadata $meta) {
		$data = array();

		$data['artist'] = $meta->Artist;
		$data['title'] = $meta->Title;
		$data['type'] = $meta->Type;
		$data['cover'] = $meta->CoverArt;
		$data['start_time'] = time();

		file_put_contents($this->filename, json_encode($data));
	}
}
?>
