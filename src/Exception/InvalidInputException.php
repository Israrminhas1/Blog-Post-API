<?php

namespace ProjectApi\Exception;

use InvalidArgumentException;

class InvalidInputException extends \InvalidArgumentException
{
    private $errors = [];

    public function getDataErrors(): array
    {
        return $this->errors;
    }
    public static function fromErrors(array $errors): self
    {
        $exception = new self('invalid input exception');
        $exception->errors = $errors;

        return $exception;
    }
}
