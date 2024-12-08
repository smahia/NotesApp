<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{

    private ValidatorInterface $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /** Validate an object and return an array of error messages.
     * @param object $object
     * @return array
     */
    public function validate(object $object): array
    {
        $errors = $this->validator->validate($object);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'message' => $error->getMessage()
                ];
            }
            return $errorMessages;
        }
        return [];
    }
}
