<?php declare(strict_types=1);

namespace VitesseCms\Block\Helpers;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Services\CacheService;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Core\Utils\DirectoryUtil;
use VitesseCms\Core\Utils\FileUtil;
use VitesseCms\Core\Utils\SystemUtil;

class BlockHelper
{
    public static function getTypes(string $rootDir, string $accountDir): array
    {
        $exclude = ['Block', 'BlockPosition'];
        $files = $types = [];

        $directories = [
            $rootDir.'src/block/models/',
            $accountDir.'src/block/models/',
        ];

        foreach ($directories as $directory) :
            $files = array_merge($files, DirectoryUtil::getFilelist($directory));
        endforeach;
        ksort($files);

        foreach ($files as $path => $file) :
            if (!\in_array(FileUtil::getName($file), $exclude, true)) :
                $name = FileUtil::getName($file);
                $className = SystemUtil::createNamespaceFromPath($path);
                $types[$className] = substr($name, 5, strlen($name));
            endif;
        endforeach;

        return $types;
    }

    public static function render(
        Block $block,
        ViewService $view,
        CacheService $cacheService
    ): string {
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

    public static function renderAjax(Block $block, ViewService $view): array
    {
        $object = $block->getBlock();
        /** @var AbstractBlockModel $item */
        $item = new $object($view);
        $item->parse($block);

        return $block->_('return');
    }

    public static function performRendering(
        Block $block,
        AbstractBlockModel $item,
        ViewService $view
    ): string {
        $item->parse($block);
        $return = $view->renderTemplate($item->getTemplate(), '', ['block' => $block]);

        if (!empty($block->getMaincontentWrapper())) :
            $return = $view->renderTemplate(
                'main_content',
                'partials/block',
                ['body' => $return]
            );
        endif;

        return $return;
    }
}
