<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\MusicianType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/albums')]
class AlbumController extends AbstractController
{
    #[Route('/{id}-{slug}', name: 'album_show')]
    public function show(Album $album, string  $slug): Response
    {
        $expectedSlug = $album->getSlug();

        if ($slug != $expectedSlug) {
            return $this->redirectToRoute('album_show', [
                'id' => $album->getId(),
                'slug' => $expectedSlug,
            ]);
        }

        return $this->render('album/show.html.twig', [
            'album' => $album,
        ]);
    }
}
