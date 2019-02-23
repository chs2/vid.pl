<?php
namespace Entity;

abstract class Base {
	public $id;
	public $title;

	public function __construct(array $properties = array()) {
		foreach ($properties as $name => $value) {
			$this -> $name = $value;
		}
	}
}
