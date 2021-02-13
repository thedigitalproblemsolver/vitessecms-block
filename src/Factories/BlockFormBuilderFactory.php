<?php declare(strict_types=1);

namespace VitesseCms\Block\Factories;

use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Models\BlockFormBuilder;
use VitesseCms\Core\Services\ViewService;

class BlockFormBuilderFactory
{
    public static function createFromBlock(Block $block, ViewService $view): BlockFormBuilder
    {
        return (new BlockFormBuilder($view))
            ->setDatagroup($block->_('datagroup'))
            ->setNewsletters($block->_('newsletters'))
            ->setUseRecaptcha((bool)$block->_('useRecaptcha'))
            ->setName($block->getRaw('name'))
            ->setSystemThankyou($block->getRaw('systemThankyou'))
            ;
    }
}
