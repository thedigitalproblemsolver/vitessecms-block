<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use ArrayIterator;

class BlockPositionIterator extends ArrayIterator
{
    public function __construct(array $blockPositions)
    {
        parent::__construct($blockPositions);
    }

    public function current(): BlockPosition
    {
        return parent::current();
    }
}
