<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Form\Models\Attributes;

class BlockFilterResultSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $form->addText(
            '%ADMIN_HEADING%',
            'heading',
            (new Attributes())->setRequired(true)->setMultilang(true)
        )->addEditor(
            '%ADMIN_INTROTEXT%',
            'introtext',
            (new Attributes())->setRequired(true)->setMultilang(true)
        )->addEditor(
            '%ADMIN_FILTER_NO_RESULT_TEXT%',
            'noresultText',
            (new Attributes())->setRequired(true)->setMultilang(true)
        );
    }
}
