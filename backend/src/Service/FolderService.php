<?php

namespace App\Service;

use App\Dto\CreateDto\CreateFolderDto;
use App\Entity\Folder;
use App\Error\ApiError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class FolderService
{
    private EntityManagerInterface $entityManager;
    private ValidatorService $validatorService;

    public function __construct(EntityManagerInterface $entityManager, ValidatorService $validatorService)
    {
        $this->entityManager = $entityManager;
        $this->validatorService = $validatorService;
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

    public function getFolders(): array
    {
        return $this->entityManager->getRepository(Folder::class)->findAll();
    }

    /**
     * @param CreateFolderDto $createFolderDto
     * @return Folder|array Return an array of error messages if validation fails, otherwise return the Folder entity
     */
    public function createFolder(CreateFolderDto $createFolderDto): Folder|array
    {
        $newFolder = new Folder();
        $newFolder->setName($createFolderDto->getName());
        if ($this->validatorService->validate($newFolder) !== []) {
            return $this->validatorService->validate($newFolder);
        }
        $this->entityManager->persist($newFolder);
        $this->entityManager->flush();
        return $newFolder;
    }

    /**
     * @param int $id
     * @param CreateFolderDto $createFolderDto
     * @return Folder|ApiError|array Return an array of error messages if validation fails, an ApiError if the Folder is not found, or the Folder entity.
     */
    public function updateFolder(int $id, CreateFolderDto $createFolderDto): Folder|ApiError|array {
        $folder = $this->entityManager->getRepository(Folder::class)->find($id);
        if ($folder != null) {
            $folder->setName($createFolderDto->getName());
            if ($this->validatorService->validate($folder) !== []) {
                return $this->validatorService->validate($folder);
            }
            $this->entityManager->persist($folder);
            $this->entityManager->flush();
            return $folder;
        } else {
            return new ApiError(Response::HTTP_NOT_FOUND, "Folder not found.");
        }
    }

    /**
     * @param int $id
     * @return ApiError|null Return null if the Note is deleted, otherwise return an ApiError
     */
    public function deleteFolder(int $id): null|ApiError
    {
        $folder = $this->entityManager->getRepository(Folder::class)->find($id);
        if ($folder != null) {
            // Default folder can not be deleted
            if($folder->getId() === 1) {
                return new ApiError(Response::HTTP_BAD_REQUEST, "Default folder can not be deleted.");
            } else {
                $this->entityManager->remove($folder);
                $this->entityManager->flush();
                return null;
            }
        } else {
            return new ApiError(Response::HTTP_NOT_FOUND, "Folder not found.");
        }
    }
}