<?php

namespace App\Controller;

use App\Entity\Song;
use App\Form\MusicianType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/songs')]
class SongController extends AbstractController
{
    #[Route('/{id}-{slug}', name: 'song_show')]
    public function show(Song $song, string  $slug): Response
    {
        $expectedSlug = $song->getSlug();

        if ($slug != $expectedSlug) {
            return $this->redirectToRoute('song_show', [
                'id' => $song->getId(),
                'slug' => $expectedSlug,
            ]);
        }

        return $this->render('song/show.html.twig', [
            'song' => $song,
        ]);
    }
}
