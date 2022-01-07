<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Interfaces\AdminRepositoryInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
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

    public function __construct(
        BlockRepository $blockRepository,
        LayoutRepository $layoutRepository,
        DatagroupRepository $datagroupRepository,
        BlockPositionRepository $blockPositionRepository
    )
    {
        $this->block = $blockRepository;
        $this->layout = $layoutRepository;
        $this->datagroup = $datagroupRepository;
        $this->blockPosition = $blockPositionRepository;
    }
}
