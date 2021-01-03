<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Communication\Repositories\NewsletterRepository;
use VitesseCms\Content\Repositories\ItemRepository;
use VitesseCms\Core\Repositories\DatafieldRepository;
use VitesseCms\Core\Repositories\DatagroupRepository;

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
     * @var NewsletterRepository
     */
    public $newsletter;

    /**
     * @var DatagroupRepository
     */
    public $datagroup;

    /**
     * @var ItemRepository
     */
    public $item;

    /**
     * @var DatafieldRepository
     */
    public $datafield;

    public function __construct(
        BlockPositionRepository $blockPositionRepository,
        BlockRepository $blockRepository,
        NewsletterRepository $newsletterRepository,
        DatagroupRepository $datagroupRepository,
        ItemRepository $itemRepository,
        DatafieldRepository $datafieldRepository
    ) {
        $this->blockPosition = $blockPositionRepository;
        $this->block = $blockRepository;
        $this->newsletter = $newsletterRepository;
        $this->datagroup = $datagroupRepository;
        $this->item  = $itemRepository;
        $this->datafield = $datafieldRepository;
    }
}
