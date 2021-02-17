<?php declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Models\BlockIterator;
use VitesseCms\Database\Models\FindValueIterator;

class BlockRepository
{
    public function getById(string $id, bool $hideUnpublished = true): ?Block
    {
        Block::setFindPublished($hideUnpublished);

        /** @var Block $block */
        $block = Block::findById($id);
        if(is_object($block)):
            return $block;
        endif;

        return null;
    }

    public function findAll(
        ?FindValueIterator $findValues = null,
        bool $hideUnpublished = true
    ): BlockIterator {
        Block::setFindPublished($hideUnpublished);
        Block::addFindOrder('name');
        $this->parsefindValues($findValues);

        return new BlockIterator(Block::findAll());
    }

    protected function parsefindValues(?FindValueIterator $findValues = null): void
    {
        if ($findValues !== null) :
            while ($findValues->valid()) :
                $findValue = $findValues->current();
                Block::setFindValue(
                    $findValue->getKey(),
                    $findValue->getValue(),
                    $findValue->getType()
                );
                $findValues->next();
            endwhile;
        endif;
    }
}
