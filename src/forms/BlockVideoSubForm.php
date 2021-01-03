<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Form\Models\Attributes;

class BlockVideoSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $form->addText(
            'Video-url',
            'videoUrl',
            (new Attributes())->setRequired(true)->setMultilang(true)
        )->addUpload(
            'Video-poster',
            'videoPoster',
            (new Attributes())->setMultilang(true)
        );
    }
}
