<?php
namespace Controller;

use Entity;
use Repository;

class Video {
	public function __construct() {
	}

	private $repository;

	public function setRepository(Repository\Video $repository) {
		$this -> repository = $repository;
	}

	public function get($request) {
		if (!empty($request[1])) {
			$videoId = $request[1];

			if (!empty($request[2])) {
				switch ($request[2]) {
					case 'playlists':
						return [
							'code' => 200,
							'message' => 'OK',
							'data' => $this -> repository -> getVideoPlaylists($videoId),
						];
					break;
				}

				throw new \Exception('Bad Request', 400);
			}

			return [
				'code' => 200,
				'message' => 'OK',
				'data' => $this -> repository -> getOneById($videoId),
			];
		}

		return [
			'code' => 200,
			'message' => 'OK',
			'data' => $this -> repository -> getAll(),
		];
	}
}

