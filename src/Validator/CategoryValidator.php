<?php

namespace ProjectApi\Validator;

use ProjectApi\Exception\InvalidInputException;

class CategoryValidator
{
    public static function validate(array $data): void
    {
        $errors = [];
        $name = $data['name'] ?? '';
        if (trim($name) === '') {
            $errors[] = 'name is required';
        }
        if (strlen($name) < 3) {
            $errors[] = 'name must be atleast less than 3 characters';
        }
        if (strlen($name) > 20) {
            $errors[] = 'name must not exceed over 20 characters';
        }
        $description = $data['description'] ?? '';
        if (trim($description) === '') {
            $errors[] = 'description is required';
        }
        if (strlen($description) < 3) {
            $errors[] = 'description must be atleast less than 3 characters';
        }
        if (strlen($description) > 50) {
            $errors[] = 'description must not exceed over 20 characters';
        }
        if (count($errors) > 0) {
            throw InvalidInputException::fromErrors($errors);
        }
    }
}
