<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use VitesseCms\Block\Models\Block;
use VitesseCms\Mustache\DTO\RenderTemplateDTO;
use VitesseCms\Mustache\Enum\ViewEnum;

class BlockListeners
{
    /**
     * @var Manager
     */
    private $eventManager;

    public function __construct(Manager $eventManager)
    {
        $this->eventsManager = $eventManager;
    }

    public function renderBlock(Event $event, Block $block): string
    {
        $blockType = $block->getBlockTypeInstance();
        $blockType->parse($block);
        $return = $this->eventsManager->fire(ViewEnum::RENDER_TEMPLATE_EVENT, new RenderTemplateDTO(
            $blockType->getTemplate(),
            'mustache/src/Template/core/',
            $blockType->getTemplateParams($block)
        ));

        if (!empty($block->getMaincontentWrapper())) :
            $return = $this->eventsManager->fire(ViewEnum::RENDER_TEMPLATE_EVENT, new RenderTemplateDTO(
                'main_content',
                'mustache/src/Template/core/views/partials/block',
                ['body' => $return]
            ));
        endif;

        return $return;
    }
}