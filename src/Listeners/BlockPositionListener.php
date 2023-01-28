<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use VitesseCms\User\Repositories\BlockPositionRepository;

class BlockPositionListener
{
    private BlockPositionRepository $blockPositionRepository;

    public function __construct(BlockPositionRepository $blockPositionRepository)
    {
        $this->blockPositionRepository = $blockPositionRepository;
    }

    public function getRepository(): BlockPositionRepository
    {
        return $this->blockPositionRepository;
    }
}