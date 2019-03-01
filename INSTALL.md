1. Clone the project (thanks Captain Obvious)
2. Make data directory world RWX (chmod -R 777 data, shame on me)
3. Run "php -t public -S localhost:3615"

# List all playlists

GET /playlist

# Get one playlist details

GET /playlist/[id]

# Get one playlist videos

GET /playlist/[id]/videos

# List all videos

GET /video

# Get one video details (bonus)

GET /video/[id]

# Get one video playlists (bonus)

GET /video/[id]/playlists

# Create a playlist

PUT /playlist
{
	"title": "…"
}

# Update one playlist details

PUT /playlist/[id]
{
	"title": "…"
}

# Delete one playlist

DELETE /playlist/[id]

# Add one video to one playlist

PUT /playlist/[id]/videos
{
	"video_id": 1,
	"rank": 1
}

# Remove one video from one playlist

## By rank

DELETE /playlist/[id]/videos
{
	"rank": 1
}

## By Video ID

DELETE /playlist/[id]/videos
{
	"video_id": 1
}

