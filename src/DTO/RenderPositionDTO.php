<?php declare(strict_types=1);

namespace VitesseCms\Block\DTO;

use Dialogflow\Action\User;
use Phalcon\Events\Event;

class RenderPositionDTO {
    readonly string $position;
    readonly string $role;

    final public function __construct(string $position, string $role)
    {
        $this->position = $position;
        $this->role = $role;
    }
}