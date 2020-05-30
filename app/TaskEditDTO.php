<?php

declare(strict_types=1);

namespace App;

use Zend\Diactoros\ServerRequest;

class TaskEditDTO
{
    public string $text;
    public bool $isCompleted;

    public static function fromRequest(ServerRequest $request): self
    {
        $input = $request->getParsedBody();

        $dto = new self;
        $dto->text = $input['text'];
        $dto->isCompleted = isset($input['complete']);

        return $dto;
    }
}