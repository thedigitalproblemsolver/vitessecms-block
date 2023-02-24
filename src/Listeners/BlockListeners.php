<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Core\Helpers\HtmlHelper;
use VitesseCms\Mustache\DTO\RenderTemplateDTO;
use VitesseCms\Mustache\Enum\ViewEnum;
use VitesseCms\User\Blocks\UserLogin;

class BlockListeners
{

    public function __construct(private readonly Manager $eventsManager, private readonly BlockRepository $blockRepository){}

    public function getRepository(): BlockRepository
    {
        return $this->blockRepository;
    }

    public function renderBlock(Event $event, Block $block): string
    {
        $this->eventsManager->fire($block->getBlock() . ':loadAssets', $block->getBlockTypeInstance(), $block);

        return $this->render($block);
    }

    private function render(Block $block): string
    {
        $blockType = $block->getBlockTypeInstance();
        $blockType->parse($block);
        $return = $this->eventsManager->fire(ViewEnum::RENDER_TEMPLATE_EVENT, new RenderTemplateDTO(
            $blockType->getTemplate(),
            'mustache/src/Template/core/',
            $blockType->getTemplateParams($block)
        ));

        if ($block->hasClass()) :
            $return = '<div ' . HtmlHelper::makeAttribute([$block->getClass()], 'class') . '>' . $return . '</div>';
        endif;

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