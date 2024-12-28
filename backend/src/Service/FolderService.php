<?php

namespace App\Service;

use App\Entity\Folder;
use App\Error\ApiError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class FolderService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return Folder|ApiError Return the Folder entity if found, otherwise return an ApiError
     */
    public function getFolder(int $id): Folder|ApiError
    {
        $folder = $this->entityManager->getRepository(Folder::class)->find($id);
        if ($folder != null) {
            return $folder;
        } else {
            return new ApiError(Response::HTTP_NOT_FOUND, "Folder not found.");
        }
    }
}