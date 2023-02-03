<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Enum\BlockPositionEnum;
use VitesseCms\Block\Listeners\Admin\AdminMenuListener;
use VitesseCms\Block\Listeners\ContentTags\TagBlockListener;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Core\Interfaces\InitiateListenersInterface;
use VitesseCms\Core\Interfaces\InjectableInterface;
use VitesseCms\User\Repositories\BlockPositionRepository;

class InitiateListeners implements InitiateListenersInterface
{
    public static function setListeners(InjectableInterface $di): void
    {
        if ($di->user->hasAdminAccess()) :
            $di->eventsManager->attach('adminMenu', new AdminMenuListener());
        endif;
        $di->eventsManager->attach('contentTag', new TagBlockListener(
            new BlockRepository(),
            $di->eventsManager
        ));
        $di->eventsManager->attach(BlockEnum::BLOCK_LISTENER, new BlockListeners(
            $di->eventsManager,
            new BlockRepository()
        ));
        $di->eventsManager->attach(BlockPositionEnum::BLOCKPOSITION_LISTENER, new BlockPositionListener(
            new BlockPositionRepository(),
            new BlockRepository()
        ));
    }
}
