<?php
namespace Repository;

use Entity;
use PDO;

abstract class Base {
	const MAX_FETCH_LIMIT = 500;

	protected $conn;

	public function __construct(PDO $conn) {
		$this -> conn = $conn;
	}

	private function getEntityClassBasename() {
		return current(array_slice(explode('\\', get_class($this)), -1, 1));
	}

	private function getEntityFqdnClassname() {
		return 'Entity\\' . $this -> getEntityClassBasename();
	}

	private function getEntitySqlTablename() {
		return strtolower($this -> getEntityClassBasename());
	}
		
	public function getOneById(int $id) {
		$statement = $this -> conn -> prepare(
			'select * '
			. 'from ' . $this -> getEntitySqlTablename() . ' '
			. 'where id = :id'
		);

		$statement -> bindParam(':id', $id, PDO::PARAM_INT);

		$statement -> execute();

		$entityClass = $this -> getEntityFqdnClassname();

		while ($result = $statement -> fetch(PDO::FETCH_ASSOC)) {
			return new $entityClass($result);
		}

		return null;
	}

	public function getAll(int $limit = 50, int $offset = 0) {
		$statement = $this -> conn -> prepare(
			'select * '
			. 'from ' . $this -> getEntitySqlTablename() . ' '
			. 'order by title asc '
			. 'limit :offset, :limit'
		);

		$limit = min($limit, self::MAX_FETCH_LIMIT);
		$statement -> bindParam(':limit', $limit, PDO::PARAM_INT);

		$offset = max($offset, 0);
		$statement -> bindParam(':offset', $offset, PDO::PARAM_INT);

		$statement -> execute();

		$items = [];

		$entityClass = $this -> getEntityFqdnClassname();

		while ($result = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$items[] = new $entityClass($result);
		}

		return $items;
	}
}
