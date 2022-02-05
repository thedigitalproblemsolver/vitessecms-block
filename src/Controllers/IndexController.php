<?php declare(strict_types=1);

namespace VitesseCms\Block\Controllers;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Helpers\BlockHelper;
use VitesseCms\Block\Interfaces\RepositoriesInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Content\Enum\ContentEnum;
use VitesseCms\Content\Services\ContentService;
use VitesseCms\Core\AbstractEventController;
use VitesseCms\Core\Enum\CacheEnum;
use VitesseCms\Core\Services\CacheService;

class IndexController extends AbstractEventController implements RepositoriesInterface
{
    /**
     * @var CacheService
     */
    private $cache;

    /**
     * @var ContentService
     */
    private $content;

    public function __construct()
    {
        $this->cache = $this->eventsManager->fire(CacheEnum::ATTACH_SERVICE_LISTENER,new stdClass());
        $this->content = $this->eventsManager->fire(ContentEnum::ATTACH_SERVICE_LISTENER,new stdClass());
    }

    public function renderAction(): void
    {
        if ($this->request->isAjax()) :
            $block = $this->repositories->block->getById($this->request->get('blockId'));
            if ($block instanceof Block) :
                $this->prepareJson(BlockHelper::renderAjax($block, $this->view));
            endif;
        endif;
    }

    public function renderHtmlAction(): void
    {
        if ($this->request->isAjax()) :
            $block = $this->repositories->block->getById($this->request->get('blockId'));
            if ($block instanceof Block) :
                $object = $block->getBlock();

                /** @var AbstractBlockModel $item */
                $item = new $object($this->view);

                $cacheKey = $this->cache->getCacheKey($item->getCacheKey($block));
                $rendering = $this->cache->get($cacheKey);
                if (!$rendering) :
                    $rendering = $this->eventsManager->fire(BlockEnum::BLOCK_LISTENER . ':renderBlock', $block);
                    $this->cache->save($cacheKey, $rendering);
                endif;

                echo $this->content->parseContent((string)$rendering);
            endif;
            die();
        endif;
    }
}
