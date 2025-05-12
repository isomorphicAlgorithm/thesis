<?php

// src/Controller/InlineEditController.php
namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use App\Repository\BandRepository;
use App\Repository\MusicianRepository;
use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InlineEditController extends AbstractController
{
    #[Route('/inline-edit', name: 'inline_edit', methods: ['POST'])]
    public function inlineEdit(Request $request, EntityManagerInterface $em, UserRepository $userRepo, AlbumRepository $albumRepo, BandRepository $bandRepo, MusicianRepository $musicianRepo, SongRepository $songRepo ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $entityType = $data['entityType'] ?? null;
        $entityId = $data['entityId'] ?? null;
        $field = $data['field'] ?? null;
        $value = $data['value'] ?? null;

        if (!$entityType || !$entityId || !$field) {
            return $this->json(['error' => 'Invalid input'], 400);
        }

        $repositories = [
            'user' => $userRepo,
            'album' => $albumRepo,
            'band' => $bandRepo,
            'musician' => $musicianRepo,
            'song' => $songRepo,
        ];

        if (!isset($repositories[$entityType])) {
            return $this->json(['error' => 'Unknown entity type'], 400);
        }

        $entity = $repositories[$entityType]->find($entityId);
        if (!$entity) {
            return $this->json(['error' => 'Entity not found'], 404);
        }

        // ✅ Authorization Logic
        $currentUser = $this->getUser();
        $isOwner = match ($entityType) {
            'user' => $entity === $currentUser,
            'album', 'band', 'musician', 'song' =>
                method_exists($entity, 'getOwner') && $entity->getOwner() === $currentUser,
            default => false,
        };

        if (!$isOwner && !$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Unauthorized'], 403);
        }

        // Set field dynamically
        $setter = 'set' . ucfirst($field);
        if (method_exists($entity, $setter)) {
            $entity->$setter($value);
        } else {
            return $this->json(['error' => 'Field not writable'], 400);
        }

        $em->flush();

        return $this->json(['success' => true]);
    }
}