<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Musician;
use App\Entity\Album;
use App\Entity\Song;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/autocomplete', name: 'autocomplete', methods: ['GET'])]
    public function autocomplete(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $q = trim($request->query->get('q', ''));
        $results = [];

        if (strlen($q) >= 2) {
            foreach (['Band', 'Musician', 'Album', 'Song'] as $entity) {
                $class = "App\\Entity\\$entity";
                $repo = $em->getRepository($class);

                // Decide search field dynamically
                $field = method_exists($class, 'getTitle') ? 'title' : 'name';

                $qb = $repo->createQueryBuilder('e')
                    ->where("LOWER(e.$field) LIKE :q")
                    ->setParameter('q', '%' . strtolower($q) . '%')
                    ->setMaxResults(5);

                $items = $qb->getQuery()->getResult();

                foreach ($items as $item) {
                    $results[] = [
                        'type' => $entity,
                        'id' => $item->getId(),
                        'name' => method_exists($item, 'getTitle') ? $item->getTitle() : $item->getName(),
                        'slug' => method_exists($item, 'getSlug') ? $item->getSlug() : null,
                        'cover' => method_exists($item, 'getCoverImage') ? $item->getCoverImage() : null,
                    ];
                }
            }
        }

        return $this->json($results);
    }
}