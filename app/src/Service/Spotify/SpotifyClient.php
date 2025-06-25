<?php

namespace App\Service\Spotify;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpotifyClient
{
    private const BASE_URL = 'https://api.spotify.com/v1/';

    public function __construct(
        private HttpClientInterface $client,
        private SpotifyTokenProvider $tokenProvider
    ) {}

    private function request(string $endpoint, array $query = []): array
    {
        $accessToken = $this->tokenProvider->getAccessToken();

        $response = $this->client->request('GET', self::BASE_URL . $endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'query' => $query,
        ]);

        return $response->toArray(false);
    }

    public function searchArtist(string $name): ?array
    {
        $data = $this->request('search', [
            'q' => $name,
            'type' => 'artist',
            'limit' => 1,
        ]);

        return $data['artists']['items'][0] ?? null;
    }

    public function getArtistAlbums(string $artistId, int $limit = 50): array
    {
        $albums = [];
        $url = self::BASE_URL . "artists/{$artistId}/albums";
        $params = ['include_groups' => 'album,single', 'limit' => $limit];

        do {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->tokenProvider->getAccessToken(),
                ],
                'query' => $params,
            ]);

            $data = $response->toArray(false);
            $albums = array_merge($albums, $data['items'] ?? []);
            $url = $data['next'] ?? null;
            $params = [];
        } while ($url);

        return $albums;
    }

    /**
     * Fetch all tracks from an album, handling pagination.
     *
     * @return array List of track data arrays.
     */
    public function getAlbumTracks(string $albumId): array
    {
        $url = self::BASE_URL . "albums/{$albumId}/tracks?limit=50";
        $tracks = [];
        $next = $url;

        while ($next) {
            $response = $this->client->request('GET', $next, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->tokenProvider->getAccessToken(),
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException("Failed to fetch album tracks for album {$albumId}");
            }

            $data = $response->toArray();

            $tracks = array_merge($tracks, $data['items']);
            $next = $data['next'];
        }

        return $tracks;
    }

    public function getAlbum(string $albumId): ?array
    {
        return $this->request("albums/{$albumId}");
    }
}
