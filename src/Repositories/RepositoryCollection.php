<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Datafield\Repositories\DatafieldRepository;
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

    /**
     * @var DatafieldRepository
     */
    public $datafield;

    public function __construct(
        BlockPositionRepository $blockPositionRepository,
        BlockRepository $blockRepository,
        DatagroupRepository $datagroupRepository,
        DatafieldRepository $datafieldRepository
    )
    {
        $this->blockPosition = $blockPositionRepository;
        $this->block = $blockRepository;
        $this->datagroup = $datagroupRepository;
        $this->datafield = $datafieldRepository;
    }
}
