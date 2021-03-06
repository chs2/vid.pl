<?php
namespace Controller;

use Entity;
use Exception;
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

			$playlist = $this -> repository -> getOneById($playlistId);

			if ($playlist instanceof Entity\Playlist) {
				if (!empty($request[2])) {
					switch ($request[2]) {
						case 'videos':
							return [
								'code' => 200,
								'message' => 'OK',
								'data' => $this -> repository -> getPlaylistVideos($playlistId),
							];
						break;
					}

					throw new Exception\Http400;
				}

				return [
					'code' => 200,
					'message' => 'OK',
					'data' => $playlist,
				];
			}

			throw new Exception\Http404;
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
				throw new Exception\Http404;
			}
		} else {
			$playlist = new Entity\Playlist;
		}

		$body = file_get_contents('php://input');
		$json = json_decode($body, true);

		if (!empty($request[2])) {
			switch ($request[2]) {
				case 'videos':
					if (array_key_exists('video_id', $json)) {
						if (!array_key_exists('rank', $json)) {
							$json['rank'] = null;
						}

						try {
							return [
								'code' => 201,
								'message' => 'Created',
								'data' => $this -> repository -> addVideo($playlist, $json['video_id'], $json['rank']),
							];
						} catch (\Exception $e) {
							throw new Exception\Http500($e);
						}
					}
				break;
			}

			throw new Exception\Http400;
		}

		if (array_key_exists('id', $json)) {
			throw new Exception\Http400;
		}

		foreach ($json as $key => $value) {
			if (property_exists($playlist, $key)) {
				$playlist -> $key = $value;
			} else {
				throw new Exception\Http400;
			}
		}

		try {
			return [
				'code' => 201,
				'message' => 'Created',
				'data' => $this -> repository -> store($playlist),
			];
		} catch (\Exception $e) {
			throw new Exception\Http500($e);
		}
	}

	public function delete($request) {
		if (!empty($request[1])) {
			$playlistId = $request[1];
			$playlist = $this -> repository -> getOneById($playlistId);

			if (!($playlist instanceof Entity\Playlist)) {
				throw new Exception\Http404;
			}
		}

		if (!empty($request[2])) {
			switch ($request[2]) {
				case 'videos':
					if (!empty($request[3])) {
						try {
							return [
								'code' => 204,
								'message' => 'No Content',
								'data' => $this -> repository -> removeVideo($playlist, $request[3]),
							];
						} catch (\Exception $e) {
							throw new Exception\Http500($e);
						}
					}
				break;
			}

			throw new Exception\Http400;
		}

		try {
			return [
				'code' => 204,
				'message' => 'No Content',
				'data' => $this -> repository -> delete($playlist),
			];
		} catch (\Exception $e) {
			throw new Exception\Http500($e);
		}
	}
}

