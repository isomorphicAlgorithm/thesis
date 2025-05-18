<?php

namespace App\Command;

use App\Entity\Album;
use App\Service\Client\LastFmClient;
use App\Service\Client\MusicBrainzClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportArtistCommand extends Command
{
    // static name is good fallback but constructor explicit is safer
    protected static $defaultName = 'bandito:import-artist';

    public function __construct(
        private MusicBrainzClient $musicBrainzClient,
        private LastFmClient $lastFmClient,
        private EntityManagerInterface $em
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
        $artistName = $input->getArgument('artistName');

        $artists = $this->musicBrainzClient->searchArtist($artistName);

        if (empty($artists)) {
            $output->writeln('No artists found.');
            return Command::FAILURE;
        }

        $artist = $artists[0];
        $output->writeln('Found artist: ' . $artist['name'] . ' (MBID: ' . $artist['id'] . ')');

        $details = $this->musicBrainzClient->getArtistDetails($artist['id']);

        $output->writeln('Releases:');
        if (!empty($details['releases'])) {
            foreach ($details['releases'] as $releaseData) {
                $album = new Album();
                $album->setTitle($releaseData['title']);
                $album->setMusicBrainzId($releaseData['id']);

                if (!empty($releaseData['date'])) {
                    try {
                        $album->setReleaseDate(new \DateTimeImmutable($releaseData['date']));
                    } catch (\Exception) {
                        // ignore
                    }
                }

                // 🔥 Get Last.fm info for this album
                $lastfmAlbum = $this->lastFmClient->getAlbumInfo($releaseData['id']);

                if ($lastfmAlbum && !empty($lastfmAlbum['image'])) {
                    // Last.fm gives multiple image sizes — get the largest
                    $images = array_column($lastfmAlbum['image'], '#text', 'size');
                    $coverUrl = $images['extralarge'] ?? $images['large'] ?? null;
                    if ($coverUrl) {
                        $album->setCoverImage($coverUrl); // You’ll need to add this property
                    }
                }

                $album->setBand($band);
                $this->em->persist($album);
            }
        } else {
            $output->writeln('No releases found.');
        }

        return Command::SUCCESS;
    }
}
