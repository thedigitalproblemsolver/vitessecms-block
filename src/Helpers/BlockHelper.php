<?php declare(strict_types=1);

namespace VitesseCms\Block\Helpers;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Services\CacheService;
use VitesseCms\Core\Services\ViewService;

/**
 * @deprecated move to BlockUtil
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
            $rendering = self::performRendering($block, $blockType, $view);
        else :
            $cacheKey = $cacheService->getCacheKey($blockType->getCacheKey($block));
            $rendering = $cacheService->get($cacheKey);
            if (!$rendering) :
                $rendering = self::performRendering($block, $blockType, $view);
                $cacheService->save($cacheKey, $rendering);
            endif;
        endif;

        return $rendering;
    }

    public static function performRendering(Block $block, AbstractBlockModel $item, ViewService $view): string
    {
        $item->parse($block);
        $return = $view->renderTemplate($item->getTemplate(), '', $item->getTemplateParams($block));

        if (!empty($block->getMaincontentWrapper())) :
            $return = $view->renderTemplate('main_content', 'partials/block', ['body' => $return]);
        endif;

        return $return;
    }

    public static function renderAjax(Block $block, ViewService $view): array
    {
        $blockType = $block->getBlockTypeInstance();
        $blockType->parse($block);

        return $block->_('return');
    }
}
