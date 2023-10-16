<?php

declare(strict_types=1);

namespace VitesseCms\Block\Migrations;

use stdClass;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Database\AbstractMigration;

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
            BlockEnum::LISTENER_GET_REPOSITORY->value,
            source: new stdClass()
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