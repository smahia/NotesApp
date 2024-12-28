<?php

namespace App\Dto\CreateDto;

use OpenApi\Attributes as OA;

class CreateFolderDto
{
    #[OA\Property(type: 'string', default: 'Name', maxLength: 255, nullable: false)]
    private ?string $name;

    /**
     * @param string|null $name
     */
    public function __construct(?string $name)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }


}