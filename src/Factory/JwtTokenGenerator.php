<?php

namespace ProjectApi\Factory;

use Tuupola\Middleware\JwtAuthentication;

class JwtTokenGenerator
{
    public static function make(): JwtAuthentication
    {
        return new JwtAuthentication([
            'secret' => $_ENV['JWT_TOKEN']
        ]);
    }
}
