<?php
namespace Repository;

use Entity;
use PDO;

class Video extends Base {
	public function getVideoPlaylists(int $id) {
		$statement = $this -> conn -> prepare(
			'select playlist.* '
			. 'from video_playlist '
			. 'join playlist on video_playlist.video_id = playlist.id '
			. 'where video_playlist.video_id = :id'
		);

		$statement -> bindParam(':id', $id, PDO::PARAM_INT);

		$statement -> execute();

		$playlists = [];

		while ($result = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$playlists[] = new Entity\Playlist($result);
		}

		return $playlists;
	}
}
