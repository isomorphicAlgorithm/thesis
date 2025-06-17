<?php

namespace App\Service\Client;

class MusicBrainzClient extends AbstractClient
{
    private const BASE_URL = 'https://musicbrainz.org/ws/2/';

    private function fetch(string $endpoint, array $params = []): ?array
    {
        $params = array_merge(['fmt' => 'json'], $params);

        $response = $this->client->request('GET', self::BASE_URL . $endpoint, [
            'query' => $params,
            'headers' => [
                'User-Agent' => 'Bandito/1.0 (banditosecure@gmail.com)',
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return $response->toArray(false);
    }

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

    // Get artist details by MBID, including releases (albums), including relations (musicians)
    public function getArtistDetails(string $mbid, int $releaseLimit, string $inc): array
    {
        $url = self::BASE_URL . 'artist/' . $mbid;

        $query = http_build_query([
            'fmt' => 'json',
            'inc' => $inc,
            'limit' => $releaseLimit,
        ]);

        $response = $this->client->request('GET', $url . '?' . $query);

        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException('MusicBrainz API getArtistDetails failed');
        }

        return $response->toArray();
    }

    /**
     * Get release group for type = album
     */
    public function getReleaseGroups(string $mbid): array
    {
        $url = self::BASE_URL . "release-group";

        $query = http_build_query([
            'fmt' => 'json',
            'type' => 'album',
            'artist' => $mbid,
        ]);

        $response = $this->client->request('GET', $url . '?' . $query);
        $data = $response->toArray();

        return $data;
    }

    /**
     * Get release group details for type = album
     */
    public function getReleaseGroupDetails(string $mbid, string $inc): array
    {
        $url = self::BASE_URL . "release-group/" . $mbid;

        $query = http_build_query([
            'fmt' => 'json',
            'inc' => $inc
        ]);

        $response = $this->client->request('GET', $url . '?' . $query);
        $data = $response->toArray();

        return $data;
    }

    /**
     * Get release recordings
     */
    public function getReleaseRecordings(string $mbid, string $inc): array
    {
        $url = self::BASE_URL . "release/" . $mbid;

        $query = http_build_query([
            'fmt' => 'json',
            'inc' => $inc
        ]);

        $response = $this->client->request('GET', $url . '?' . $query);
        $data = $response->toArray();

        return $data;
    }

    /**
     * Get release recording details
     */
    public function getReleaseRecordingDetails(string $mbid, string $inc): array
    {
        $url = self::BASE_URL . "recording/" . $mbid;

        $query = http_build_query([
            'fmt' => 'json',
            'inc' => $inc
        ]);

        $response = $this->client->request('GET', $url . '?' . $query);
        $data = $response->toArray();

        return $data;
    }

    /**
     * Get detailed recording info by MBID (including relations)
     */
    public function getRecordingDetails(string $mbid): array
    {
        $url = self::BASE_URL . "recording/$mbid?inc=artists+releases+url-rels&fmt=json";

        $response = $this->client->request('GET', $url);
        $data = $response->toArray();

        return $data;
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
