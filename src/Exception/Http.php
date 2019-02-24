<?php
namespace Exception;

abstract class Http extends \Exception {
	final public function __construct(\Exception $previous = null) {
		parent::__construct(
			static::MESSAGE,
			static::CODE,
			$previous
		);
	}
}

