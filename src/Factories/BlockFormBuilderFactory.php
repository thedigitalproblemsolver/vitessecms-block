<?php declare(strict_types=1);

namespace VitesseCms\Block\Factories;

use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Form\Blocks\FormBuilder;

class BlockFormBuilderFactory
{
    public static function createFromBlock(Block $block, ViewService $view): FormBuilder
    {
        return (new FormBuilder($view,$block->getDI()))
            ->setDatagroup($block->_('datagroup'))
            ->setNewsletters((array)$block->_('newsletters'))
            ->setUseRecaptcha((bool)$block->_('useRecaptcha'))
            ->setName($block->getRaw('name'))
            ->setSystemThankyou((array)$block->getRaw('systemThankyou'));
    }
}
