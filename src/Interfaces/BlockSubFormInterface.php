<?php declare(strict_types=1);

namespace VitesseCms\Block\Interfaces;

use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Models\Block;
use VitesseCms\Content\Repositories\ItemRepository;
use VitesseCms\Datagroup\Repositories\DatagroupRepository;

interface BlockSubFormInterface
{
    public function getBlockForm(BlockForm $form, Block $block): void;
}
