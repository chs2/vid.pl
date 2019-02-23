1. Clone the project (thanks Captain Obvious)
2. Make data directory world RWX (chmod -R 777 data, shame on me)

# List all playlists

GET <base-href>/playlist

# Get one playlist details

GET <base-href>/playlist/<id>

# Get one playlist videos

GET <base-href>/playlist/<id>/videos

# List all videos

GET <base-href>/video

# Get one video details (bonus)

GET <base-href>/video/<id>

# Get one video playlists (bonus)

GET <base-href>/video/<id>/playlists

# Create a playlist

PUT <base-href>/playlist
{
	"title": "…"
}

# Update one playlist details

PUT <base-href>/playlist/<id>
{
	"title": "…"
}

# Delete one playlist

DELETE <base-href>/playlist/<id>

# Add one video to one playlist

PUT <base-href>/playlist/<id>/videos
{
	"video_id": 1,
	"rank": 1
}

# Remove one video from one playlist

## By rank

DELETE <base-href>/playlist/<id>/videos
{
	"rank": 1
}

## By Video ID

DELETE <base-href>/playlist/<id>/videos
{
	"video_id": 1
}

