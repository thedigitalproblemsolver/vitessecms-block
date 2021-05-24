<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use Phalcon\Events\Event;
use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Models\Block;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockBlocksListener
{
    public function buildBlockForm(Event $event, BlockForm $form, Block $block): void
    {
        $form->addDropdown(
            '%ADMIN_BLOCKS%',
            'blocks',
            (new Attributes())
                ->setMultiple(true)
                ->setInputClass('select2-sortable')
                ->setOptions(ElementHelper::modelIteratorToOptions($block->getDi()->repositories->block->findAll()))
        );
    }
}
