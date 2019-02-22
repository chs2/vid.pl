<?php
namespace Controller;

use Entity;

class Playlist {
	public function __construct() {
	}

	public function get($request) {
		if (!empty($request[1])) {
			$playlistId = $request[1];

			if (!empty($request[2])) {
				switch ($request[2]) {
					case 'videos':
						return [
							'data' => [
								new Entity\Video,
							],
						];
					break;
				}

				throw new \Exception('Bad Request', 400);
			}

			return [
				'data' => new Entity\Playlist,
			];
		}

		return [
			'data' => [
				new Entity\Playlist,
			],
		];
	}
}

