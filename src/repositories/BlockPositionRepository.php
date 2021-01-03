<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Models\BlockPosition;
use VitesseCms\Block\Models\BlockPositionIterator;

class BlockPositionRepository
{
    public function getById(string $id, bool $hideUnpublished = true): ?BlockPosition
    {
        BlockPosition::setFindPublished($hideUnpublished);

        /** @var BlockPosition $blockPosition */
        $blockPosition = BlockPosition::findById($id);
        if(is_object($blockPosition)):
            return $blockPosition;
        endif;

        return null;
    }

    public function getByPositionNameAndDatagroup(string $position, array $dataGroups): BlockPositionIterator
    {
        BlockPosition::setFindValue('position', $position);
        BlockPosition::setFindValue('datagroup', ['$in' => $dataGroups]);
        BlockPosition::addFindOrder('ordering');

        return new BlockPositionIterator(BlockPosition::findAll());
    }
}
