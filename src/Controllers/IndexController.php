<?php

declare(strict_types=1);

namespace VitesseCms\Block\Controllers;

use stdClass;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Content\Enum\ContentEnum;
use VitesseCms\Content\Services\ContentService;
use VitesseCms\Core\AbstractControllerFrontend;
use VitesseCms\Database\DTO\GetRepositoryDTO;
use VitesseCms\Database\Enums\RepositoryEnum;

class IndexController extends AbstractControllerFrontend
{
    private BlockRepository $blockRepository;
    private ContentService $contentService;

    public function onConstruct()
    {
        parent::onConstruct();

        $this->blockRepository = $this->eventsManager->fire(
            RepositoryEnum::GET_REPOSITORY->value,
            new GetRepositoryDTO(Block::class)
        );
        $this->contentService = $this->eventsManager->fire(ContentEnum::ATTACH_SERVICE_LISTENER, new stdClass());
    }

    public function renderAction(string $blockId): void
    {
        if ($this->request->isAjax()) {
            $block = $this->blockRepository->getById($blockId);
            if ($block !== null) {
                $blockType = $block->getBlockTypeInstance();
                $blockType->parse($block);
                $this->jsonResponse($block->_('return'));
            }
        }
    }

    public function renderHtmlAction(string $blockId): void
    {
        if ($this->request->isAjax()) {
            $block = $this->blockRepository->getById($blockId);
            if ($block !== null) {
                $rendering = $this->eventsManager->fire(BlockEnum::LISTENER_RENDER_BLOCK->value, $block);
                echo $this->contentService->parseContent((string)$rendering);
            }
        }

        $this->viewService->disable();
    }
}
