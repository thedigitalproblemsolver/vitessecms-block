<?php declare(strict_types=1);

namespace VitesseCms\Block\Blocks;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Models\Block;
use VitesseCms\Database\Utils\MongoUtil;

class Blocks extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        parent::parse($block);

        $content = [];
        foreach ((array)$block->_('blocks') as $blockId) :
            if (MongoUtil::isObjectId($blockId)) :
                /** @var Block $tmpBlock */
                $tmpBlock = Block::findById($blockId);
                if ($tmpBlock) :
                    $content[] = [
                        'id' => $tmpBlock->getId(),
                        'name' => $tmpBlock->_('name'),
                        'content' => $this->di->eventsManager->fire(BlockEnum::LISTENER_RENDER_BLOCK->value, $tmpBlock)
                    ];
                endif;
            endif;
        endforeach;

        $block->set('items', $content);
    }
}
