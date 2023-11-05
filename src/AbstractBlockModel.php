<?php

declare(strict_types=1);

namespace VitesseCms\Block;

use Phalcon\Di\Di;
use Phalcon\Events\Manager;
use VitesseCms\Block\Interfaces\BlockModelInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Interfaces\BaseObjectInterface;
use VitesseCms\Core\Interfaces\InjectableInterface;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Core\Traits\BaseObjectTrait;

abstract class AbstractBlockModel implements BlockModelInterface, BaseObjectInterface
{
    use BaseObjectTrait;

    protected string $template;
    protected bool $excludeFromCache;
    protected Manager $eventsManager;

    public function __construct(
        protected readonly ViewService $view,
        protected readonly Di $di
    ) {
        $this->eventsManager = $this->di->get('eventsManager');
        $this->initialize();
    }

    public function initialize()
    {
        $this->excludeFromCache = false;
        $this->template = 'core';
    }

    public function parse(Block $block): void
    {
        if (!empty($block->getTemplate())) :
            $this->template = $block->getTemplate();
        endif;
    }

    public function getTemplate(): string
    {
        return $this->template ?? 'core';
    }

    public function getCacheKey(Block $block): string
    {
        return $block->getId() . $block->getUpdatedOn()->getTimestamp() . $_SERVER['REQUEST_URI'];
    }

    public function setExcludeFromCache(bool $value): BlockModelInterface
    {
        $this->excludeFromCache = $value;

        return $this;
    }

    public function getTemplateParams(Block $block): array
    {
        return [
            'block' => $block,
            'currentItem' => $this->view->getCurrentItem(),
            'BASE_URI' => $this->getDi()->get('url')->getBaseUri(),
            'uploads_uri' => $this->getDi()->get('configuration')->getUploadUri()
        ];
    }

    public function getDi(): InjectableInterface
    {
        return $this->di;
    }
}
