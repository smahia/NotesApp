<?php

namespace App\Error;

use OpenApi\Attributes as OA;

/**
 * Error model to customise the response body of an error
 */
class ApiError
{
    #[OA\Property(type: 'int', default: 404, nullable: false)]
   private int $code;

    #[OA\Property(type: 'string', default: 'message', maxLength: 255, nullable: false)]
   private string $message;

    /**
     * @param int $code
     * @param string $message
     */
    public function __construct(int $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }


}