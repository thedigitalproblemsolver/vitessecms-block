<?php

declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Models\BlockPosition;
use VitesseCms\Block\Models\BlockPositionIterator;
use VitesseCms\Database\Models\FindOrder;
use VitesseCms\Database\Models\FindOrderIterator;
use VitesseCms\Database\Models\FindValue;
use VitesseCms\Database\Models\FindValueIterator;
use VitesseCms\Database\Traits\TraitRepositoryConstructor;
use VitesseCms\Database\Traits\TraitRepositoryParseFindAll;
use VitesseCms\Database\Traits\TraitRepositoryParseGetById;

class BlockPositionRepository
{
    use TraitRepositoryConstructor;
    use TraitRepositoryParseGetById;
    use TraitRepositoryParseFindAll;

    public function getById(string $id, bool $hideUnpublished = true): ?BlockPosition
    {
        return $this->parseGetById($id, $hideUnpublished);
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
        ?FindValueIterator $findValuesIterator = null,
        bool $hideUnpublished = true,
        ?int $limit = null,
        ?FindOrderIterator $findOrders = null
    ): BlockPositionIterator {
        return $this->parseFindAll($findValuesIterator, $hideUnpublished, $limit, $findOrders);
    }
}
