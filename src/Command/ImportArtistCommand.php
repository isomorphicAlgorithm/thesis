<?php

namespace App\Command;

use App\Entity\Album;
use App\Entity\Band;
use App\Entity\Musician;
use App\Entity\Song;
use App\Service\Client\LastFmClient;
use App\Service\Client\MusicBrainzClient;
use App\Service\Spotify\SpotifyClient;
use App\Service\Spotify\SpotifyService;
use App\Service\GenreService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImportArtistCommand extends Command
{
    protected static $defaultName = 'bandito:import-artist';

    public function __construct(
        private MusicBrainzClient $musicBrainzClient,
        private LastFmClient $lastFmClient,
        private EntityManagerInterface $em,
        private SluggerInterface $slugger,
        private SpotifyClient $spotifyClient,
        private SpotifyService $spotifyService,
        private GenreService $genreService,
    ) {
        parent::__construct('bandito:import-artist');
    }

    protected function configure()
    {
        $this
            ->setDescription('Import artist info from MusicBrainz by artist name')
            ->addArgument('artistName', InputArgument::REQUIRED, 'Artist name to search and import');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $artistInputName = $input->getArgument('artistName');

        $spotifyArtist = $this->spotifyClient->searchArtist($artistInputName);

        if (!$spotifyArtist) {
            $output->writeln('<error>Artist not found on Spotify</error>');

            return Command::FAILURE;
        } else {
            $output->writeln('Found artist: ' . $spotifyArtist['name'] . ' (Spotify ID: ' . $spotifyArtist['id'] . ') on Spotify');
        }

        $musicBrainzArtists = $this->musicBrainzClient->searchArtist($artistInputName);

        if (empty($musicBrainzArtists)) {
            $output->writeln('No artists found in MusicBrainz.');
            return Command::FAILURE;
        }

        $musicBrainzArtist = $musicBrainzArtists[0];

        $output->writeln('Found artist: ' . $musicBrainzArtist['name'] . ' (MBID: ' . $musicBrainzArtist['id'] . ') on MusicBrainz');

        $fetchedBand = null;
        $fetchedMusician = null;

        if ($musicBrainzArtist['type'] == 'Group') {
            $fetchedBand = $musicBrainzArtist; // band artist
        } else {
            $fetchedMusician = $musicBrainzArtist; // musician artist
        }

        // Band Logic
        if ($fetchedBand) {
            $slug = $this->slugger->slug($fetchedBand['name'])->lower();

            // --- Find or create Band ---
            $dbBand = $this->em->getRepository(Band::class)
                ->findOneBy([
                    'slug' => $slug,
                    'music_brainz_id' => $fetchedBand['id'],
                    'spotify_id' => $spotifyArtist['id']
                ]);

            if (!$dbBand) {
                $band = new Band();
                $band->setName($fetchedBand['name']); // name
                $band->setMusicBrainzId($fetchedBand['id']); // music_brainz_id

                // Last.fm band info
                $lastfmBand = $this->lastFmClient->getArtistInfo($fetchedBand['id'], $fetchedBand['name']);

                if ($lastfmBand && !empty($lastfmBand['bio']['summary'])) {
                    $band->setBio($lastfmBand['bio']['summary']); // bio
                }

                $genresData = [];

                if ($spotifyArtist) {
                    // Setting Spotify Id
                    $band->setSpotifyId($spotifyArtist['id']); // spotify_id
                    // Checking Spotify band images
                    if ($spotifyArtist['images'][0]['url']) {
                        $band->setCoverImage($spotifyArtist['images'][0]['url']); // cover_image
                    }

                    // Genres Logic
                    if (!empty($spotifyArtist['genres'])) {
                        foreach ($spotifyArtist['genres'] as $spotifyBandGenre) {
                            $genresData[] = $spotifyBandGenre;
                        }
                    }
                }

                // Genres Logic
                if (empty($genresData)) {
                    $fetchedBandGenres = $this->getArtistDetailsWithRetry($fetchedBand['id'], 10, 'genres');

                    foreach ($fetchedBandGenres['genres'] ?? [] as $fetchedBandGenre) {
                        if (isset($fetchedBandGenre['name'])) {
                            $genresData[] = $fetchedBandGenre['name'];
                        }
                    }
                }

                if (!empty($genresData)) {
                    $genres = $this->genreService->getOrCreateGenres($genresData);

                    foreach ($genres as $genre) {
                        $band->addGenre($genre); // genres
                    }
                }

                // Band life span
                $fetchedBandLifeSpan = $this->getArtistDetailsWithRetry($fetchedBand['id'], 10, '');

                if (isset($fetchedBandLifeSpan['life-span'])) {
                    $bandLifeSpan = $fetchedBandLifeSpan['life-span'];

                    if (!empty($bandLifeSpan['begin']) && ($date = $this->genreService->createImmutableDate($bandLifeSpan['begin']))) {
                        $immutableDate = $date instanceof \DateTimeImmutable ? $date : \DateTimeImmutable::createFromMutable($date);

                        $band->setActiveFrom($immutableDate); // active_from
                    }

                    if (!empty($bandLifeSpan['end']) && ($date = $this->genreService->createImmutableDate($bandLifeSpan['end']))) {
                        $immutableDate = $date instanceof \DateTimeImmutable ? $date : \DateTimeImmutable::createFromMutable($date);

                        $band->setActiveUntil($immutableDate); // active_until
                    }

                    if (isset($bandLifeSpan['ended'])) {
                        $band->setIsDisbanded($bandLifeSpan['ended']); // is_disbanded
                    }
                }

                // Extract band links from MusicBrainz
                $fetchedBandLinks = $this->getArtistDetailsWithRetry($fetchedBand['id'], 10, 'url-rels');

                $extractedBandLinks = $this->extractLinksFromRelations($fetchedBandLinks['relations'], $spotifyArtist);

                if (!empty($extractedBandLinks)) {
                    $band->setLinks($extractedBandLinks); // links
                }

                $this->em->persist($band);
                $output->writeln('Created new band: ' . $band->getName());
            } else {
                $band = $dbBand; //Assigning the band with the one from db for updating
                $output->writeln('Using existing band: ' . $dbBand->getName());
            }

            $this->em->flush();

            // Band Musicians Logic
            $fetchedMusicians = $this->getArtistDetailsWithRetry($fetchedBand['id'], 15, 'artist-rels');

            // --- Import musicians from relations ---
            if (!empty($fetchedMusicians['relations'])) {
                foreach ($fetchedMusicians['relations'] as $fetchedMusician) {
                    // Check if type is member of the band
                    if (($fetchedMusician['type'] ?? '') === 'member of band' && !empty($fetchedMusician['artist'])) {
                        $musicianData = $fetchedMusician['artist'];

                        $slug = $this->slugger->slug($musicianData['name'])->lower();

                        $spotifyMusician = $this->spotifyClient->searchArtist($musicianData['name']);

                        $dbMusician = $this->em->getRepository(Musician::class)
                            ->findOneBy([
                                'slug' => $slug,
                                'spotify_id' => $spotifyMusician['id'],
                                'music_brainz_id' => $musicianData['id']
                            ]);

                        if (!$dbMusician) {

                            $dbMusicianSpotifyRecheck = $this->em->getRepository(Musician::class)
                                ->findOneBy([
                                    'spotify_id' => $spotifyMusician['id'],
                                ]);

                            if ($dbMusicianSpotifyRecheck) {
                                continue;
                            }

                            $dbMusicianMusicBrainzRecheck = $this->em->getRepository(Musician::class)
                                ->findOneBy([
                                    'music_brainz_id' => $musicianData['id'],
                                ]);

                            if ($dbMusicianMusicBrainzRecheck) {
                                continue;
                            }

                            $musician = new Musician();
                            $musician->setName($musicianData['name']); // name
                            $musician->setMusicBrainzId($musicianData['id']); // music_brainz_id

                            // Last.fm musician info
                            $lastfmMusician = $this->lastFmClient->getArtistInfo($musicianData['id'], $musicianData['name']);

                            if ($lastfmMusician && !empty($lastfmMusician['bio']['summary'])) {
                                $musician->setBio($lastfmMusician['bio']['summary']); // bio
                            }

                            $genresData = [];

                            // Checking Spotify musician images
                            if ($spotifyMusician) {
                                // Setting Spotify Id
                                $musician->setSpotifyId($spotifyMusician['id']); // spotify_id

                                // Checking Spotify musician images
                                if (isset($spotifyMusician['images'][0]['url'])) {
                                    $musician->setCoverImage($spotifyMusician['images'][0]['url']); // cover_image
                                }

                                // Genres Logic
                                if (!empty($spotifyMusician['genres'])) {
                                    foreach ($spotifyMusician['genres'] as $spotifyMusicianGenre) {
                                        $genresData[] = $spotifyMusicianGenre;
                                    }
                                }
                            }

                            // Genres Logic
                            if (empty($genresData)) {
                                $fetchedMusicianGenres = $this->getArtistDetailsWithRetry($musicianData['id'], 10, 'genres');

                                foreach ($fetchedMusicianGenres['genres'] ?? [] as $fetchedMusicianGenre) {
                                    if (isset($fetchedMusicianGenre['name'])) {
                                        $genresData[] = $fetchedMusicianGenre['name'];
                                    }
                                }
                            }

                            if (!empty($genresData)) {
                                $genres = $this->genreService->getOrCreateGenres($genresData);

                                foreach ($genres as $genre) {
                                    $musician->addGenre($genre); // genres
                                }
                            }

                            $fetchedMusicianLifeSpan = $this->getArtistDetailsWithRetry($musicianData['id'], 10, '');

                            if (isset($fetchedMusicianLifeSpan['life-span'])) {
                                $musicianLifeSpan = $fetchedMusicianLifeSpan['life-span'];

                                if (!empty($musicianLifeSpan['begin']) && ($date = $this->genreService->createImmutableDate($musicianLifeSpan['begin']))) {
                                    $immutableDate = $date instanceof \DateTimeImmutable ? $date : \DateTimeImmutable::createFromMutable($date);

                                    $musician->setActiveFrom($immutableDate); // active_from
                                }

                                if (!empty($musicianLifeSpan['end']) && ($date = $this->genreService->createImmutableDate($musicianLifeSpan['end']))) {
                                    $immutableDate = $date instanceof \DateTimeImmutable ? $date : \DateTimeImmutable::createFromMutable($date);

                                    $musician->setActiveUntil($immutableDate); // active_until
                                }

                                if (isset($musicianLifeSpan['ended'])) {
                                    $musician->setIsDisbanded($musicianLifeSpan['ended']); // is_disbanded
                                }
                            }

                            // Extract musician links from MusicBrainz
                            $fetchedMusicianLinks = $this->getArtistDetailsWithRetry($musicianData['id'], 10, 'url-rels');

                            $extractedMusicianLinks = $this->extractLinksFromRelations($fetchedMusicianLinks['relations'], $spotifyMusician);

                            if (!empty($extractedMusicianLinks)) {
                                $musician->setLinks($extractedMusicianLinks); // links
                            }

                            $this->em->persist($musician);
                            $this->em->flush(); //avoiding duplication

                            $output->writeln('Added musician: ' . $musician->getName());
                        } else {
                            $musician = $dbMusician;

                            $output->writeln('Existing musician: ' . $musician->getName());
                        }

                        // Band relation
                        if (!$musician->getBands()->contains($band)) {
                            $musician->addBand($band);
                        }
                        if (!$band->getMusicians()->contains($musician)) {
                            $band->addMusician($musician);
                        }
                    }
                }
            }

            $this->em->flush();

            // albums
            if ($band && $band->getSpotifyId()) {
                $musicBrainzBandAlbums = $this->getReleaseGroupsWithRetry($fetchedBand['id']);;
                $spotifyBandAlbums = $this->spotifyClient->getArtistAlbums($band->getSpotifyId());

                foreach ($spotifyBandAlbums as $spotifyBandAlbum) {
                    if ($spotifyBandAlbum['album_type'] == 'album') {
                        $slug = $this->slugger->slug($spotifyBandAlbum['name'])->lower();

                        $dbAlbum = $this->em->getRepository(Album::class)
                            ->findOneBy([
                                'slug' => $slug,
                                'spotify_id' => $spotifyBandAlbum['id']
                            ]);

                        if (!$dbAlbum) {
                            $album = new Album();
                            $album->setTitle($spotifyBandAlbum['name']); // name
                            $album->setSpotifyId($spotifyBandAlbum['id']); // spotify_id
                            $album->setCoverImage($spotifyBandAlbum['images'][0]['url'] ?? null); // cover_image
                            $album->setReleaseDate(new \DateTimeImmutable($spotifyBandAlbum['release_date'])); // release_date

                            $duration = $this->spotifyService->getAlbumDuration($spotifyBandAlbum['id']);

                            $album->setDuration($duration); // duration

                            //Album links (taken from MusicBrainz) and description (taken from Last.fm)
                            foreach ($musicBrainzBandAlbums['release-groups'] ?? [] as $musicBrainzBandAlbum) {
                                if ($spotifyBandAlbum['name'] == $musicBrainzBandAlbum['title']) {
                                    if ($musicBrainzBandAlbum['primary-type'] == 'Album') {

                                        // Album genres
                                        $genresData = [];

                                        $fetchedAlbumGenres = $this->getReleaseGroupDetailsWithRetry($musicBrainzBandAlbum['id'], 'genres+tags');

                                        foreach ($fetchedAlbumGenres['genres'] ?? [] as $fetchedAlbumGenre) {
                                            if (isset($fetchedAlbumGenre['name'])) {
                                                $genresData[] = $fetchedAlbumGenre['name'];
                                            }
                                        }

                                        if (!empty($genresData)) {
                                            $genres = $this->genreService->getOrCreateGenres($genresData);

                                            foreach ($genres as $genre) {
                                                $album->addGenre($genre); // genres
                                            }
                                        }

                                        // Album links
                                        $fetchedAlbumLinks = $this->getReleaseGroupDetailsWithRetry($musicBrainzBandAlbum['id'], 'url-rels');

                                        $extractedAlbumLinks = $this->extractLinksFromRelations($fetchedAlbumLinks['relations'], $spotifyBandAlbum);

                                        if (!empty($extractedAlbumLinks)) {
                                            $album->setLinks($extractedAlbumLinks); // links
                                        }

                                        // Album description
                                        $lastfmAlbum = $this->lastFmClient->getAlbumInfo($band->getName(), $musicBrainzBandAlbum['title']);

                                        if ($lastfmAlbum && !empty($lastfmAlbum['wiki']['summary'])) {
                                            $album->setDescription($lastfmAlbum['wiki']['summary']); // description
                                        }
                                    }
                                }
                            }

                            $this->em->persist($album);
                            $this->em->flush();

                            $output->writeln('Added album: ' . $album->getTitle());
                        } else {
                            $album = $dbAlbum;

                            $output->writeln('Existing album: ' . $album->getTitle());
                        }

                        // Band and Musician Relations
                        if (!$album->getBands()->contains($band)) {
                            $album->addBand($band);
                        }
                        if (!$band->getAlbums()->contains($album)) {
                            $band->addAlbum($album);
                        }

                        foreach ($band->getMusicians() as $musician) {
                            if (!$album->getMusicians()->contains($musician)) {
                                $album->addMusician($musician);
                            }
                            if (!$musician->getAlbums()->contains($album)) {
                                $musician->addAlbum($album);
                            }
                        }

                        $this->em->flush();

                        //songs-tracks
                        $spotifyAlbumTracks = $this->spotifyClient->getAlbumTracks($spotifyBandAlbum['id']);

                        foreach ($spotifyAlbumTracks as $songData) {
                            //print_r($songData);
                            //die;

                            // Not using slug, since multiple songs with the same name can be inside multiple albums
                            $dbSong = $this->em->getRepository(Song::class)->findOneBy([
                                'spotify_id' => $songData['id'],
                            ]);

                            if (!$dbSong) {
                                $song = new Song();
                                $song->setTitle($songData['name']); // title
                                $song->setSpotifyId($songData['id']); // spotify_id
                                $song->setDuration($songData['duration_ms'] / 1000); // duration
                                $song->setCoverImage($spotifyBandAlbum['images'][0]['url'] ?? null); // cover_image
                                $song->setReleaseDate(new \DateTimeImmutable($spotifyBandAlbum['release_date'])); // release_date
                                $song->setTrackNumber($songData['track_number']); // track_number

                                //Song links (taken from MusicBrainz) and description (taken from Last.fm)
                                foreach ($musicBrainzBandAlbums['release-groups'] ?? [] as $musicBrainzBandAlbum) {
                                    if ($spotifyBandAlbum['name'] == $musicBrainzBandAlbum['title']) {
                                        if ($musicBrainzBandAlbum['primary-type'] == 'Album') {
                                            $fetchedReleases = $this->getReleaseGroupDetailsWithRetry($musicBrainzBandAlbum['id'], 'releases');

                                            $bestRelease = null;

                                            foreach ($fetchedReleases['releases'] as $fetchedRelease) {
                                                if ($fetchedRelease['status'] === 'Official') {
                                                    $bestRelease = $fetchedRelease;
                                                }
                                            }

                                            if (!$bestRelease) {
                                                continue;
                                            }

                                            $fetchedRecordings = $this->getReleaseRecordingsWithRetry($bestRelease['id'], 'recordings');
                                            //print_r($fetchedRecordings);
                                            //die;
                                            foreach ($fetchedRecordings['media'] as $fetchedRecordingMedia) {
                                                foreach ($fetchedRecordingMedia['tracks'] as $fetchedRecordingMediaTrack) {
                                                    $fetchedRecording = $fetchedRecordingMediaTrack['recording'];
                                                    if (strtolower(trim($fetchedRecording['title'])) === strtolower(trim($songData['name']))) {
                                                        // links
                                                        $fetchedRecordingLinks = $this->getReleaseRecordingDetailsWithRetry($fetchedRecording['id'], 'url-rels');

                                                        $extractedSongLinks = $this->extractLinksFromRelations($fetchedRecordingLinks['relations'], $songData);

                                                        if (!empty($extractedSongLinks)) {
                                                            $song->setLinks($extractedSongLinks); // links
                                                        }

                                                        // Song genres
                                                        $genresData = [];

                                                        $fetchedRecordingGenres = $this->getReleaseRecordingDetailsWithRetry($fetchedRecording['id'], 'genres+tags');

                                                        foreach ($fetchedRecordingGenres['genres'] ?? [] as $fetchedRecordingGenre) {
                                                            if (isset($fetchedRecordingGenre['name'])) {
                                                                $genresData[] = $fetchedRecordingGenre['name'];
                                                            }
                                                        }

                                                        if (!empty($genresData)) {
                                                            $genres = $this->genreService->getOrCreateGenres($genresData);

                                                            foreach ($genres as $genre) {
                                                                $song->addGenre($genre); // genres
                                                            }
                                                        }

                                                        // Song description
                                                        $lastfmSong = $this->lastFmClient->getTrackInfo($band->getName(), $fetchedRecording['title']);

                                                        if ($lastfmSong && !empty($lastfmSong['wiki']['summary'])) {
                                                            $song->setDescription($lastfmSong['wiki']['summary']); // description
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                $this->em->persist($song);
                                $this->em->flush($song);

                                $output->writeln('Added song: ' . $song->getTitle());
                            } else {
                                $song = $dbSong;

                                $output->writeln('Existing song: ' . $song->getTitle());
                            }

                            // Song and Album relation
                            if (!$song->getAlbums()->contains($album)) {
                                $song->addAlbum($album);
                            }

                            // Song and Band relation
                            if (!$song->getBands()->contains($band)) {
                                $song->addBand($band);
                            }

                            // Song and Musician(s) relation
                            foreach ($album->getMusicians() as $musician) {
                                if (!$song->getMusicians()->contains($musician)) {
                                    $song->addMusician($musician);
                                }
                            }
                        }

                        $this->em->flush();
                    }/* else {
                        // make single (track) logic
                    }*/
                }

                $this->em->flush();
            }
        } else
        // Solo Musician
        {
            $slug = $this->slugger->slug($fetchedMusician['name'])->lower();

            // --- Find or create Musician ---
            $dbMusician = $this->em->getRepository(Musician::class)
                ->findOneBy([
                    'slug' => $slug,
                    'music_brainz_id' => $fetchedMusician['id'],
                    'spotify_id' => $spotifyArtist['id']
                ]);

            if (!$dbMusician) {
                $musician = new Musician();
                $musician->setName($fetchedMusician['name']); // name
                $musician->setMusicBrainzId($fetchedMusician['id']); // music_brainz_id

                // Last.fm musician info
                $lastfmMusician = $this->lastFmClient->getArtistInfo($fetchedMusician['id'], $fetchedMusician['name']);

                if ($lastfmMusician && !empty($lastfmMusician['bio']['summary'])) {
                    $musician->setBio($lastfmMusician['bio']['summary']); // bio
                }

                $genresData = [];

                if ($spotifyArtist) {
                    // Setting Spotify Id
                    $musician->setSpotifyId($spotifyArtist['id']); // spotify_id
                    // Checking Spotify Musician images
                    if ($spotifyArtist['images'][0]['url']) {
                        $musician->setCoverImage($spotifyArtist['images'][0]['url']); // cover_image
                    }

                    // Genres Logic
                    if (!empty($spotifyArtist['genres'])) {
                        foreach ($spotifyArtist['genres'] as $spotifyMusicianGenre) {
                            $genresData[] = $spotifyMusicianGenre;
                        }
                    }
                }

                // Genres Logic
                if (empty($genresData)) {
                    $fetchedMusicianGenres = $this->getArtistDetailsWithRetry($fetchedMusician['id'], 10, 'genres');

                    foreach ($fetchedMusicianGenres['genres'] ?? [] as $fetchedMusicianGenre) {
                        if (isset($fetchedMusicianGenre['name'])) {
                            $genresData[] = $fetchedMusicianGenre['name'];
                        }
                    }
                }

                if (!empty($genresData)) {
                    $genres = $this->genreService->getOrCreateGenres($genresData);

                    foreach ($genres as $genre) {
                        $musician->addGenre($genre); // genres
                    }
                }

                // Musician life span
                $fetchedMusicianLifeSpan = $this->getArtistDetailsWithRetry($fetchedMusician['id'], 10, '');

                if (isset($fetchedMusicianLifeSpan['life-span'])) {
                    $musicianLifeSpan = $fetchedMusicianLifeSpan['life-span'];

                    if (!empty($musicianLifeSpan['begin']) && ($date = $this->genreService->createImmutableDate($musicianLifeSpan['begin']))) {
                        $immutableDate = $date instanceof \DateTimeImmutable ? $date : \DateTimeImmutable::createFromMutable($date);

                        $musician->setActiveFrom($immutableDate); // active_from
                    }

                    if (!empty($musicianLifeSpan['end']) && ($date = $this->genreService->createImmutableDate($musicianLifeSpan['end']))) {
                        $immutableDate = $date instanceof \DateTimeImmutable ? $date : \DateTimeImmutable::createFromMutable($date);

                        $musician->setActiveUntil($immutableDate); // active_until
                    }

                    if (isset($MusicianLifeSpan['ended'])) {
                        $musician->setIsDisbanded($musicianLifeSpan['ended']); // is_disbanded
                    }
                }

                // Extract musician links from MusicBrainz
                $fetchedMusicianLinks = $this->getArtistDetailsWithRetry($fetchedMusician['id'], 10, 'url-rels');

                $extractedMusicianLinks = $this->extractLinksFromRelations($fetchedMusicianLinks['relations'], $spotifyArtist);

                if (!empty($extractedMusicianLinks)) {
                    $musician->setLinks($extractedMusicianLinks); // links
                }

                $this->em->persist($musician);
                $output->writeln('Created new Musician: ' . $musician->getName());
            } else {
                $musician = $dbMusician; //Assigning the Musician with the one from db for updating
                $output->writeln('Using existing Musician: ' . $dbMusician->getName());
            }

            $this->em->flush();

            // albums
            if ($musician && $musician->getSpotifyId()) {
                $musicBrainzMusicianAlbums = $this->getReleaseGroupsWithRetry($fetchedMusician['id']);;
                $spotifyMusicianAlbums = $this->spotifyClient->getArtistAlbums($musician->getSpotifyId());

                foreach ($spotifyMusicianAlbums as $spotifyMusicianAlbum) {
                    if ($spotifyMusicianAlbum['album_type'] == 'album') {
                        $slug = $this->slugger->slug($spotifyMusicianAlbum['name'])->lower();

                        $dbAlbum = $this->em->getRepository(Album::class)
                            ->findOneBy([
                                'slug' => $slug,
                                'spotify_id' => $spotifyMusicianAlbum['id']
                            ]);

                        if (!$dbAlbum) {
                            $album = new Album();
                            $album->setTitle($spotifyMusicianAlbum['name']); // name
                            $album->setSpotifyId($spotifyMusicianAlbum['id']); // spotify_id
                            $album->setCoverImage($spotifyMusicianAlbum['images'][0]['url'] ?? null); // cover_image
                            $album->setReleaseDate(new \DateTimeImmutable($spotifyMusicianAlbum['release_date'])); // release_date

                            $duration = $this->spotifyService->getAlbumDuration($spotifyMusicianAlbum['id']);

                            $album->setDuration($duration); // duration

                            //Album links (taken from MusicBrainz) and description (taken from Last.fm)
                            foreach ($musicBrainzMusicianAlbums['release-groups'] ?? [] as $musicBrainzMusicianAlbum) {
                                if ($spotifyMusicianAlbum['name'] == $musicBrainzMusicianAlbum['title']) {
                                    if ($musicBrainzMusicianAlbum['primary-type'] == 'Album') {

                                        // Album genres
                                        $genresData = [];

                                        $fetchedAlbumGenres = $this->getReleaseGroupDetailsWithRetry($musicBrainzMusicianAlbum['id'], 'genres+tags');

                                        foreach ($fetchedAlbumGenres['genres'] ?? [] as $fetchedAlbumGenre) {
                                            if (isset($fetchedAlbumGenre['name'])) {
                                                $genresData[] = $fetchedAlbumGenre['name'];
                                            }
                                        }

                                        if (!empty($genresData)) {
                                            $genres = $this->genreService->getOrCreateGenres($genresData);

                                            foreach ($genres as $genre) {
                                                $album->addGenre($genre); // genres
                                            }
                                        }

                                        // Album links
                                        $fetchedAlbumLinks = $this->getReleaseGroupDetailsWithRetry($musicBrainzMusicianAlbum['id'], 'url-rels');

                                        $extractedAlbumLinks = $this->extractLinksFromRelations($fetchedAlbumLinks['relations'], $spotifyMusicianAlbum);

                                        if (!empty($extractedAlbumLinks)) {
                                            $album->setLinks($extractedAlbumLinks); // links
                                        }

                                        // Album description
                                        $lastfmAlbum = $this->lastFmClient->getAlbumInfo($musician->getName(), $musicBrainzMusicianAlbum['title']);

                                        if ($lastfmAlbum && !empty($lastfmAlbum['wiki']['summary'])) {
                                            $album->setDescription($lastfmAlbum['wiki']['summary']); // description
                                        }
                                    }
                                }
                            }

                            $this->em->persist($album);
                            $this->em->flush();

                            $output->writeln('Added album: ' . $album->getTitle());
                        } else {
                            $album = $dbAlbum;

                            $output->writeln('Existing album: ' . $album->getTitle());
                        }

                        // Bands and Musician Relations
                        if (!$album->getBands()->contains($musician)) {
                            $album->addMusician($musician);
                        }

                        if (!$musician->getAlbums()->contains($album)) {
                            $musician->addAlbum($album);
                        }

                        $this->em->flush();

                        //songs-tracks
                        $spotifyAlbumTracks = $this->spotifyClient->getAlbumTracks($spotifyMusicianAlbum['id']);

                        foreach ($spotifyAlbumTracks as $songData) {

                            // Not using slug, since multiple songs with the same name can be inside multiple albums
                            $dbSong = $this->em->getRepository(Song::class)->findOneBy([
                                'spotify_id' => $songData['id'],
                            ]);

                            if (!$dbSong) {
                                $song = new Song();
                                $song->setTitle($songData['name']); // title
                                $song->setSpotifyId($songData['id']); // spotify_id
                                $song->setDuration($songData['duration_ms'] / 1000); // duration
                                $song->setCoverImage($spotifyMusicianAlbum['images'][0]['url'] ?? null); // cover_image
                                $song->setReleaseDate(new \DateTimeImmutable($spotifyMusicianAlbum['release_date'])); // release_date
                                $song->setTrackNumber($songData['track_number']); // track_number

                                //Song links (taken from MusicBrainz) and description (taken from Last.fm)
                                foreach ($musicBrainzMusicianAlbums['release-groups'] ?? [] as $musicBrainzMusicianAlbum) {
                                    if ($spotifyMusicianAlbum['name'] == $musicBrainzMusicianAlbum['title']) {
                                        if ($musicBrainzMusicianAlbum['primary-type'] == 'Album') {
                                            $fetchedReleases = $this->getReleaseGroupDetailsWithRetry($musicBrainzMusicianAlbum['id'], 'releases');

                                            $bestRelease = null;

                                            foreach ($fetchedReleases['releases'] as $fetchedRelease) {
                                                if ($fetchedRelease['status'] === 'Official') {
                                                    $bestRelease = $fetchedRelease;
                                                }
                                            }

                                            if (!$bestRelease) {
                                                continue;
                                            }

                                            $fetchedRecordings = $this->getReleaseRecordingsWithRetry($bestRelease['id'], 'recordings');
                                            //print_r($fetchedRecordings);
                                            //die;
                                            foreach ($fetchedRecordings['media'] as $fetchedRecordingMedia) {
                                                foreach ($fetchedRecordingMedia['tracks'] as $fetchedRecordingMediaTrack) {
                                                    $fetchedRecording = $fetchedRecordingMediaTrack['recording'];
                                                    if (strtolower(trim($fetchedRecording['title'])) === strtolower(trim($songData['name']))) {
                                                        // links
                                                        $fetchedRecordingLinks = $this->getReleaseRecordingDetailsWithRetry($fetchedRecording['id'], 'url-rels');

                                                        $extractedSongLinks = $this->extractLinksFromRelations($fetchedRecordingLinks['relations'], $songData);

                                                        if (!empty($extractedSongLinks)) {
                                                            $song->setLinks($extractedSongLinks); // links
                                                        }

                                                        // Song genres
                                                        $genresData = [];

                                                        $fetchedRecordingGenres = $this->getReleaseRecordingDetailsWithRetry($fetchedRecording['id'], 'genres+tags');

                                                        foreach ($fetchedRecordingGenres['genres'] ?? [] as $fetchedRecordingGenre) {
                                                            if (isset($fetchedRecordingGenre['name'])) {
                                                                $genresData[] = $fetchedRecordingGenre['name'];
                                                            }
                                                        }

                                                        if (!empty($genresData)) {
                                                            $genres = $this->genreService->getOrCreateGenres($genresData);

                                                            foreach ($genres as $genre) {
                                                                $song->addGenre($genre); // genres
                                                            }
                                                        }

                                                        // Song description
                                                        $lastfmSong = $this->lastFmClient->getTrackInfo($musician->getName(), $fetchedRecording['title']);

                                                        if ($lastfmSong && !empty($lastfmSong['wiki']['summary'])) {
                                                            $song->setDescription($lastfmSong['wiki']['summary']); // description
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                $this->em->persist($song);
                                $this->em->flush($song);

                                $output->writeln('Added song: ' . $song->getTitle());
                            } else {
                                $song = $dbSong;

                                $output->writeln('Existing song: ' . $song->getTitle());
                            }

                            // Song and Album relation
                            if (!$song->getAlbums()->contains($album)) {
                                $song->addAlbum($album);
                            }

                            // Song and Musician relation
                            if (!$song->getMusicians()->contains($musician)) {
                                $song->addMusician($musician);
                            }
                        }

                        $this->em->flush();
                    }/* else {
                        // make single (track) logic
                    }*/
                }

                $this->em->flush();
            }
        }

        $this->em->flush();

        return Command::SUCCESS;
    }

    private function extractLinksFromRelations(array $relations, ?array $spotifyArtist): array
    {
        $links = [];

        foreach ($relations as $relation) {
            if (!empty($relation['url']['resource'])) {
                $links[] = [
                    'type' => $relation['type'] ?? 'other',
                    'url' => $relation['url']['resource']
                ];
            }
        }

        $extractedLinks = [];

        foreach ($links as $link) {
            $extractedLinks = array_merge($extractedLinks, [
                (string)$link['type'] => $link['url']
            ]);
        }

        if ($spotifyArtist && $spotifyArtist['external_urls']) {
            $extractedLinks = array_merge($extractedLinks, [
                'spotify' => $spotifyArtist['external_urls']['spotify']
            ]);
        }

        return $extractedLinks;
    }

    private function getReleaseGroupsWithRetry(string $releaseId, int $maxRetries = 3, int $delaySeconds = 5): ?array
    {
        $attempt = 0;
        do {
            try {
                return $this->musicBrainzClient->getReleaseGroups($releaseId);
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt >= $maxRetries) {
                    throw $e;
                }
                sleep($delaySeconds);
            }
        } while ($attempt < $maxRetries);
        return null;
    }

    private function getReleaseGroupDetailsWithRetry(string $releaseId, string $inc, int $maxRetries = 3, int $delaySeconds = 5): ?array
    {
        $attempt = 0;
        do {
            try {
                return $this->musicBrainzClient->getReleaseGroupDetails($releaseId, $inc);
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt >= $maxRetries) {
                    throw $e;
                }
                sleep($delaySeconds);
            }
        } while ($attempt < $maxRetries);
        return null;
    }

    private function getReleaseRecordingsWithRetry(string $releaseId, string $inc, int $maxRetries = 3, int $delaySeconds = 5): ?array
    {
        $attempt = 0;
        do {
            try {
                return $this->musicBrainzClient->getReleaseRecordings($releaseId, $inc);
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt >= $maxRetries) {
                    throw $e;
                }
                sleep($delaySeconds);
            }
        } while ($attempt < $maxRetries);
        return null;
    }

    private function getReleaseRecordingDetailsWithRetry(string $releaseId, string $inc, int $maxRetries = 3, int $delaySeconds = 5): ?array
    {
        $attempt = 0;
        do {
            try {
                return $this->musicBrainzClient->getReleaseRecordingDetails($releaseId, $inc);
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt >= $maxRetries) {
                    throw $e;
                }
                sleep($delaySeconds);
            }
        } while ($attempt < $maxRetries);
        return null;
    }

    private function getArtistDetailsWithRetry(string $mbid, int $limit, string $inc, int $maxRetries = 3, int $delaySeconds = 5): ?array
    {
        $attempt = 0;
        do {
            try {
                return $this->musicBrainzClient->getArtistDetails($mbid, $limit, $inc);
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt >= $maxRetries) {
                    throw $e;
                }
                sleep($delaySeconds);
            }
        } while ($attempt < $maxRetries);
        return null;
    }
}
