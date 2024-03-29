<?php

declare(strict_types=1);

namespace VitesseCms\Block\Enum;

enum BlockEnum: string
{
    case LISTENER_RENDER_BLOCK = 'BlockListener:renderBlock';
    case LISTENER = 'BlockListener';
    case DYNAMIC_LOADING = 'dynamicLoading';
}
