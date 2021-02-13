<?php declare(strict_types=1);

namespace VitesseCms\Block\Controllers;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Admin\AbstractAdminController;
use VitesseCms\Block\Helpers\BlockHelper;
use VitesseCms\Block\Interfaces\RepositoriesInterface;
use VitesseCms\Block\Models\Block;

class IndexController extends AbstractAdminController implements RepositoriesInterface
{
    public function renderAction(): void
    {
        if ($this->request->isAjax()) :
            $block = $this->repositories->block->getById($this->request->get('blockId'));
            if($block instanceof Block) :
                $this->prepareJson(BlockHelper::renderAjax($block, $this->view));
            endif;
        endif;
    }

    public function renderHtmlAction(): void
    {
        if ($this->request->isAjax()) :
            $block = $this->repositories->block->getById($this->request->get('blockId'));
            if($block instanceof Block) :
                $object = $block->getBlock();

                /** @var AbstractBlockModel $item */
                $item = new $object($this->view);

                $cacheKey = $this->cache->getCacheKey($item->getCacheKey($block));
                $rendering = $this->cache->get($cacheKey);
                if (!$rendering) :
                    $rendering = BlockHelper::performRendering($block, $item, $this->view);
                    $this->cache->save($cacheKey, $rendering);
                endif;

                echo $this->content->parseContent((string)$rendering);
            endif;
            die();
        endif;
    }
}
