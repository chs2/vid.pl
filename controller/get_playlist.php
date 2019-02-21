<?php

if (!empty($request[1])) {
	$playlistId = $request[1];

	if (!empty($request[2])) {
		switch ($request[2]) {
			case 'videos':
				return [
					'data' => [
						new Video
					],
				];
			break;
		}

		throw new \Exception('Bad Request', 400);
	}

	return [
		'data' => new Playlist,
	];
}

return [
	'data' => [
		new Playlist,
	],
];
