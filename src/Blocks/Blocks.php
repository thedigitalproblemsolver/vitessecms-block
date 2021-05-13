<?php declare(strict_types=1);

namespace VitesseCms\Block\Blocks;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Helpers\BlockHelper;
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
                        'content' => BlockHelper::render(
                            $tmpBlock,
                            $this->di->view,
                            $this->di->cache
                        ),
                    ];
                endif;
            endif;
        endforeach;

        $block->set('items', $content);
    }
}
