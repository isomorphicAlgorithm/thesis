<?php
// src/Service/SpotifyService.php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class SpotifyService
{
    private const TOKEN_CACHE_KEY = 'spotify_access_token';

    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheInterface $cache,
        private string $clientId,
        private string $clientSecret
    ) {}

    /**
     * Get a valid Spotify API access token from cache or refresh if expired.
     */
    private function getAccessToken(): string
    {
        return $this->cache->get(self::TOKEN_CACHE_KEY, function (ItemInterface $item) {
            $item->expiresAfter(3500); // Slightly less than 1 hour

            $auth = base64_encode("{$this->clientId}:{$this->clientSecret}");

            $response = $this->httpClient->request('POST', 'https://accounts.spotify.com/api/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . $auth,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'body' => 'grant_type=client_credentials',
            ]);

            $data = $response->toArray(false);

            if (!isset($data['access_token'])) {
                throw new \RuntimeException('Spotify token fetch failed: ' . json_encode($data));
            }

            return $data['access_token'];
        });
    }

    /**
     * Search for an artist by name and return their Spotify data.
     */
    public function searchArtist(string $name): ?array
    {
        $accessToken = $this->getAccessToken();

        $response = $this->httpClient->request('GET', 'https://api.spotify.com/v1/search', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'query' => [
                'q' => $name,
                'type' => 'artist',
                'limit' => 1,
            ],
        ]);

        $data = $response->toArray(false);

        return $data['artists']['items'][0] ?? null;
    }

    /**
     * Get artist image URL (or null).
     */
    public function getArtistImage(string $name): ?string
    {
        $artist = $this->searchArtist($name);

        return $artist['images'][0]['url'] ?? null;
    }
}
