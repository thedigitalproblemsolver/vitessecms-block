<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use VitesseCms\Block\Models\Block;
use Phalcon\Events\Manager;

class InitiateAdminListeners
{
    public static function setListeners(Manager $eventsManager): void
    {
        $eventsManager->attach(Block::class, new AdminBlockListener());
    }
}
