<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners\Admin;

use Phalcon\Events\Event;
use VitesseCms\Admin\Models\AdminMenu;
use VitesseCms\Admin\Models\AdminMenuNavBarChildren;

class AdminMenuListener
{
    public function AddChildren(Event $event, AdminMenu $adminMenu): void
    {
        $children = new AdminMenuNavBarChildren();
        $children->addChild('Blocks', 'admin/block/adminblock/adminList')
            ->addChild('BlockPositions', 'admin/block/adminblockposition/adminList');
        $adminMenu->addDropdown('DataDesign', $children);
    }
}
