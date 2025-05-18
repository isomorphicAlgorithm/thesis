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

    public function getAlbumInfo(string $mbid): ?array
    {
        $response = $this->client->request('GET', self::API_BASE_URL, [
            'query' => [
                'method' => 'album.getInfo',
                'mbid' => $mbid,
                'api_key' => $this->apiKey,
                'format' => 'json',
            ]
        ]);

        return $response->toArray(false)['album'] ?? null;
    }

    public function getArtistInfo(string $mbid): ?array
    {
        $response = $this->client->request('GET', self::API_BASE_URL, [
            'query' => [
                'method' => 'artist.getInfo',
                'mbid' => $mbid,
                'api_key' => $this->apiKey,
                'format' => 'json',
            ]
        ]);

        return $response->toArray(false)['artist'] ?? null;
    }
}
