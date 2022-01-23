<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Interfaces\AdminRepositoryInterface;
use VitesseCms\Content\Repositories\ItemRepository;
use VitesseCms\Datagroup\Repositories\DatagroupRepository;
use VitesseCms\Mustache\Repositories\LayoutRepository;

class AdminRepositoryCollection implements AdminRepositoryInterface
{
    /**
     * @var BlockRepository
     */
    public $block;

    /**
     * @var LayoutRepository
     */
    public $layout;

    /**
     * @var DatagroupRepository
     */
    public $datagroup;

    /**
     * @var BlockPositionRepository
     */
    public $blockPosition;

    /**
     * @var ItemRepository
     */
    public $item;

    public function __construct(
        BlockRepository $blockRepository,
        LayoutRepository $layoutRepository,
        DatagroupRepository $datagroupRepository,
        BlockPositionRepository $blockPositionRepository,
        ItemRepository $itemRepository
    )
    {
        $this->block = $blockRepository;
        $this->layout = $layoutRepository;
        $this->datagroup = $datagroupRepository;
        $this->blockPosition = $blockPositionRepository;
        $this->item = $itemRepository;
    }
}
