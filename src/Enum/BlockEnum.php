<?php declare(strict_types=1);

namespace VitesseCms\Block\Enum;

use VitesseCms\Core\AbstractEnum;

enum BlockEnum : string
{
    case BLOCK_LISTENER = 'BlockListener';
    case LISTENER_GET_REPOSITORY = 'BlockListener:getRepository';
    case LISTENER_RENDER_BLOCK = 'BlockListener:renderBlock';
}
