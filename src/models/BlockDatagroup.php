<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Core\Repositories\DatafieldRepository;
use VitesseCms\Database\Utils\MongoUtil;

class BlockDatagroup extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        if (
            MongoUtil::isObjectId($block->_('datafield'))
            && $this->view->getCurrentItem()->getDatagroup() === $block->_('datagroup')
        ) :
            $datafield = (new DatafieldRepository())->getById($block->_('datafield'));
            $fieldValue = $this->view->getCurrentItem()->_($datafield->getCallingName());
            if (is_array($fieldValue)) :
                $items = [];
                foreach ($fieldValue as $value) :
                    if (!empty($value)) :
                        $items[] = ['id' => $value];
                    endif;
                endforeach;
                $block->set('items', $items);
            endif;
        endif;

        parent::parse($block);
    }
}
