<?php

declare(strict_types=1);

namespace VitesseCms\Block\Listeners\Blocks;

use Phalcon\Events\Event;
use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockBlocksListener
{
    public function __construct(private readonly BlockRepository $blockRepository)
    {
    }

    public function buildBlockForm(Event $event, BlockForm $form): void
    {
        $form->addDropdown(
            '%ADMIN_BLOCKS%',
            'blocks',
            (new Attributes())
                ->setMultiple()
                ->setInputClass('select2-sortable')
                ->setOptions(ElementHelper::modelIteratorToOptions($this->blockRepository->findAll()))
        );
    }
}
