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
    public static function render(
        Block $block,
        ViewService $view,
        CacheService $cacheService
    ): string
    {
        $block->getDI()->eventsManager->fire($block->getBlock() . ':loadAssets');

        $object = $block->getBlock();
        /** @var AbstractBlockModel $item */
        $item = new $object($view);
        $item->loadAssets($block);

        if ($item->_('excludeFromCache')) :
            $rendering = self::performRendering($block, $item, $view);
        else :
            $cacheKey = $cacheService->getCacheKey($item->getCacheKey($block));
            $rendering = $cacheService->get($cacheKey);
            if (!$rendering) :
                $rendering = self::performRendering($block, $item, $view);
                $cacheService->save($cacheKey, $rendering);
            endif;
        endif;

        return $rendering;
    }

    public static function performRendering(
        Block $block,
        AbstractBlockModel $item,
        ViewService $view
    ): string
    {
        $item->parse($block);
        $return = $view->renderTemplate($item->getTemplate(), '', $item->getTemplateParams($block));

        if (!empty($block->getMaincontentWrapper())) :
            $return = $view->renderTemplate(
                'main_content',
                'partials/block',
                ['body' => $return]
            );
        endif;

        return $return;
    }

    public static function renderAjax(Block $block, ViewService $view): array
    {
        $object = $block->getBlock();
        /** @var AbstractBlockModel $item */
        $item = new $object($view);
        $item->parse($block);

        return $block->_('return');
    }
}
