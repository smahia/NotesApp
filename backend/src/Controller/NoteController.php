<?php

namespace App\Controller;

use App\Dto\CreateDto\CreateNoteDto;
use App\Dto\NoteDto;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Notes', description: 'Manage Notes Endpoints')]
#[Route('/api')]
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
    /** Creates a Note resource.
     * @param Request $request
     * @return Response
     */
    #[Rest\Post('/note', name: 'app_create_note')]
    #[OA\Response(
        response: 200,
        description: 'Successfull operation.',
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

    /** Get all Notes resource.
     * @return Response
     */
    #[Rest\Get('/note', name: 'app_get_notes')]
    #[OA\Response(
        response: 200,
        description: 'Sucessful operation.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: NoteDto::class))
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: NoteDto::class))
        )
    )]
    public function getNotes(): Response
    {
        $notes = $this->entityManager->getRepository(Note::class)->findAll();
        $view = $this->view($notes, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /** Get a Note resource.
     * @param int $id The ID of the Note to get.
     * @return Response
     */
    #[Rest\Get('/note/{id}', name: 'app_get_note')]
    #[OA\Response(
        response: 200,
        description: 'Sucessful operation.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: NoteDto::class))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Note not found.',
    )]
    public function getNote(int $id): Response
    {
        $note = $this->entityManager->getRepository(Note::class)->find($id);
        if($note != null) {
            $view = $this->view($note, Response::HTTP_OK);
        } else {
            $view = $this->view(null, Response::HTTP_NOT_FOUND);
        }
        return $this->handleView($view);
    }

    /** Update a Note resource.
     * @param int $id The ID of the Note to update.
     * @param Request $request
     * @return Response
     */
    #[Rest\Put('/note/{id}', name: 'app_update_note')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: CreateNoteDto::class))
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Sucessful operation.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: NoteDto::class))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Note not found.',
    )]
    public function updateNote(int $id, Request $request): Response
    {
        $note = $this->entityManager->getRepository(Note::class)->find($id);
        if($note != null) {

            $data = json_decode($request->getContent(), true);
            $note->setTitle($data['title']);
            $note->setContent($data['content']);
            $note->setTag($data['tag']);

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
                $view = $this->view($note, Response::HTTP_OK);
                return $this->handleView($view);
            }
        } else {
            $view = $this->view(null, Response::HTTP_NOT_FOUND);
        }
        return $this->handleView($view);
    }

    /** Delete a Note resource.
     * @param int $id The ID of the Note to delete.
     * @return Response
     */
    #[Rest\Delete('/note/{id}', name: 'app_delete_note')]
    #[OA\Response(
        response: 200,
        description: 'Sucessful operation.',
    )]
    #[OA\Response(
        response: 404,
        description: 'Note not found.',
    )]
    public function deleteNote(int $id): Response
    {
        $note = $this->entityManager->getRepository(Note::class)->find($id);
        if($note != null) {
            $this->entityManager->remove($note);
            $this->entityManager->flush();
            $view = $this->view(null, Response::HTTP_NO_CONTENT);
        } else {
            $view = $this->view(null, Response::HTTP_NOT_FOUND);
        }
        return $this->handleView($view);
    }
}
