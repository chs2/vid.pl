# Playlists

## List all playlists

GET /playlist

## Get one playlist details

GET /playlist/[id]

## Get one playlist videos

GET /playlist/[id]/videos

## Create a playlist

PUT /playlist
{
	"title": "…"
}

## Update one playlist details

PUT /playlist/[id]
{
	"title": "…"
}

## Delete one playlist

DELETE /playlist/[id]

## Add one video to one playlist

PUT /playlist/[id]/videos
{
	"video_id": 1,
	"rank": 1
}

if rank is missing, video will be appended to the playlist

## Remove one video from one playlist

DELETE /playlist/[playlist_id]/videos/[video_id]

# Videos

## List all videos

GET /video

## Get one video details (bonus)

GET /video/[id]

## Get one video playlists (bonus)

GET /video/[id]/playlists

