<?php

declare(strict_types=1);

namespace App;

use Zend\Diactoros\ServerRequest;

class ValidateTask
{
    public static function validate(ServerRequest $request): bool
    {
        $input = $request->getParsedBody();

        $isset = isset($input['text']) && isset($input['email']) && isset($input['username']);
        $notEmpty = !empty($input['text']) && !empty($input['email']) && !empty($input['username']);

        return $isset && $notEmpty && filter_var($input['email'], FILTER_VALIDATE_EMAIL) !== false;
    }
}