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
				'code' => 200,
				'message' => 'OK',
				'data' => $this -> repository -> getOneById($playlistId),
			];
		}

		
		return [
			'code' => 200,
			'message' => 'OK',
			'data' => $this -> repository -> getAll(),
		];
	}

	public function put($request) {
		if (!empty($request[1])) {
			$playlistId = $request[1];
			$playlist = $this -> repository -> getOneById($playlistId);

			if (!($playlist instanceof Entity\Playlist)) {
				throw new \Exception('Not Found', 404);
			}
		} else {
			$playlist = new Entity\Playlist;
		}

		$body = file_get_contents('php://input');
		$json = json_decode($body, true);

		if (!empty($request[2])) {
			$body = file_get_contents('php://input');
			$json = json_decode($body, true);

			switch ($request[2]) {
				case 'videos':
					if (array_key_exists('video_id', $json) && array_key_exists('rank', $json)) {
						return [
							'code' => 201,
							'message' => 'Created',
							'data' => $this -> repository -> addVideo($playlist, $json['video_id'], $json['rank']),
						];
					}
				break;
			}

			throw new \Exception('Bad Request', 400);
		}

		if (array_key_exists('id', $json)) {
			throw new \Exception('Bad Request', 400);
		}

		foreach ($json as $key => $value) {
			$playlist -> $key = $value;
		}

		return [
			'code' => 201,
			'message' => 'Created',
			$this -> repository -> store($playlist),
		];
	}

	public function delete($request) {
		if (!empty($request[1])) {
			$playlistId = $request[1];
			$playlist = $this -> repository -> getOneById($playlistId);

			if (!($playlist instanceof Entity\Playlist)) {
				throw new \Exception('Not Found', 404);
			}
		}

		if (!empty($request[2])) {
			$body = file_get_contents('php://input');
			$json = json_decode($body, true);

			switch ($request[2]) {
				case 'videos':
					if (array_key_exists('video_id', $json)) {
						return [
							'code' => 204,
							'message' => 'No Content',
							'data' => $this -> repository -> removeVideoById($playlist, $json['video_id']),
						];
					} elseif (array_key_exists('rank', $json)) {
						return [
							'code' => 204,
							'message' => 'No Content',
							'data' => $this -> repository -> removeVideoByRank($playlist, $json['rank']),
						];
					}
				break;
			}

			throw new \Exception('Bad Request', 400);
		}

		return [
			'code' => 204,
			'message' => 'No Content',
			'data' => $this -> repository -> delete($playlist),
		];
	}
}

