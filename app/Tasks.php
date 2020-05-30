<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $text
 * @property string $email
 * @property string $username
 * @property string $status
 * @property bool   $edited_by_admin
 */
class Tasks extends Model
{
    public $timestamps = false;

    public const STATUS_CREATED = 'created';
    public const STATUS_COMPLETED = 'completed';

    protected $casts = [
        'edited_by_admin' => 'boolean',
    ];

    public static function createFromDTO(TaskAddDTO $DTO): self
    {
        $task = new self;

        $task->text = $DTO->text;
        $task->username = $DTO->username;
        $task->email = $DTO->email;
        $task->status = self::STATUS_CREATED;
        $task->edited_by_admin = false;

        $task->save();

        return $task;
    }

    public function updateFromDTO(TaskEditDTO $DTO): void
    {
        $this->edited_by_admin = $this->text !== $DTO->text;
        $this->text = $DTO->text;
        $this->status = $DTO->isCompleted ? self::STATUS_COMPLETED : self::STATUS_CREATED;

        $this->save();
    }
}