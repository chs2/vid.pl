<?php
namespace Entity;

class Playlist {
	public $id;
	public $title;
	public $videos;

	public function __construct(array $properties) {
		foreach ($properties as $name => $value) {
			$this -> $name = $value;
		}
	}
}
