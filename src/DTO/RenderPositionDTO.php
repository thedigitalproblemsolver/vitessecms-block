<?php declare(strict_types=1);

namespace VitesseCms\Block\DTO;

use Dialogflow\Action\User;
use Phalcon\Events\Event;

class RenderPositionDTO {
    readonly string $position;
    readonly array $roles;
    readonly array $datagroups;

    final public function __construct(
        string $position,
        array $roles = [],
        array $datagroups = []
    )
    {
        $this->position = $position;
        $this->roles = $roles;
        $this->datagroups = $datagroups;
    }
}