<?php declare(strict_types=1);

namespace VitesseCms\Block\Enum;

use VitesseCms\Core\AbstractEnum;

enum BlockFormEnum : string
{
    case BLOCK_FORM_LISTENER = 'BlockFormListener';
    case LISTENER_GET_TEMPLATE_FILES = 'BlockFormListener:getTemplateFiles';
}
