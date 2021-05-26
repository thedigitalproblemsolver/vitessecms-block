<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use VitesseCms\Block\Blocks\Blocks;
use VitesseCms\Block\Controllers\AdminblockController;
use VitesseCms\Block\Controllers\AdminblockpositionController;
use VitesseCms\Block\Listeners\Admin\AdminblockControllerListener;
use VitesseCms\Block\Listeners\Admin\AdminblockpositionControllerListener;
use VitesseCms\Block\Listeners\Admin\AdminMenuListener;
use VitesseCms\Block\Listeners\Blocks\BlockBlocksListener;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Core\Interfaces\InitiateListenersInterface;
use VitesseCms\Core\Interfaces\InjectableInterface;

class InitiateAdminListeners implements InitiateListenersInterface
{
    public static function setListeners(InjectableInterface $di): void
    {
        if ($di->user->hasAdminAccess()) :
            $di->eventsManager->attach('adminMenu', new AdminMenuListener());
        endif;
        $di->eventsManager->attach(AdminblockController::class, new AdminblockControllerListener());
        $di->eventsManager->attach(AdminblockpositionController::class, new AdminblockpositionControllerListener());
        $di->eventsManager->attach(Blocks::class, new BlockBlocksListener(new BlockRepository()));
    }
}
