<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockItemlistDatagroupSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $form->addDropdown(
            '%ADMIN_ITEMS%',
            'items',
            (new Attributes())
                ->setMultiple(true)
                ->setInputClass('select2')
                ->setOptions(ElementHelper::modelIteratorToOptions($repositories->datagroup->findAll()))
        );

        if (is_array($block->_('items'))) :
            foreach ($block->_('items') as $datagroupId) :
                BlockItemlistSubForm::buildDatafieldValueForm($form, $datagroupId);
            endforeach;
            $form->addText('parentId', 'datafieldValue[parentId]');
        endif;
    }
}
