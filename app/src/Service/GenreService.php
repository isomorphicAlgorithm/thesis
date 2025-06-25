<?php

namespace App\Service;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GenreRepository;
use App\Service\Client\AbstractClient;

class GenreService extends AbstractClient
{
    private const BLACKLIST = [
        'seen live',
        'favorites',
        'favorite',
        'beautiful',
        'awesome',
        'all',
        '00s',
        '90s',
        '80s',
        'male vocalists',
        'female vocalists',
        'fun',
        'cool',
        'funny',
        'under 2000 listeners',
    ];

    public function __construct(
        private EntityManagerInterface $em,
        private GenreRepository $genreRepo
    ) {}

    /**
     * @param string[] $names
     * @return Genre[]
     */
    public function getOrCreateGenres(array $names): array
    {
        $result = [];
        $seen = [];

        // Normalize and filter first
        $normalizedNames = array_unique(array_filter(array_map(function ($name) {
            $normalized = strtolower(trim($name));
            return $this->isValidGenre($normalized) ? $normalized : null;
        }, $names)));

        // Load all matching genres at once
        $existingGenres = $this->genreRepo->findBy(['name' => $normalizedNames]);
        $existingByName = [];

        foreach ($existingGenres as $genre) {
            $existingByName[$genre->getName()] = $genre;
        }

        foreach ($normalizedNames as $normalized) {
            if (isset($seen[$normalized])) {
                continue;
            }
            $seen[$normalized] = true;

            if (isset($existingByName[$normalized])) {
                $result[] = $existingByName[$normalized];
            } else {
                $genre = new Genre($normalized);
                $this->em->persist($genre);
                $result[] = $genre;
            }
        }

        return $result;
    }

    private function isValidGenre(string $name): bool
    {
        $normalized = strtolower(trim($name));
        return !in_array($normalized, self::BLACKLIST, true);
    }

    public function createImmutableDate(string $date): ?\DateTimeImmutable
    {
        try {
            // Pad year-only or year-month to full date
            if (preg_match('/^\d{4}$/', $date)) {
                $date .= '-01-01';
            } elseif (preg_match('/^\d{4}-\d{2}$/', $date)) {
                $date .= '-01';
            }

            return new \DateTimeImmutable($date);
        } catch (\Exception) {
            return null;
        }
    }
}
