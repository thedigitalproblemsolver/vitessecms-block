<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Helpers\ItemHelper;
use VitesseCms\Database\Models\FindValue;
use VitesseCms\Database\Models\FindValueIterator;
use VitesseCms\Form\Models\Attributes;

class BlockItemlistChildrenOfItemSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $selectedItem = null;

        $options = [
            [
                'value'    => '',
                'label'    => '%ADMIN_TYPE_TO_SEARCH%',
                'selected' => false,
            ],
        ];
        if ($block->_('item')) :
            $selectedItem = $repositories->item->getById($block->_('item'));
            $itemPath = ItemHelper::getPathFromRoot($selectedItem);
            $options[] = [
                'value'    => (string)$selectedItem->getId(),
                'label'    => implode(' - ', $itemPath),
                'selected' => true,

            ];
        endif;

        $form->addDropdown(
            '%ADMIN_ITEMS%',
            'item',
            (new Attributes())
                ->setOptions($options)
                ->setInputClass('select2-ajax')
                ->setDataUrl('/content/index/search/')
        );

        if ($selectedItem !== null) :
            $datagroupChildren = $repositories->datagroup->findAll(new FindValueIterator(
                [new FindValue('parentId', $selectedItem->getDatagroup())]
            ));
            while ($datagroupChildren->valid()) :
                $datagroupChild = $datagroupChildren->current();
                BlockItemlistSubForm::buildDatafieldValueForm($form, (string)$datagroupChild->getId());
                $datagroupChildren->next();
            endwhile;
        endif;
    }
}
