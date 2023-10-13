<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners\ContentTags;

use Phalcon\Events\Manager;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Content\DTO\TagListenerDTO;
use VitesseCms\Content\Helpers\EventVehicleHelper;
use VitesseCms\Content\Listeners\ContentTags\AbstractTagListener;

class TagBlockListener extends AbstractTagListener
{
    public function __construct(
        private readonly BlockRepository $blockRepository,
        private readonly Manager $eventsManager
    ) {
        $this->name = 'BLOCK';
    }

    protected function parse(EventVehicleHelper $contentVehicle, TagListenerDTO $tagListenerDTO): void
    {
        $tagOptions = explode(';', $tagListenerDTO->getTagString());
        $block = $this->blockRepository->getById($tagOptions[1]);
        $replace = '';

        if($block !== null) {
            $replace = $this->eventsManager->fire(BlockEnum::LISTENER_RENDER_BLOCK->value, $block);
        }

        $contentVehicle->setContent(
            str_replace(
                '{' . $this->name . $tagListenerDTO->getTagString() . '}',
                $replace,
                $contentVehicle->getContent()
            )
        );
    }
}