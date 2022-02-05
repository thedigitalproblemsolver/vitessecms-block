<?php declare(strict_types=1);

namespace VitesseCms\Block\Helpers;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Services\CacheService;
use VitesseCms\Core\Services\ViewService;

/**
 * @deprecated move to listeners adn take a look at BlockEnum
 */
class BlockHelper
{
    public static function render(Block $block, ViewService $view, CacheService $cacheService): string
    {
        if (substr_count($block->getTemplate(), 'lazyload')) :
            $block->getDI()->get('assets')->loadLazyLoading();
        endif;

        $blockType = $block->getBlockTypeInstance();
        $block->getDi()->eventsManager->fire($block->getBlock() . ':loadAssets', $blockType, $block);

        if ($blockType->_('excludeFromCache')) :
            $rendering = $block->getDi()->eventsManager->fire(BlockEnum::BLOCK_LISTENER . ':renderBlock', $block);
        else :
            $cacheKey = $cacheService->getCacheKey($blockType->getCacheKey($block));
            $rendering = $cacheService->get($cacheKey);
            if (!$rendering) :
                $rendering = $block->getDi()->eventsManager->fire(BlockEnum::BLOCK_LISTENER . ':renderBlock', $block);
                $cacheService->save($cacheKey, $rendering);
            endif;
        endif;

        return $rendering;
    }

    public static function renderAjax(Block $block, ViewService $view): array
    {
        $blockType = $block->getBlockTypeInstance();
        $blockType->parse($block);

        return $block->_('return');
    }
}
