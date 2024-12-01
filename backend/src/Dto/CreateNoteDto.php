<?php

namespace App\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class CreateNoteDto
{
    #[OA\Property(type: 'string', default: 'Title', maxLength: 255, nullable: true)]
    private ?string $title = '';

    #[Assert\NotBlank(message: 'The content of the note can not be empty.')]
    #[OA\Property(type: 'string', default: 'Content', maxLength: 255, nullable: false)]
    private ?string $content = '';

    #[OA\Property(type: 'string', default: 'Tag', maxLength: 255, nullable: true)]
    private ?string $tag = '';


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


}