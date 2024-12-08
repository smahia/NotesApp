<?php

namespace App\Service;

use App\Dto\CreateDto\CreateNoteDto;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;

class NoteService
{
    private EntityManagerInterface $entityManager;
    private ValidatorService $validatorService;

    public function __construct(EntityManagerInterface $entityManager, ValidatorService $validatorService)
    {
        $this->entityManager = $entityManager;
        $this->validatorService = $validatorService;
    }

    public function createNote(CreateNoteDto $createNoteDto): Note|array
    {
        $note = new Note();
        $note->setTitle($createNoteDto->getTitle());
        $note->setContent($createNoteDto->getContent());
        $note->setTag($createNoteDto->getTag());

        if($this->validatorService->validate($note) !== []) {
            return $this->validatorService->validate($note);
        }

        $this->entityManager->persist($note);
        $this->entityManager->flush();
        return $note;
    }
}