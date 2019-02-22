<?php
namespace Controller;

use Entity;
use Repository;

class Playlist {
	public function __construct() {
	}

	private $repository;

	public function setRepository(Repository\Playlist $repository) {
		$this -> repository = $repository;
	}

	public function get($request) {
		if (!empty($request[1])) {
			$playlistId = $request[1];

			if (!empty($request[2])) {
				switch ($request[2]) {
					case 'videos':
						return [
							'data' => $this -> repository -> getPlaylistVideos($playlistId),
						];
					break;
				}

				throw new \Exception('Bad Request', 400);
			}

			return [
				'data' => $this -> repository -> getOneById($playlistId),
			];
		}

		
		return [
			'data' => $this -> repository -> getAll(),
		];
	}
}

