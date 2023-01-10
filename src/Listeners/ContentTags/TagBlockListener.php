<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners\ContentTags;

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Content\DTO\TagListenerDTO;
use VitesseCms\Content\Helpers\EventVehicleHelper;
use VitesseCms\Content\Listeners\ContentTags\AbstractTagListener;
use VitesseCms\Mustache\DTO\RenderTemplateDTO;
use VitesseCms\Mustache\Enum\ViewEnum;
use stdClass;

class TagBlockListener extends AbstractTagListener
{
    /**
     * @var BlockRepository
     */
    private $blockRepository;

    /**
     * @var Manager
     */
    private $eventsManager;

    /**
     * @var AssetsService
     */
    private $assetsService;

    /**
     * @var Manager
     */
    private $eventManager;

    public function __construct(BlockRepository $blockRepository, Manager $eventManager)
    {
        $this->name = 'BLOCK';
        $this->blockRepository = $blockRepository;
        $this->eventsManager = $eventManager;
    }

    protected function parse(EventVehicleHelper $contentVehicle, TagListenerDTO $tagListenerDTO): void
    {
        $tagOptions = explode(';', $tagListenerDTO->getTagString());
        $block = $this->blockRepository->getById($tagOptions[1]);
        $replace = $this->eventsManager->fire(BlockEnum::BLOCK_LISTENER . ':renderBlock', $block);

        $contentVehicle->setContent(
            str_replace(
                '{' . $this->name . $tagListenerDTO->getTagString() . '}',
                $replace,
                $contentVehicle->getContent()
            )
        );
    }
}