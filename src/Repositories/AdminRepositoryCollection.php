<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Mustache\Repositories\LayoutRepository;

class AdminRepositoryCollection implements RepositoryInterface
{
    /**
     * @var BlockRepository
     */
    public $block;

    /**
     * @var LayoutRepository
     */
    public $layout;

    public function __construct(
        BlockRepository $blockRepository,
        LayoutRepository $layoutRepository
    )
    {
        $this->block = $blockRepository;
        $this->layout = $layoutRepository;
    }
}
