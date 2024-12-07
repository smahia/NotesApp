<?php

namespace App\Service;

use App\Dto\CreateDto\CreateNoteDto;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NoteService
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager,
                                ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function createNote(CreateNoteDto $createNoteDto): Note|array
    {
        $note = new Note();
        $note->setTitle($createNoteDto->getTitle());
        $note->setContent($createNoteDto->getContent());
        $note->setTag($createNoteDto->getTag());

        $errors = $this->validator->validate($note);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'message' => $error->getMessage()
                ];
            }
           return $errorMessages;
        }

        $this->entityManager->persist($note);
        $this->entityManager->flush();

        return $note;
    }
}