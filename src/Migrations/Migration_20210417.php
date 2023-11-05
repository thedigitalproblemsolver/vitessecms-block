<?php

declare(strict_types=1);

namespace VitesseCms\Block\Migrations;

use stdClass;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Database\AbstractMigration;

class Migration_20210417 extends AbstractMigration
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
        $blockRepository = $this->eventsManager->fire(
            BlockEnum::GET_REPOSITORY->value,
            source: new stdClass()
        );

        $result = true;
        $blocks = $blockRepository->findAll(null, false);
        $dir = str_replace(
            'install/src/Migrations',
            'core/src/Services/../../../../../vendor/vitessecms/mustache/src/',
            __DIR__
        );
        $search = [
            'Template/core/',
            'templates/default/',
            $dir
        ];
        $replace = [
            '',
            '',
            ''
        ];
        while ($blocks->valid()):
            $block = $blocks->current();
            $template = str_replace($search, $replace, $block->getTemplate());
            if (substr($template, 0, 6) === "views/") :
                $block->setTemplate($template)->save();
            else :
                $this->terminalService->printError(
                    'wrong template "' . $template . '" for block "' . $block->getNameField() . '"'
                );
                $result = false;
            endif;

            $blocks->next();
        endwhile;

        $this->terminalService->printMessage('Block template repaired');

        return $result;
    }
}