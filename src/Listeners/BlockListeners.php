<?php

declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use VitesseCms\Block\DTO\RenderedBlockDTO;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Helpers\HtmlHelper;
use VitesseCms\Mustache\DTO\RenderTemplateDTO;
use VitesseCms\Mustache\Enum\ViewEnum;

class BlockListeners
{

    public function __construct(private readonly Manager $eventsManager, private readonly bool $isAjax)
    {
    }

    public function renderBlock(Event $event, Block $block): string
    {
        $this->eventsManager->fire($block->getBlock() . ':loadAssets', $block->getBlockTypeInstance(), $block);

        $renderedBlock = $this->render($block);
        $renderedBlockDTO = new RenderedBlockDTO($block, $renderedBlock);

        $this->eventsManager->fire($block->getBlock() . ':afterRenderBlock', $renderedBlockDTO);

        return $renderedBlockDTO->renderedBlock;
    }

    private function render(Block $block): string
    {
        if ($this->isAjax === false && $block->dynamicLoading === true) {
            return '<div id="load_block_' . $block->getId() . '" ' . HtmlHelper::makeAttribute(
                    [$block->getClass(), 'load-block'],
                    'class'
                ) . ' data-block="' . $block->getId() . '"></div>';
        }

        $blockType = $block->getBlockTypeInstance();
        $blockType->parse($block);
        $return = $this->eventsManager->fire(
            ViewEnum::RENDER_TEMPLATE_EVENT,
            new RenderTemplateDTO(
                $blockType->getTemplate(),
                'mustache/src/Template/core/',
                $blockType->getTemplateParams($block)
            )
        );

        if ($block->hasClass() && $block->dynamicLoading === false) :
            $return = '<div ' . HtmlHelper::makeAttribute([$block->getClass()], 'class') . '>' . $return . '</div>';
        endif;

        if (!empty($block->getMaincontentWrapper())) :
            $return = $this->eventsManager->fire(
                ViewEnum::RENDER_TEMPLATE_EVENT,
                new RenderTemplateDTO(
                    'main_content',
                    'mustache/src/Template/core/views/partials/block',
                    ['body' => $return]
                )
            );
        endif;

        return $return;
    }
}