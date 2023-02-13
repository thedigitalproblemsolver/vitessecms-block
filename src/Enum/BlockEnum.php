<?php declare(strict_types=1);

namespace VitesseCms\Block\Enum;

use VitesseCms\Core\AbstractEnum;

enum BlockEnum : string
{
    case BLOCK_LISTENER = 'blockListener';
    case LISTENER_GET_REPOSITORY = 'blockListener:getRepository';
    case LISTENER_RENDER_BLOCK = 'blockListener:renderBlock';
}
