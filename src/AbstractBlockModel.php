<?php declare(strict_types=1);

namespace VitesseCms\Block;

use Phalcon\Di\Di;
use VitesseCms\Block\Interfaces\BlockModelInterface;
use VitesseCms\Core\Interfaces\BaseObjectInterface;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Core\Traits\BaseObjectTrait;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Helpers\InjectableHelper;
use VitesseCms\Core\Interfaces\InjectableInterface;
use function is_object;

abstract class AbstractBlockModel implements BlockModelInterface, BaseObjectInterface
{
    use BaseObjectTrait;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var bool
     */
    protected $excludeFromCache;

    /**
     * @var InjectableInterface
     */
    protected $di;

    /**
     * @var ViewService
     */
    protected $view;

    public function __construct(ViewService $view, Di $di)
    {
        $this->view = $view;
        $this->di = $di;
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

    public function getCacheKey(Block $block): string
    {
        return $block->getId() . $block->getUpdatedOn()->getTimestamp() . $_SERVER['REQUEST_URI'];
    }

    public function setExcludeFromCache(bool $value): BlockModelInterface
    {
        $this->excludeFromCache = $value;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template ?? 'core';
    }

    public function getDi(): InjectableInterface
    {
        return $this->di;
    }

    public function getTemplateParams(Block $block): array
    {
        return [
            'block' => $block,
            'currentItem' => $this->view->getCurrentItem()
        ];
    }
}
