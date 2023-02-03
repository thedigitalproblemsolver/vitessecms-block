<?php declare(strict_types=1);

namespace VitesseCms\Block\Enum;

use VitesseCms\Core\AbstractEnum;

class BlockEnum extends AbstractEnum
{
    public const BLOCK_LISTENER = 'blockListener';
    public const LISTENER_GET_REPOSITORY = 'blockListener:getRepository';
    public const LISTENER_RENDER_BLOCK = 'blockListener:renderBlock';
}
