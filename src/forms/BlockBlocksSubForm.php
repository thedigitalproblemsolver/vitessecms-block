<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockBlocksSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $form->addDropdown(
            '%ADMIN_BLOCKS%',
            'blocks',
            (new Attributes())
                ->setMultiple(true)
                ->setInputClass('select2-sortable')
                ->setOptions(ElementHelper::modelIteratorToOptions($repositories->block->findAll()))
        );
    }
}
