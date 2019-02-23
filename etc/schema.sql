CREATE TABLE video_playlist(playlist_id int, video_id int, rank int);
CREATE TABLE playlist (id integer primary key autoincrement, title varchar);
CREATE TABLE video (id integer primary key autoincrement, title varchar);
CREATE TABLE sqlite_sequence(name,seq);

