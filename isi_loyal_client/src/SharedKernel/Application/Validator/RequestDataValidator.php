<?php

declare(strict_types=1);

namespace SharedKernel\Application\Validator;

use SharedKernel\Application\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RequestDataValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /** @throws ValidatorException */
    public function validate(mixed $data): void
    {
        $errors = $this->validator->validate($data);

        if (0 !== $errors->count()) {
            $errorMessage = [];

            foreach ($errors as $error) {
                $errorMessage[] = [$error->getPropertyPath() => $error->getMessage()];
            }

            throw new ValidatorException($errorMessage);
        }
    }
}
