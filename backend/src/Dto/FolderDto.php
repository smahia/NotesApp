<?php

namespace App\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use OpenApi\Attributes as OA;

class FolderDto {
    #[OA\Property(type: 'int', default: 1, nullable: false)]
    private ?int $id;

    #[OA\Property(type: 'string', default: 'Name', maxLength: 255, nullable: false)]
    private ?string $name;

    #[OA\Property(type: 'collection', default: [1, 2], nullable: true)]
    private ?Collection $notesIds;

    public function __construct()
    {
        // https://stackoverflow.com/questions/59265625/why-i-am-suddenly-getting-a-typed-property-must-not-be-accessed-before-initiali
        $this->notesIds = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getNotesIds(): ?Collection
    {
        return $this->notesIds;
    }

    public function setNotesIds(?Collection $notesIds): void
    {
        $this->notesIds = $notesIds;
    }

}