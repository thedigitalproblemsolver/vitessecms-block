<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Datagroup\Repositories\DatagroupRepository;

class RepositoryCollection implements RepositoryInterface
{
    /**
     * @var BlockPositionRepository
     */
    public $blockPosition;

    /**
     * @var BlockRepository
     */
    public $block;

    /**
     * @var DatagroupRepository
     */
    public $datagroup;

    public function __construct(
        BlockPositionRepository $blockPositionRepository,
        BlockRepository $blockRepository,
        DatagroupRepository $datagroupRepository
    )
    {
        $this->blockPosition = $blockPositionRepository;
        $this->block = $blockRepository;
        $this->datagroup = $datagroupRepository;
    }
}
