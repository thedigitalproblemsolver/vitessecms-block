<?php declare(strict_types=1);

namespace VitesseCms\Block\Interfaces;

use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Models\Block;

interface BlockModelInterface
{
    public function parse(Block $block): void;

    public function buildBlockForm(BlockForm $form, Block $item, RepositoryInterface $repositories): void;

    public function loadAssets(Block $block): void;

    public function getCacheKey(Block $block): string;
    
    public function setExcludeFromCache(bool $value): BlockModelInterface;
}
