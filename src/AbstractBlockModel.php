<?php declare(strict_types=1);

namespace VitesseCms\Block;

use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Interfaces\BlockModelInterface;
use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Core\Interfaces\BaseObjectInterface;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Core\Traits\BaseObjectTrait;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Helpers\InjectableHelper;
use VitesseCms\Core\Interfaces\InjectableInterface;

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

    public function __construct(ViewService $view)
    {
        $this->view = $view;
        $this->initialize();
    }


    public function initialize()
    {
        $this->excludeFromCache = false;
        $this->template = 'core';

        if (!\is_object($this->di)) :
            $this->di = new InjectableHelper();
        endif;
    }

    public function parse(Block $block): void
    {
        if (!empty($block->getTemplate())) :
            $this->template = $block->getTemplate();
        endif;
    }

    public function buildBlockForm(BlockForm $form, Block $item, RepositoryInterface $repositories): void
    {
        $reflect = new \ReflectionClass($this);
        /** @var BlockSubFormInterface $class */
        $class = 'VitesseCms\\Block\\Forms\\'.$reflect->getShortName().'SubForm';
        if(class_exists($class)) :
            $class::getBlockForm($form, $item, $repositories);
        endif;

        $class = 'VitesseCms\\'.
            ucwords($form->configuration->getAccount()).
            '\\Block\\Forms\\'.
            $reflect->getShortName().
            'SubForm'
        ;

        if(class_exists($class)) :
            $class::getBlockForm($form, $item, $repositories);
        endif;
    }

    public function loadAssets(Block $block): void
    {
        if (substr_count($block->_('template'), 'lazyload')) :
            $this->di->assets->load('lazyload');
        endif;
    }

    public function getCacheKey(Block $block): string
    {
        return $block->getId() . $block->_('updatedOn') . $_SERVER['REQUEST_URI'];
    }

    public function setExcludeFromCache(bool $value): BlockModelInterface
    {
        $this->excludeFromCache = $value;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template??'core';
    }
}
