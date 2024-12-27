<?php

namespace App\Service;

use App\Dto\CreateDto\CreateNoteDto;
use App\Entity\Folder;
use App\Entity\Note;
use App\Error\ApiError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class NoteService
{
    private EntityManagerInterface $entityManager;
    private ValidatorService $validatorService;

    public function __construct(EntityManagerInterface $entityManager, ValidatorService $validatorService)
    {
        $this->entityManager = $entityManager;
        $this->validatorService = $validatorService;
    }

    /**
     * @param CreateNoteDto $createNoteDto
     * @return Note|array|ApiError Return an array of error messages if validation fails,
     * ApiError if the Folder is not found, or the Note entity
     */
    public function createNote(CreateNoteDto $createNoteDto): Note|array|ApiError
    {
        $note = new Note();
        $note->setTitle($createNoteDto->getTitle());
        $note->setContent($createNoteDto->getContent());
        $note->setTag($createNoteDto->getTag());

        if ($createNoteDto->getFolderId() != null) {
            $folder = $this->entityManager->getRepository(Folder::class)->find($createNoteDto->getFolderId());
            if ($folder != null) {
                $note->setFolder($folder);
            } else {
                return new ApiError(Response::HTTP_NOT_FOUND, "Folder not found.");
            }
        } else {
            $folder = $this->entityManager->getRepository(Folder::class)->findOneBy(['id' => 1]); // Default folder id
            $note->setFolder($folder);
        }

        if ($this->validatorService->validate($note) !== []) {
            return $this->validatorService->validate($note);
        }

        $this->entityManager->persist($note);
        $this->entityManager->flush();
        return $note;
    }

    public function getNotes(): array
    {
        return $this->entityManager->getRepository(Note::class)->findAll();
    }

    /**
     * @param int $id
     * @return Note|ApiError Return the Note entity if found, otherwise return an ApiError
     */
    public function getNote(int $id): Note|ApiError
    {
        $note = $this->entityManager->getRepository(Note::class)->find($id);
        if ($note != null) {
            return $note;
        } else {
            return new ApiError(Response::HTTP_NOT_FOUND, "Note not found.");
        }
    }

    /**
     * @param int $id
     * @param CreateNoteDto $createNoteDto
     * @return Note|ApiError|array Return an array of error messages if validation fails,
     * an ApiError if the Note or the Folder is not found, or the Note entity.
     */
    public function updateNote(int $id, CreateNoteDto $createNoteDto): Note|ApiError|array
    {
        $note = $this->entityManager->getRepository(Note::class)->find($id);
        if ($note != null) {

            $note->setTitle($createNoteDto->getTitle());
            $note->setContent($createNoteDto->getContent());
            $note->setTag($createNoteDto->getTag());

            if ($createNoteDto->getFolderId() != null) {
                $folder = $this->entityManager->getRepository(Folder::class)->find($createNoteDto->getFolderId());
                if ($folder != null) {
                    $note->setFolder($folder);
                } else {
                    return new ApiError(Response::HTTP_NOT_FOUND, "Folder not found.");
                }
            } else {
                $folder = $this->entityManager->getRepository(Folder::class)->findOneBy(['id' => 1]); // Default folder id
                $note->setFolder($folder);
            }

            if ($this->validatorService->validate($note) !== []) {
                return $this->validatorService->validate($note);
            } else {
                $this->entityManager->persist($note);
                $this->entityManager->flush();
                return $note;
            }
        } else {
            return new ApiError(Response::HTTP_NOT_FOUND, "Note not found.");
        }
    }

    /**
     * @param int $id
     * @return ApiError|null Return null if the Note is deleted, otherwise return an ApiError
     */
    public function deleteNote(int $id): null|ApiError
    {
        $note = $this->entityManager->getRepository(Note::class)->find($id);
        if ($note != null) {
            $this->entityManager->remove($note);
            $this->entityManager->flush();
            return null;
        } else {
            return new ApiError(Response::HTTP_NOT_FOUND, "Note not found.");
        }
    }
}