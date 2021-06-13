<?php declare(strict_types=1);

namespace VitesseCms\Block\Interfaces;

use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Datagroup\Repositories\DatagroupRepository;
use VitesseCms\Database\Interfaces\BaseRepositoriesInterface;

/**
 * @property BlockPositionRepository $blockPosition
 * @property BlockRepository $block
 * @property DatagroupRepository $datagroup
 */
interface RepositoryInterface extends BaseRepositoriesInterface
{
}
