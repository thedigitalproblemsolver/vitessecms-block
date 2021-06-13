<?php declare(strict_types=1);

namespace VitesseCms\Block\Interfaces;

use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Datafield\Repositories\DatafieldRepository;
use VitesseCms\Datagroup\Repositories\DatagroupRepository;
use VitesseCms\Database\Interfaces\BaseRepositoriesInterface;

/**
 * @property BlockPositionRepository $blockPosition
 * @property BlockRepository $block
 * @property DatagroupRepository $datagroup
 * @property DatafieldRepository $datafield
 */
interface RepositoryInterface extends BaseRepositoriesInterface
{
}
