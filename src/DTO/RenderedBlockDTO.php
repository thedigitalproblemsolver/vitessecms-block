<?php
declare(strict_types=1);

namespace VitesseCms\Block\DTO;

use VitesseCms\Block\Models\Block;

final class RenderedBlockDTO
{
    public function __construct(
        public readonly Block $blockModel,
        public string $renderedBlock
    ) {
    }
}