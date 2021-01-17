<?php declare(strict_types=1);

namespace VitesseCms\Block\Interfaces;

use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Communication\Repositories\NewsletterRepository;
use VitesseCms\Content\Repositories\ItemRepository;
use VitesseCms\Datafield\Repositories\DatafieldRepository;
use VitesseCms\Core\Repositories\DatagroupRepository;
use VitesseCms\Database\Interfaces\BaseRepositoriesInterface;

/**
 * @property BlockPositionRepository $blockPosition
 * @property BlockRepository $block
 * @property NewsletterRepository $newsletter
 * @property DatagroupRepository $datagroup
 * @property DatafieldRepository $datafield
 * @property ItemRepository $item
 */
interface RepositoryInterface extends BaseRepositoriesInterface
{
}
