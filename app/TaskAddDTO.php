<?php

declare(strict_types=1);

namespace App;

use Zend\Diactoros\ServerRequest;


class TaskAddDTO
{
    public string $text;
    public string $email;
    public string $username;

    public static function fromRequest(ServerRequest $request): self
    {
        $input = $request->getParsedBody();

        $dto = new self;
        $dto->text = $input['text'];
        $dto->email = $input['email'];
        $dto->username = $input['username'];

        return $dto;
    }
}