<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Helpers\BlockHelper;
use VitesseCms\Database\Utils\MongoUtil;

class BlockBlocks extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        parent::parse($block);

        $content = [];
        foreach ((array)$block->_('blocks') as $blockId) :
            if(MongoUtil::isObjectId($blockId)) :
                /** @var Block $tmpBlock */
                $tmpBlock = Block::findById($blockId);
                if($tmpBlock) :
                    $content[] = [
                        'id'      => $tmpBlock->getId(),
                        'name'    => $tmpBlock->_('name'),
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
