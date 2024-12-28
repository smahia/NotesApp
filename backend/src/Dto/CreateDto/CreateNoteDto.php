<?php

namespace App\Dto\CreateDto;

use OpenApi\Attributes as OA;

class CreateNoteDto
{
    #[OA\Property(type: 'string', default: 'Title', maxLength: 255, nullable: true)]
    private ?string $title;

    #[OA\Property(type: 'string', default: 'Content', maxLength: 255, nullable: false)]
    private ?string $content;

    #[OA\Property(type: 'string', default: 'Tag', maxLength: 255, nullable: true)]
    private ?string $tag;

    #[OA\Property(type: 'int', default: 1, nullable: false)]
    private ?int $folderId;

    /**
     * @param string|null $title
     * @param string|null $content
     * @param int|null $folderId
     * @param string|null $tag
     */
    public function __construct(?string $title, ?string $content, ?int $folderId, ?string $tag)
    {
        $this->title = $title;
        $this->content = $content;
        $this->folderId = $folderId;
        $this->tag = $tag;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): void
    {
        $this->tag = $tag;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getFolderId(): ?int
    {
        return $this->folderId;
    }

    public function setFolderId(?int $folderId): void
    {
        $this->folderId = $folderId;
    }
}