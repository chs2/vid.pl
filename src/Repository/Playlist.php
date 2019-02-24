<?php
namespace Repository;

use Entity;
use Exception;
use PDO;

class Playlist extends Base {
	public function getPlaylistVideos(int $id) {
		$statement = $this -> conn -> prepare(
			'select video.* '
			. 'from video_playlist '
			. 'join video on video_playlist.video_id = video.id '
			. 'where video_playlist.playlist_id = :id '
			. 'order by video_playlist.rank asc'
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
			throw Exception\Statement::instance($statement);
		}

		if (null === $playlist -> id) {
			$playlist -> id = $this -> conn -> lastInsertId();
		}

		return (array)$playlist;
	}

	public function delete(Entity\Playlist $playlist) {
		if (null === $playlist -> id) {
			throw new Exception\Http400;
		}

		$statement = $this -> conn -> prepare('delete from playlist where id = :id');

		$statement -> bindParam(':id', $playlist -> id, PDO::PARAM_INT);

		if (false === $statement -> execute()) {
			throw Exception\Statement::instance($statement);
		}

		return true;
	}

	public function addVideo(Entity\Playlist $playlist, $video_id, $rank = 0) {
		if (null === $playlist -> id) {
			throw new Exception\Http400;
		}

		$statement = $this -> conn -> prepare(
			'insert into video_playlist '
			. '(playlist_id, video_id, rank) '
			. 'values '
			. '(:playlist_id, :video_id, :rank)'
		);

		$statement -> bindParam(':playlist_id', $playlist -> id, PDO::PARAM_INT);
		$statement -> bindParam(':video_id', $video_id, PDO::PARAM_INT);
		$statement -> bindParam(':rank', $rank, PDO::PARAM_INT);

		if (false === $statement -> execute()) {
			throw Exception\Statement::instance($statement);
		}

		return true;
	}
	public function removeVideoById(Entity\Playlist $playlist, $videoId) {
		if (null === $playlist -> id) {
			throw new Exception\Http400;
		}

		$statement = $this -> conn -> prepare('delete from video_playlist where playlist_id = :playlist_id and video_id = :video_id');

		$statement -> bindParam(':playlist_id', $playlist -> id, PDO::PARAM_INT);
		$statement -> bindParam(':video_id', $video_id, PDO::PARAM_INT);

		if (false === $statement -> execute()) {
			throw Exception\Statement::instance($statement);
		}

		return true;
	}

	public function removeVideoByRank(Entity\Playlist $playlist, $rank) {
		if (null === $playlist -> id) {
			throw new Exception\Http400;
		}

		$statement = $this -> conn -> prepare('delete from video_playlist where playlist_id = :playlist_id and rank = :rank');

		$statement -> bindParam(':playlist_id', $playlist -> id, PDO::PARAM_INT);
		$statement -> bindParam(':rank', $rank, PDO::PARAM_INT);

		if (false === $statement -> execute()) {
			throw Exception\Statement::instance($statement);
		}

		return true;
	}
}
