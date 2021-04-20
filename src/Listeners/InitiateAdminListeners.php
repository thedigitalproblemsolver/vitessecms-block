<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use Phalcon\Events\Manager;
use VitesseCms\Block\Controllers\AdminblockController;
use VitesseCms\Block\Controllers\AdminblockpositionController;

class InitiateAdminListeners
{
    public static function setListeners(Manager $eventsManager): void
    {
        $eventsManager->attach('adminMenu', new AdminMenuListener());
        $eventsManager->attach(AdminblockController::class, new AdminblockControllerListener());
        $eventsManager->attach(AdminblockpositionController::class, new AdminblockpositionControllerListener());
    }
}
