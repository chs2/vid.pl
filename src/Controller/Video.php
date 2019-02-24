<?php
namespace Controller;

use Entity;
use Exception;
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

			$video = $this -> repository -> getOneById($videoId);

			if ($video instanceof Entity\Video) {
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

					throw new Exception\Http400;
				}

				return [
					'code' => 200,
					'message' => 'OK',
					'data' => $video, 
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
}

