<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;

class BlockImageSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $form->addUpload('Image', 'image');
    }
}
