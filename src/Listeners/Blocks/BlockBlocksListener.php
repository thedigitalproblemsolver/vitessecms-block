<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners\Blocks;

use Phalcon\Events\Event;
use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockBlocksListener
{
    /**
     * @var BlockRepository
     */
    private $blockRepository;

    public function __construct(BlockRepository $blockRepository)
    {
        $this->blockRepository = $blockRepository;
    }

    public function buildBlockForm(Event $event, BlockForm $form): void
    {
        $form->addDropdown(
            '%ADMIN_BLOCKS%',
            'blocks',
            (new Attributes())
                ->setMultiple(true)
                ->setInputClass('select2-sortable')
                ->setOptions(ElementHelper::modelIteratorToOptions($this->blockRepository->findAll()))
        );
    }
}
