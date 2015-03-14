<?php
interface DataProviderInterface {
	public function __construct($artist, $title);
	public function getCover();
	public function getPreview();
}
?>