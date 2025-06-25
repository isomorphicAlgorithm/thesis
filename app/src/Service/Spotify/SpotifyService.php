<?php

namespace App\Service\Spotify;

class SpotifyService
{
    public function __construct(private SpotifyClient $client) {}

    /**
     * Get total album duration in milliseconds.
     */
    public function getAlbumDuration(string $albumId): ?int
    {
        try {
            $tracks = $this->client->getAlbumTracks($albumId);
        } catch (\RuntimeException $e) {
            // Log error or handle gracefully
            return null;
        }

        $duration = 0;
        foreach ($tracks as $track) {
            $duration += $track['duration_ms'] ?? 0;
        }

        return (int) round($duration / 1000);
    }

    public function getArtistImage(string $name): ?string
    {
        $artist = $this->client->searchArtist($name);

        return $artist['images'][0]['url'] ?? null;
    }
}
