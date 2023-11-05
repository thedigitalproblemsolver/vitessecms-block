<?php

declare(strict_types=1);

namespace VitesseCms\Block\Blocks;

use Phalcon\Di\Di;
use stdClass;
use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Database\Utils\MongoUtil;

class Blocks extends AbstractBlockModel
{
    private readonly BlockRepository $blockRepository;

    public function __construct(ViewService $view, Di $di)
    {
        parent::__construct($view, $di);

        $this->blockRepository = $this->eventsManager->fire(BlockEnum::GET_REPOSITORY->value, new stdClass());
    }

    public function parse(Block $block): void
    {
        parent::parse($block);

        $content = [];
        foreach ($block->getArray('blocks') as $blockId) :
            if (MongoUtil::isObjectId($blockId)) :
                $tmpBlock = $this->blockRepository->getById($blockId);
                if ($tmpBlock) :
                    $content[] = [
                        'id' => $tmpBlock->getId(),
                        'name' => $tmpBlock->getNameField(),
                        'content' => $this->eventsManager->fire(BlockEnum::LISTENER_RENDER_BLOCK->value, $tmpBlock)
                    ];
                endif;
            endif;
        endforeach;

        $block->set('items', $content);
    }
}
