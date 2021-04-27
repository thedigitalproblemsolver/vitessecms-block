<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Interfaces\RepositoryInterface;

class AdminRepositoryCollection implements RepositoryInterface
{
    /**
     * @var BlockRepository
     */
    public $block;

    public function __construct(
        BlockRepository $blockRepository
    )
    {
        $this->block = $blockRepository;
    }
}
