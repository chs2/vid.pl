<?php
namespace Exception;

use PDOStatement;

class Statement extends \Exception {
	public static function instance(PDOStatement $statement, \Exception $previous = null) {
		parent::__construct(
			$statement -> errorInfo()[2],
			$statement -> errorInfo()[1],
			$previous
		);
	}
}
