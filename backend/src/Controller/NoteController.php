<?php

namespace App\Controller;

use App\Dto\CreateDto\CreateNoteDto;
use App\Dto\NoteDto;
use App\Entity\Note;
use App\Error\ApiError;
use App\Service\NoteService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Notes', description: 'Manage Notes Endpoints')]
#[Route('/api')]
class NoteController extends AbstractFOSRestController
{
    private NoteService $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
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
            ref: new Model(type: NoteDto::class),
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation fails.',
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: CreateNoteDto::class),
        )
    )]
    public function createNote(Request $request): Response
    {

        $createNoteDto = new CreateNoteDto(
            $request->request->get('title'),
            $request->request->get('content'),
            $request->request->get('folderId'),
            $request->request->get('tag')
        );

        $result = $this->noteService->createNote($createNoteDto);

        if ($result instanceof Note) {
            // Convert Note into NoteDTO
            $newNoteDto = new NoteDto();
            $newNoteDto->setId($result->getId());
            $newNoteDto->setTitle($result->getTitle());
            $newNoteDto->setContent($result->getContent());
            $newNoteDto->setCreationDate($result->getCreationDate());
            $newNoteDto->setFolderId($result->getFolder()->getId());
            $newNoteDto->setTag($result->getTag());
            $view = $this->view($newNoteDto, Response::HTTP_CREATED);
        } else {
            $view = $this->view($result, Response::HTTP_BAD_REQUEST);
        }
        return $this->handleView($view);

    }

    /** Get all Notes resources.
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
    public function getNotes(): Response
    {
        $notes = $this->noteService->getNotes();
        // Convert Note into NoteDTO
        $notesArray = array();
        foreach ($notes as $note) {
            $newNoteDto = new NoteDto();
            $newNoteDto->setId($note->getId());
            $newNoteDto->setTitle($note->getTitle());
            $newNoteDto->setContent($note->getContent());
            $newNoteDto->setCreationDate($note->getCreationDate());
            $newNoteDto->setFolderId($note->getFolder()->getId());
            $newNoteDto->setTag($note->getTag());
            $notesArray[] = $newNoteDto;
        }
        $view = $this->view($notesArray, Response::HTTP_OK);
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
            ref: new Model(type: NoteDto::class),
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Note not found.',
        content: new OA\JsonContent(
            ref: new Model(type: ApiError::class),
        )
    )]
    public function getNote(int $id): Response
    {
        $result = $this->noteService->getNote($id);
        if($result instanceof Note) {
            // Convert Note into NoteDTO
            $newNoteDto = new NoteDto();
            $newNoteDto->setId($result->getId());
            $newNoteDto->setTitle($result->getTitle());
            $newNoteDto->setContent($result->getContent());
            $newNoteDto->setCreationDate($result->getCreationDate());
            $newNoteDto->setFolderId($result->getFolder()->getId());
            $newNoteDto->setTag($result->getTag());
            $view = $this->view($newNoteDto, Response::HTTP_OK);
        } else {
            $view = $this->view($result, Response::HTTP_NOT_FOUND);
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
            ref: new Model(type: CreateNoteDto::class),
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Sucessful operation.',
        content: new OA\JsonContent(
            ref: new Model(type: NoteDto::class),
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Note not found.',
        content: new OA\JsonContent(
            ref: new Model(type: ApiError::class),
        )
    )]
    public function updateNote(int $id, Request $request): Response
    {
        $createNoteDto = new CreateNoteDto(
            $request->request->get('title'),
            $request->request->get('content'),
            $request->request->get('folderId'),
            $request->request->get('tag')
        );

        $result = $this->noteService->updateNote($id, $createNoteDto);
        if($result instanceof Note) {
            // Convert Note into NoteDTO
            $newNoteDto = new NoteDto();
            $newNoteDto->setId($result->getId());
            $newNoteDto->setTitle($result->getTitle());
            $newNoteDto->setContent($result->getContent());
            $newNoteDto->setCreationDate($result->getCreationDate());
            $newNoteDto->setFolderId($result->getFolder()->getId());
            $newNoteDto->setTag($result->getTag());
            $view = $this->view($newNoteDto, Response::HTTP_OK);
        } elseif ($result instanceof ApiError) {
            $view = $this->view($result, Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->view($result, Response::HTTP_BAD_REQUEST);
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
        content: new OA\JsonContent(
            ref: new Model(type: ApiError::class),
        )
    )]
    public function deleteNote(int $id): Response
    {
        if($this->noteService->deleteNote($id) == null) {
            $view = $this->view(null, Response::HTTP_NO_CONTENT);
        } else {
            $view = $this->view($this->noteService->deleteNote($id), Response::HTTP_NOT_FOUND);
        }
        return $this->handleView($view);
    }
}
