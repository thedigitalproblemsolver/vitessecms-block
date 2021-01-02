<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Models\Datagroup;
use VitesseCms\Form\Helpers\ElementHelper;

class BlockAffiliateInitializeSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $form->_(
            'select',
            'Entry point datagroups',
            'datagroups',
            [
                'multiple'   => 'multiple',
                'options'    => ElementHelper::arrayToSelectOptions(Datagroup::findAll()),
                'inputClass' => 'select2',
            ]
        );

        $form->addNumber('Cookie lifetime in days', 'cookieLifetime');
    }
}
