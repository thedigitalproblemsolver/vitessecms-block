<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Media\Helpers\VideoEmbeddHelper;

class BlockVideo extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        parent::parse($block);

        $block->set('videoCode', VideoEmbeddHelper::getEmbeddCode(
            $this->view,
            $block->_('videoUrl')
        ));
    }
}
