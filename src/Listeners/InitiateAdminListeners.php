<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use Phalcon\Events\Manager;
use VitesseCms\Block\Blocks\Blocks;
use VitesseCms\Block\Controllers\AdminblockController;
use VitesseCms\Block\Controllers\AdminblockpositionController;
use VitesseCms\Block\Listeners\Admin\AdminblockControllerListener;
use VitesseCms\Block\Listeners\Admin\AdminblockpositionControllerListener;
use VitesseCms\Block\Listeners\Admin\AdminMenuListener;
use VitesseCms\Block\Listeners\Blocks\BlockBlocksListener;
use VitesseCms\Block\Repositories\BlockRepository;

class InitiateAdminListeners
{
    public static function setListeners(Manager $eventsManager): void
    {
        $eventsManager->attach('adminMenu', new AdminMenuListener());
        $eventsManager->attach(AdminblockController::class, new AdminblockControllerListener());
        $eventsManager->attach(AdminblockpositionController::class, new AdminblockpositionControllerListener());
        $eventsManager->attach(Blocks::class, new BlockBlocksListener(new BlockRepository()));
    }
}
