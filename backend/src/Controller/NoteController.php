<?php

namespace App\Controller;

use App\Dto\CreateNoteDto;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Notes', description: 'Manage Notes Endpoints')]
class NoteController extends AbstractFOSRestController
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager,
                                ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    // https://www.binaryboxtuts.com/php-tutorials/how-to-make-symfony-7-rest-api/
    // https://phpenterprisesystems.com/symfony-framework/70-using-dtos-in-php-symfony
    // https://guiaphp.com/integracion/crud-con-apis-rest-en-symfony-guia-paso-a-paso/
    #[Rest\Post('api/note', name: 'app_create_note')]
    #[OA\Response(
        response: 200,
        description: 'Creates a Note resource.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: CreateNoteDto::class))
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation fails.',
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: CreateNoteDto::class))
        )
    )]
    public function createNote(Request $request): Response
    {

        $note = new Note();
        $data = json_decode($request->getContent(), true);
        $note->setTitle($data['title']);
        $note->setContent($data['content']);
        $note->setTag($data['tag']);
        $note->setCreationDate(new \DateTime());

        $errors = $this->validator->validate($note);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'message' => $error->getMessage()
                ];
            }

            $jsonErrorMessage = json_encode($errorMessages, JSON_PRETTY_PRINT);
            return new Response($jsonErrorMessage, Response::HTTP_BAD_REQUEST);
        } else {
            $this->entityManager->persist($note);
            $this->entityManager->flush();
            $view = $this->view($data, Response::HTTP_CREATED);
            return $this->handleView($view);
        }
    }

    #[Rest\Get('api/note', name: 'app_get_notes')]
    public function getNotes(): Response
    {

    }
}
