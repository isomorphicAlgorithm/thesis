<?php

namespace App\Service\Spotify;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpotifyTokenProvider
{
    private const CACHE_KEY = 'spotify_access_token';

    public function __construct(
        private CacheInterface $cache,
        private HttpClientInterface $client,
        private string $clientId,
        private string $clientSecret
    ) {}

    public function getAccessToken(): string
    {
        return $this->cache->get(self::CACHE_KEY, function (ItemInterface $item) {
            $item->expiresAfter(3500);

            $auth = base64_encode("{$this->clientId}:{$this->clientSecret}");

            $response = $this->client->request('POST', 'https://accounts.spotify.com/api/token', [
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
}
