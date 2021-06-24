<?php declare(strict_types=1);

namespace VitesseCms\Block\Migrations;

use VitesseCms\Block\Repositories\AdminRepositoryCollection;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Cli\Services\TerminalServiceInterface;
use VitesseCms\Configuration\Services\ConfigServiceInterface;
use VitesseCms\Install\Interfaces\MigrationInterface;

class Migration_20210417 implements MigrationInterface
{
    /**
     * @var AdminRepositoryCollection
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new AdminRepositoryCollection(
            new BlockRepository()
        );
    }

    public function up(
        ConfigServiceInterface $configService,
        TerminalServiceInterface $terminalService
    ): bool
    {
        $result = true;
        if (!$this->parseBlocks($terminalService)) :
            $result = false;
        endif;

        return $result;
    }

    private function parseBlocks(TerminalServiceInterface $terminalService): bool
    {
        $result = true;
        $blocks = $this->repository->block->findAll(null, false);
        $dir = str_replace('install/src/Migrations', 'core/src/Services/../../../../../vendor/vitessecms/mustache/src/', __DIR__);
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
                $terminalService->printError('wrong template "' . $template . '" for block "' . $block->getNameField() . '"');
                $result = false;
            endif;

            $blocks->next();
        endwhile;

        $terminalService->printMessage('Block template repaired');

        return $result;
    }
}