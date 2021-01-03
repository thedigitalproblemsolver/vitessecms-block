<?php declare(strict_types=1);

namespace VitesseCms\Block\Factories;

use VitesseCms\Block\Models\Block;

class BlockFactory
{
    public static function create(
        string $title,
        string $blockType,
        string $template,
        array $blockSettings = [],
        bool $published = false,
        int $ordering = 0
    ): Block {
        $block = new Block();
        $block->set('name', $title, true);
        $block->set('block', $blockType);
        $block->set('template', $template);
        foreach ($blockSettings as $blockSettingKey => $blockSettingValue) :
            $multilang = false;
            if (isset($blockSettingValue['multilang']) && $blockSettingValue['multilang']) :
                $multilang = true;
            endif;
            $block->set($blockSettingKey, $blockSettingValue['value'], $multilang);
        endforeach;
        $block->setPublished($published);
        $block->set('ordering', $ordering);

        return $block;
    }
}
