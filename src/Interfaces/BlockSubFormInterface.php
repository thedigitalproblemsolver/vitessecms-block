<?php declare(strict_types=1);

namespace VitesseCms\Block\Interfaces;

use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Repositories\AdminRepositoryCollection;

interface BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, AdminRepositoryCollection $repositories): void;
}
