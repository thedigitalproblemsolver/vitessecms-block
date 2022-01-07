<?php declare(strict_types=1);

namespace VitesseCms\Block\Interfaces;

use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Datagroup\Repositories\DatagroupRepository;
use VitesseCms\Database\Interfaces\BaseRepositoriesInterface;
use VitesseCms\Mustache\Repositories\LayoutRepository;

/**
 * @property BlockPositionRepository $blockPosition
 * @property BlockRepository $block
 * @property DatagroupRepository $datagroup
 * @property LayoutRepository $layout
 */
interface AdminRepositoryInterface extends BaseRepositoriesInterface
{
}
