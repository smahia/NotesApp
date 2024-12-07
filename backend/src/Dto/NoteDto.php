<?php

namespace App\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class NoteDto
{
    #[OA\Property(type: 'int', default: 1, nullable: false)]
    private ?int $id;
    #[OA\Property(type: 'string', default: 'Title', maxLength: 255, nullable: true)]
    private ?string $title;

    #[Assert\NotBlank(message: 'The content of the note can not be empty.')]
    #[OA\Property(type: 'string', default: 'Content', maxLength: 255, nullable: false)]
    private ?string $content;

    #[OA\Property(type: 'Date', default: '2024-12-01T00:00:00+00:00', nullable: false)]
    private ?\DateTimeInterface $creationDate;

    #[OA\Property(type: 'string', default: 'Tag', maxLength: 255, nullable: true)]
    private ?string $tag;


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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): void
    {
        $this->creationDate = $creationDate;
    }


}