<?php

namespace ProjectApi\Validator;

use ProjectApi\Exception\InvalidInputException;

class PostValidator
{
    public static function validate(array $data): void
    {
        $errors = [];
        $title = $data['title'] ?? '';
        if (trim($title) === '') {
            $errors[] = 'title is required';
        }
        if (strlen($title) < 3) {
            $errors[] = 'title must be atleast less than 3 characters';
        }
        if (strlen($title) > 30) {
            $errors[] = 'title must not exceed over 30 characters';
        }
        $content = $data['content'] ?? '';
        if (trim($content) === '') {
            $errors[] = 'content is required';
        }
        if (strlen($content) < 3) {
            $errors[] = 'content must be atleast less than 3 characters';
        }
        if (strlen($content) > 225) {
            $errors[] = 'content must not exceed over 225 characters';
        }
        $thumbnail = $data['thumbnail'] ?? '';
        if (trim($thumbnail) === '') {
            $errors[] = 'thumbnail is required';
        }
        $author = $data['author'] ?? '';
        if (trim($author) === '') {
            $errors[] = 'author is required';
        }
        if (count($errors) > 0) {
            throw InvalidInputException::fromErrors($errors);
        }
    }
}
