<?php

declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Enum\BlockFormBuilderEnum;
use VitesseCms\Block\Enum\BlockPositionEnum;
use VitesseCms\Block\Listeners\Admin\AdminMenuListener;
use VitesseCms\Block\Listeners\ContentTags\TagBlockListener;
use VitesseCms\Block\Listeners\Repositories\BlockFormBuilderRepositoryListener;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Models\BlockPosition;
use VitesseCms\Block\Repositories\BlockFormBuilderRepository;
use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Core\Interfaces\InitiateListenersInterface;
use VitesseCms\Core\Interfaces\InjectableInterface;

class InitiateListeners implements InitiateListenersInterface
{
    public static function setListeners(InjectableInterface $injectable): void
    {
        if ($injectable->user->hasAdminAccess()) :
            $injectable->eventsManager->attach('adminMenu', new AdminMenuListener());
        endif;
        $injectable->eventsManager->attach(
            'contentTag',
            new TagBlockListener(
                new BlockRepository(Block::class),
                $injectable->eventsManager
            )
        );
        $injectable->eventsManager->attach(
            BlockEnum::LISTENER->value,
            new BlockListeners($injectable->eventsManager, $injectable->request->isAjax())
        );
        $injectable->eventsManager->attach(
            BlockPositionEnum::BLOCKPOSITION_LISTENER,
            new BlockPositionListener(
                new BlockPositionRepository(BlockPosition::class),
                new BlockRepository(Block::class),
                $injectable->eventsManager
            )
        );
        $injectable->eventsManager->attach(
            BlockFormBuilderEnum::BLOCK_LISTENER->value,
            new BlockFormBuilderRepositoryListener(
                new BlockFormBuilderRepository(
                    new BlockRepository(Block::class),
                    $injectable->view
                )
            )
        );
    }
}
