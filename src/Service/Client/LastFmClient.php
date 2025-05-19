<?php

namespace App\Service\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class LastFmClient extends AbstractClient
{
    private const API_BASE_URL = 'http://ws.audioscrobbler.com/2.0/';

    public function __construct(HttpClientInterface $client, private string $apiKey)
    {
        parent::__construct($client);
    }

    private function fetch(array $params): ?array
    {
        $params = array_merge([
            'format' => 'json',
            'api_key' => $this->apiKey,
        ], $params);

        $response = $this->client->request('GET', self::API_BASE_URL, ['query' => $params]);

        if ($response->getStatusCode() !== 200) {
            return null;
        }


        return $response->toArray(false);
    }

    public function getArtistInfo(string $mbid, ?string $fallbackName = null): ?array
    {
        // Try MBID first
        $data = $this->fetch([
            'method' => 'artist.getinfo',
            'mbid' => $mbid,
        ]);

        if (!empty($data['artist'])) {
            return $data['artist'];
        }

        // Fallback to name if provided
        if ($fallbackName) {
            $data = $this->fetch([
                'method' => 'artist.getinfo',
                'artist' => $fallbackName,
            ]);

            if (!empty($data['artist'])) {
                return $data['artist'];
            }
        }

        return null;
    }

    public function getAlbumInfo(string $mbid, string $albumTitle): ?array
    {
        return $this->fetch([
            'method' => 'album.getinfo',
            'mbid' => $mbid,
            'album' => $albumTitle,
        ])['album'] ?? null;
    }

    public function getTrackInfo(string $mbid, string $trackTitle): ?array
    {
        return $this->fetch([
            'method' => 'track.getinfo',
            'mbid' => $mbid,
            'track' => $trackTitle,
        ])['track'] ?? null;
    }
}
