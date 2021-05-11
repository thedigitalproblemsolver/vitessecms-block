<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;

class BlockMainContent extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        parent::parse($block);

        if ($this->view->hasCurrentItem()) :
            $item = $this->view->getCurrentItem();
            if($block->getBool('useDatagroupTemplate')) :
                $datagroup = $this->di->repositories->datagroup->getById($item->getDatagroup());
                if ($datagroup->getTemplate() !== null) :
                    $this->template = $datagroup->getTemplate();
                endif;
            endif;
            $block->set('imageFullWidth', true);
            $this->di->eventsManager->fire(get_class($this) . ':parse', $this, $block);
        endif;
    }

    public function getCacheKey(Block $block): string
    {
        return parent::getCacheKey($block) . $this->view->getCurrentItem()->getUpdatedOn()->getTimestamp();
    }
}
