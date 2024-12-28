<?php

namespace App\Controller;

use App\Dto\FolderDto;
use App\Entity\Folder;
use App\Error\ApiError;
use App\Service\FolderService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
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
}
