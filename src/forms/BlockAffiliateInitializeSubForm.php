<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Models\Datagroup;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockAffiliateInitializeSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $form->addDropdown(
            'Entry point datagroups',
            'datagroups',
            (new Attributes())->setMultiple(true)
                ->setInputClass('select2')
                ->setOptions(ElementHelper::modelIteratorToOptions($repositories->datagroup->findAll()))
        );

        $form->addNumber('Cookie lifetime in days', 'cookieLifetime');
    }
}
