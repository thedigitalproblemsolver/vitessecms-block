<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Core\Helpers\ItemHelper;
use VitesseCms\Admin\Utils\AdminUtil;

class BlockBreadcrumbs extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        parent::parse($block);

        if (
            !AdminUtil::isAdminPage()
            && !$this->di->shop->checkout->isCurrentItemCheckout()
            && \is_object($this->view->getVar('currentItem'))
        ) {
            $block->set('items', ItemHelper::getPathFromRoot($this->view->getVar('currentItem')));
            $block->set('hasItems', true);
        }
    }
}
