<?php declare(strict_types=1);

namespace VitesseCms\Block\Interfaces;

use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Interfaces\InjectableInterface;

interface BlockModelInterface
{
    public function parse(Block $block): void;

    public function getCacheKey(Block $block): string;

    public function setExcludeFromCache(bool $value): BlockModelInterface;

    public function getTemplate(): string;

    public function getDi(): InjectableInterface;

    public function getTemplateParams(Block $block): array;
}
