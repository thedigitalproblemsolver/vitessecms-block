<?php

declare(strict_types=1);

namespace VitesseCms\Block\Migrations;

use VitesseCms\Block\Models\Block;
use VitesseCms\Database\AbstractMigration;
use VitesseCms\Database\DTO\GetRepositoryDTO;
use VitesseCms\Database\Enums\RepositoryEnum;

class Migration_20210513 extends AbstractMigration
{
    public function up(): bool
    {
        $result = true;
        if (!$this->parseBlocks()) :
            $result = false;
        endif;

        return $result;
    }

    private function parseBlocks(): bool
    {
        $result = true;
        $blockRepository = $this->eventsManager->fire(
            RepositoryEnum::GET_REPOSITORY->value,
            new GetRepositoryDTO(Block::class)
        );

        $blocks = $blockRepository->findAll(null, false);
        $search = ['VitesseCms\Block\Models\BlockItemlist'];
        $replace = ['VitesseCms\Content\Blocks\Itemlist'];
        while ($blocks->valid()):
            $block = $blocks->current();
            $newBlockType = str_replace($search, $replace, $block->getBlock());
            $block->setBlock($newBlockType)->save();

            $blocks->next();
        endwhile;

        $this->terminalService->printMessage('Block Itemlist repaired');

        return $result;
    }
}