<?php
namespace Repository;

use Entity;
use PDO;

class Playlist {
	const MAX_FETCH_LIMIT = 500;

	private $conn;

	public function __construct(PDO $conn) {
		$this -> conn = $conn;
	}

	public function getOneById(int $id) {
		$statement = $this -> conn -> prepare(
			'select * '
			. 'from playlist '
			. 'where id = :id'
		);

		$statement -> bindParam(':id', $id, PDO::PARAM_INT);

		$statement -> execute();

		while ($result = $statement -> fetch(PDO::FETCH_ASSOC)) {
			return new Entity\Playlist($result);
		}

		return null;
	}

	public function getAll(int $limit = 50, int $offset = 0) {
		$statement = $this -> conn -> prepare(
			'select * '
			. 'from playlist '
			. 'order by title asc '
			. 'limit :offset, :limit'
		);

		$statement -> bindParam(':limit', min($limit, self::MAX_FETCH_LIMIT), PDO::PARAM_INT);
		$statement -> bindParam(':offset', max($offset, 0), PDO::PARAM_INT);

		$statement -> execute();

		$playlists = [];

		while ($result = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$playlists[] = new Entity\Playlist($result);
		}

		return $playlists;
	}

	public function getPlaylistVideos(int $id) {
		$statement = $this -> conn -> prepare(
			'select video.* '
			. 'from video_playlist '
			. 'join video on video_playlist.video_id = video.id '
			. 'where video_playlist.playlist_id = :id'
		);

		$statement -> bindParam(':id', $id, PDO::PARAM_INT);

		$statement -> execute();

		$videos = [];

		while ($result = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$videos[] = new Entity\Video($result);
		}

		return $videos;
	}

	public function store(Entity\Playlist $playlist) {
		if (null === $playlist -> id) {
			$statement = $this -> conn -> prepare(
				'insert into playlist(id, title) values(:id, :title)'
			);
		} else {
			$statement = $this -> conn -> prepare(
				'update playlist set title = :title where id = :id'
			);
		}

		$statement -> bindParam(':id', $playlist -> id, PDO::PARAM_INT);
		$statement -> bindParam(':title', $playlist -> title, PDO::PARAM_STR);

		if (false === $statement -> execute()) {
			throw new \Exception($this -> conn -> errorInfo()[2], $this -> conn -> errorCode());
		}

		if (null === $playlist -> id) {
			$playlist -> id = $this -> conn -> lastInsertId();
		}

		return (array)$playlist;
	}

	public function delete(Entity\Playlist $playlist) {
		if (null === $playlist -> id) {
			throw new \Exception('Bad Request', 400);
		}

		$statement = $this -> conn -> prepare('delete from playlist where id = :id');

		$statement -> bindParam(':id', $playlist -> id, PDO::PARAM_INT);

		if (false === $statement -> execute()) {
			throw new \Exception($this -> conn -> errorInfo()[2], $this -> conn -> errorCode());
		}

		return true;
	}
}
