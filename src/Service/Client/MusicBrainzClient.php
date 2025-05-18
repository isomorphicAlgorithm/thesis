<?php

namespace App\Service\Client;

class MusicBrainzClient extends AbstractClient
{
    private const BASE_URL = 'https://musicbrainz.org/ws/2/';

    // Search artists by name
    public function searchArtist(string $name, int $limit = 5): array
    {
        $url = self::BASE_URL . 'artist/';

        $query = http_build_query([
            'query' => $name,
            'fmt' => 'json',
            'limit' => $limit,
        ]);

        $response = $this->client->request('GET', $url . '?' . $query);

        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException('MusicBrainz API searchArtist failed');
        }

        $data = $response->toArray();
        return $data['artists'] ?? [];
    }

    // Get artist details by MBID, including releases (albums)
    public function getArtistDetails(string $mbid, int $releaseLimit = 10): array
    {
        $url = self::BASE_URL . 'artist/' . $mbid;

        $query = http_build_query([
            'fmt' => 'json',
            'inc' => 'releases',  // include releases
            'limit' => $releaseLimit,
        ]);

        $response = $this->client->request('GET', $url . '?' . $query);

        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException('MusicBrainz API getArtistDetails failed');
        }

        return $response->toArray();
    }

    // Get recordings (songs) for a release (album) by release MBID
    public function getRecordingsForRelease(string $releaseMbid, int $limit = 50): array
    {
        $url = self::BASE_URL . 'recording/';
        $query = http_build_query([
            'release' => $releaseMbid,
            'fmt' => 'json',
            'limit' => $limit,
        ]);

        $response = $this->client->request('GET', $url . '?' . $query);

        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException('MusicBrainz API getRecordingsForRelease failed');
        }

        $data = $response->toArray();
        return $data['recordings'] ?? [];
    }
}
