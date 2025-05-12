<?php

namespace App\Controller;

use App\Entity\Band;
use App\Form\BandType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/bands')]
class BandController extends AbstractController
{
    #[Route('/{id}-{name}', name: 'band_show', methods: ['GET'])]
    public function show(Band $band, string $name, SluggerInterface $slugger): Response
    {
        $expectedName = $slugger->slug($band->getName())->lower();

        if ($name != $expectedName) {
            return $this->redirectToRoute('band_show', [
                'id' => $band->getId(),
                'name' => $expectedName,
            ]);
        }

        return $this->render('band/show.html.twig', [
            'band' => $band,
        ]);
    }

    #[Route('/{id}/edit', name: 'band_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Band $band, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Band updated successfully.');
            return $this->redirectToRoute('band_show', ['id' => $band->getId()]);
        }

        return $this->render('band/edit.html.twig', [
            'band' => $band,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'band_delete', methods: ['POST'])]
    public function delete(Request $request, Band $band, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $band->getId(), $request->request->get('_token'))) {
            $em->remove($band);
            $em->flush();
            $this->addFlash('success', 'Band deleted.');
        }

        return $this->redirectToRoute('homepage'); // or wherever you want to go after
    }
}
