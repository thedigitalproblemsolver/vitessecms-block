<?php declare(strict_types=1);

namespace VitesseCms\Block\Factories;

use VitesseCms\Block\Models\BlockPosition;

class BlockPositionFactory
{
    public static function create(
        string $title,
        string $blockId,
        string $position,
        $datagroupId,
        bool $published = false,
        int $ordering = 0
    ): BlockPosition {
        return (new BlockPosition())
            ->set('name', $title, true)
            ->set('block', $blockId)
            ->set('position', $position)
            ->set('datagroup', $datagroupId)
            ->set('published', $published)
            ->set('ordering', $ordering)
            ;
    }
}
