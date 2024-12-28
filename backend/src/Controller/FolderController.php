<?php

namespace App\Controller;

use App\Dto\CreateDto\CreateFolderDto;
use App\Dto\CreateDto\CreateNoteDto;
use App\Dto\FolderDto;
use App\Dto\NoteDto;
use App\Entity\Folder;
use App\Error\ApiError;
use App\Service\FolderService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Folders', description: 'Manage Folders Endpoints')]
#[Route('/api')]
class FolderController extends AbstractFOSRestController
{

    private FolderService $folderService;

    /**
     * @param FolderService $folderService
     */
    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    /** Get a Folder resource.
     * @param int $id The ID of the Folder to get.
     * @return Response
     */
    #[Rest\Get('/folder/{id}', name: 'app_get_folder')]
    #[OA\Response(
        response: 200,
        description: 'Sucessful operation.',
        content: new OA\JsonContent(
            ref: new Model(type: FolderDto::class),
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Folder not found.',
        content: new OA\JsonContent(
            ref: new Model(type: ApiError::class),
        )
    )]
    public function getFolder(int $id): Response
    {
        $result = $this->folderService->getFolder($id);
        if($result instanceof Folder) {
            // Convert Folder into FolderDTO
            $newFolderDto = new FolderDto();
            $newFolderDto->setId($result->getId());
            $newFolderDto->setName($result->getName());
            $notesCollection = $result->getNotes();
            foreach ($notesCollection as $note) {
                $newFolderDto->getNotesIds()->add($note->getId());
            }
            $view = $this->view($newFolderDto, Response::HTTP_OK);
        } else {
            $view = $this->view($result, Response::HTTP_NOT_FOUND);
        }
        return $this->handleView($view);

    }

    /** Get all Folders resources.
     * @return Response
     */
    #[Rest\Get('/folder', name: 'app_get_folders')]
    #[OA\Response(
        response: 200,
        description: 'Sucessful operation.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: FolderDto::class))
        )
    )]
    public function getFolders(): Response {
        $folders = $this->folderService->getFolders();
        // Convert Folder into FolderDTO
        $foldersArray = array();
        foreach ($folders as $folder) {
            $newFolderDto = new FolderDto();
            $newFolderDto->setId($folder->getId());
            $newFolderDto->setName($folder->getName());
            $notesCollection = $folder->getNotes();
            foreach ($notesCollection as $note) {
                $newFolderDto->getNotesIds()->add($note->getId());
            }
            $foldersArray[] = $newFolderDto;
        }
        $view = $this->view($foldersArray, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /** Create a Folder resource.
     * @param Request $request
     * @return Response
     */
    #[Rest\Post('/folder', name: 'app_create_folder')]
    #[OA\Response(
        response: 200,
        description: 'Successfull operation.',
        content: new OA\JsonContent(
            ref: new Model(type: FolderDto::class),
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation fails.',
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: CreateFolderDto::class),
        )
    )]
    public function createFolder(Request $request): Response {
        $createFolderDto = new CreateFolderDto(
          $request->request->get('name')
        );

        $result = $this->folderService->createFolder($createFolderDto);

        if($result instanceof Folder) {
            // Convert Folder into FolderDto
            $newFolderDto = new FolderDto();
            $newFolderDto->setId($result->getId());
            $newFolderDto->setName($result->getName());
            $view = $this->view($result, Response::HTTP_CREATED);
        } else {
            $view = $this->view($result, Response::HTTP_BAD_REQUEST);
        }
        return $this->handleView($view);
    }

    /** Update a Folder resource.
     * @param int $id The ID of the Folder to update.
     * @param Request $request
     * @return Response
     */
    #[Rest\Put('/folder/{id}', name: 'app_update_folder')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: CreateFolderDto::class),
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Sucessful operation.',
        content: new OA\JsonContent(
            ref: new Model(type: FolderDto::class),
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Folder not found.',
        content: new OA\JsonContent(
            ref: new Model(type: ApiError::class),
        )
    )]
    public function updateFolder(int $id, Request $request): Response {
        $createFolderDto = new CreateFolderDto(
            $request->request->get('name')
        );
        $result = $this->folderService->updateFolder($id, $createFolderDto);
        if($result instanceof Folder) {
            // Convert Folder into FolderDto
            $newFolderDto = new FolderDto();
            $newFolderDto->setId($result->getId());
            $newFolderDto->setName($result->getName());
            $notesCollection = $result->getNotes();
            foreach ($notesCollection as $note) {
                $newFolderDto->getNotesIds()->add($note->getId());
            }
            $view = $this->view($newFolderDto, Response::HTTP_OK);
        } else if($result instanceof ApiError) {
            $view = $this->view($result, Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->view($result, Response::HTTP_BAD_REQUEST);
        }
        return $this->handleView($view);
    }

    /** Delete a Folder resource.
     * @param int $id The ID of the Folder to delete.
     * @return Response
     */
    #[Rest\Delete('/folder/{id}', name: 'app_delete_folder')]
    #[OA\Response(
        response: 200,
        description: 'Sucessful operation.',
    )]
    #[OA\Response(
        response: 404,
        description: 'Folder not found.',
        content: new OA\JsonContent(
            ref: new Model(type: ApiError::class),
        )
    )]
    public function deleteFolder(int $id): Response {
        $result = $this->folderService->deleteFolder($id);
        if($result == null) {
            $view = $this->view(null, Response::HTTP_NO_CONTENT);
        } else if($result instanceof ApiError && $result->getCode() == Response::HTTP_NOT_FOUND) {
            $view = $this->view($result, Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->view($result, Response::HTTP_BAD_REQUEST);
        }
        return $this->handleView($view);
    }
}
