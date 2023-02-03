<?php declare(strict_types=1);

namespace VitesseCms\Block\Enum;

use VitesseCms\Core\AbstractEnum;

class BlockPositionEnum extends AbstractEnum
{
    public const BLOCKPOSITION_LISTENER = 'blockPositionListener';
    public const GET_REPOSITORY = 'blockPositionListener:getRepository';
    public const RENDER_POSITION = 'blockPositionListener:renderPosition';
}
