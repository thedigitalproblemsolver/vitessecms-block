<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Models\BlockPosition;
use VitesseCms\Block\Models\BlockPositionIterator;
use VitesseCms\Database\Models\FindOrder;
use VitesseCms\Database\Models\FindOrderIterator;
use VitesseCms\Database\Models\FindValue;
use VitesseCms\Database\Models\FindValueIterator;

class BlockPositionRepository
{
    public function getById(string $id, bool $hideUnpublished = true): ?BlockPosition
    {
        BlockPosition::setFindPublished($hideUnpublished);

        /** @var BlockPosition $blockPosition */
        $blockPosition = BlockPosition::findById($id);
        if (is_object($blockPosition)):
            return $blockPosition;
        endif;

        return null;
    }

    public function getByPositionNameAndDatagroup(string $position, array $dataGroups): BlockPositionIterator
    {
        return $this->findAll(
            new FindValueIterator([
                new FindValue('position', $position),
                new FindValue('datagroup', ['$in' => $dataGroups])
            ]),
            true,
            null,
            new FindOrderIterator([new FindOrder('ordering', 1)])
        );
    }

    public function findAll(
        ?FindValueIterator $findValues = null,
        bool $hideUnpublished = true,
        ?int $limit = null,
        ?FindOrderIterator $findOrders = null
    ): BlockPositionIterator
    {
        BlockPosition::setFindPublished($hideUnpublished);
        if ($limit !== null) :
            BlockPosition::setFindLimit($limit);
        endif;
        if ($findOrders === null):
            $findOrders = new FindOrderIterator([new FindOrder('name', 1)]);
        endif;

        $this->parseFindValues($findValues);
        $this->parseFindOrders($findOrders);

        return new BlockPositionIterator(BlockPosition::findAll());
    }

    protected function parseFindValues(?FindValueIterator $findValues = null): void
    {
        if ($findValues !== null) :
            while ($findValues->valid()) :
                $findValue = $findValues->current();
                BlockPosition::setFindValue(
                    $findValue->getKey(),
                    $findValue->getValue(),
                    $findValue->getType()
                );
                $findValues->next();
            endwhile;
        endif;
    }

    protected function parseFindOrders(?FindOrderIterator $findOrders = null): void
    {
        if ($findOrders !== null) :
            while ($findOrders->valid()) :
                $findOrder = $findOrders->current();
                BlockPosition::addFindOrder(
                    $findOrder->getKey(),
                    $findOrder->getOrder()
                );
                $findOrders->next();
            endwhile;
        endif;
    }
}
