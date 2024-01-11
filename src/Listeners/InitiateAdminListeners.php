<?php

declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use VitesseCms\Block\Blocks\Blocks as BlockBlocks;
use VitesseCms\Block\Controllers\AdminblockController;
use VitesseCms\Block\Controllers\AdminblockpositionController;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Enum\BlockPositionEnum;
use VitesseCms\Block\Listeners\Admin\AdminblockControllerListener;
use VitesseCms\Block\Listeners\Admin\AdminblockpositionControllerListener;
use VitesseCms\Block\Listeners\Admin\AdminMenuListener;
use VitesseCms\Block\Listeners\Blocks\BlockBlocksListener;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Models\BlockPosition;
use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Core\Interfaces\InitiateListenersInterface;
use VitesseCms\Core\Interfaces\InjectableInterface;

class InitiateAdminListeners implements InitiateListenersInterface
{
    public static function setListeners(InjectableInterface $di): void
    {
        $di->eventsManager->attach('adminMenu', new AdminMenuListener());
        $di->eventsManager->attach(AdminblockController::class, new AdminblockControllerListener());
        $di->eventsManager->attach(AdminblockpositionController::class, new AdminblockpositionControllerListener());
        $di->eventsManager->attach(BlockBlocks::class, new BlockBlocksListener(new BlockRepository(Block::class)));
        $di->eventsManager->attach(
            BlockEnum::LISTENER->value,
            new BlockListeners($di->eventsManager, $di->request->isAjax())
        );
        $di->eventsManager->attach(
            BlockPositionEnum::BLOCKPOSITION_LISTENER,
            new BlockPositionListener(
                new BlockPositionRepository(BlockPosition::class),
                new BlockRepository(Block::class),
                $di->eventsManager
            )
        );
    }
}
