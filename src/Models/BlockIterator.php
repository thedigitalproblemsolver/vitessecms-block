<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

class BlockIterator extends \ArrayIterator
{
    public function __construct(array $blocks)
    {
        parent::__construct($blocks);
    }

    public function current(): Block
    {
        return parent::current();
    }
}
