<?php

declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Models\BlockIterator;
use VitesseCms\Database\Models\FindOrderIterator;
use VitesseCms\Database\Models\FindValueIterator;
use VitesseCms\Database\Traits\TraitRepositoryConstructor;
use VitesseCms\Database\Traits\TraitRepositoryParseFindAll;
use VitesseCms\Database\Traits\TraitRepositoryParseGetById;

class BlockRepository
{
    use TraitRepositoryConstructor;
    use TraitRepositoryParseGetById;
    use TraitRepositoryParseFindAll;

    public function getById(string $id, bool $hideUnpublished = true): ?Block
    {
        return $this->parseGetById($id, $hideUnpublished);
    }

    public function findAll(
        ?FindValueIterator $findValuesIterator = null,
        bool $hideUnpublished = true,
        ?int $limit = null,
        ?FindOrderIterator $findOrders = null,
        ?array $returnFields = null
    ): BlockIterator {
        return $this->parseFindAll($findValuesIterator, $hideUnpublished, $limit, $findOrders, $returnFields);
    }
}
