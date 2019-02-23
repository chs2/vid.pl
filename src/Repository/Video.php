<?php
namespace Repository;

use Entity;
use PDO;

class Video {
	const MAX_FETCH_LIMIT = 500;

	private $conn;

	public function __construct(PDO $conn) {
		$this -> conn = $conn;
	}

	public function getOneById(int $id) {
		$statement = $this -> conn -> prepare(
			'select * '
			. 'from video '
			. 'where id = :id'
		);

		$statement -> bindParam(':id', $id, PDO::PARAM_INT);

		$statement -> execute();

		while ($result = $statement -> fetch(PDO::FETCH_ASSOC)) {
			return new Entity\Video($result);
		}

		return null;
	}

	public function getAll(int $limit = 50, int $offset = 0) {
		$statement = $this -> conn -> prepare(
			'select * '
			. 'from video '
			. 'order by title asc '
			. 'limit :offset, :limit'
		);

		$statement -> bindParam(':limit', min($limit, self::MAX_FETCH_LIMIT), PDO::PARAM_INT);
		$statement -> bindParam(':offset', max($offset, 0), PDO::PARAM_INT);

		$statement -> execute();

		$videos = [];

		while ($result = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$videos[] = new Entity\Video($result);
		}

		return $videos;
	}

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
