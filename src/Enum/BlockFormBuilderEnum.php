<?php declare(strict_types=1);

namespace VitesseCms\Block\Enum;

use VitesseCms\Core\AbstractEnum;

enum BlockFormBuilderEnum : string
{
    case BLOCK_LISTENER = 'BlockFormBuilderListener';
    case LISTENER_GET_REPOSITORY = 'BlockFormBuilderListener:getRepository';
}
