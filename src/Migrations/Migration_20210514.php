<?php declare(strict_types=1);

namespace VitesseCms\Block\Migrations;

use VitesseCms\Block\Repositories\AdminRepositoryCollection;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Cli\Services\TerminalServiceInterface;
use VitesseCms\Configuration\Services\ConfigServiceInterface;
use VitesseCms\Install\Interfaces\MigrationInterface;

class Migration_20210514 implements MigrationInterface
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
        $search = [
            'VitesseCms\Block\Models\BlockBlocks',
            'VitesseCms\Block\Models\BlockAffiliateInitialize',
            'VitesseCms\Block\Models\BlockAffiliateOrderOverview',
            'VitesseCms\Block\Models\BlockBreadcrumbs',
            'VitesseCms\Block\Models\BlockDatagroup',
            'VitesseCms\Block\Models\BlockFilter',
            'VitesseCms\Block\Models\BlockFilterResult',
        ];
        $replace = [
            'VitesseCms\Block\Blocks\Blocks',
            'VitesseCms\Shop\Blocks\AffiliateInitialize',
            'VitesseCms\Shop\Blocks\AffiliateOrderOverview',
            'VitesseCms\Content\Blocks\Breadcrumbs',
            'VitesseCms\Datagroup\Blocks\Datagroup',
            'VitesseCms\Content\Blocks\Filter',
            'VitesseCms\Content\Blocks\FilterResult',
        ];
        while ($blocks->valid()):
            $block = $blocks->current();
            $newBlockType = str_replace($search, $replace, $block->getBlock());
            if (substr_count($newBlockType, 'VitesseCms\\Block\\Models\\') === 0) :
                $block->setBlock($newBlockType)->save();
            else :
                $terminalService->printError('wrong blockType "' . $newBlockType . '" for block "' . $block->getNameField() . '"');
                $result = false;
            endif;

            $blocks->next();
        endwhile;

        $terminalService->printMessage('Block classnames repaired');

        return $result;
    }
}