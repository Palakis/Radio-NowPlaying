<?php
interface PlatformInterface {
	public function __construct(array $config);
	public function send($artist, $title, $type, $coverUrl);
}
?>
