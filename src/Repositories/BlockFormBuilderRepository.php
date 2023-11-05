<?php

declare(strict_types=1);

namespace VitesseCms\Block\Repositories;

use VitesseCms\Block\Factories\BlockFormBuilderFactory;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Form\Blocks\FormBuilder;

class BlockFormBuilderRepository
{
    public function __construct(
        private readonly BlockRepository $blockRepository,
        private readonly ViewService $viewService
    ) {
    }

    public function getById(string $id): ?FormBuilder
    {
        $block = $this->blockRepository->getById($id);
        if ($block !== null):
            $objectClass = $block->getBlock();
            if (class_exists($objectClass)) :
                return BlockFormBuilderFactory::createFromBlock($block, $this->viewService);
            endif;
        endif;

        return null;
    }
}
