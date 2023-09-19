<?php

declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Database\AbstractCollection;

class Block extends AbstractCollection
{
    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $maincontentWrapper;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string;
     */
    public $block;

    public function getClass(): string
    {
        return $this->class ?? '';
    }

    public function hasClass(): bool
    {
        return $this->class !== null && !empty($this->class);
    }

    public function getMaincontentWrapper(): string
    {
        return $this->maincontentWrapper ?? '';
    }

    public function getTemplate(): string
    {
        return $this->template ?? '';
    }

    public function setTemplate(string $template): Block
    {
        $this->template = $template;

        return $this;
    }

    public function beforeSave(): void
    {
        if ($this->block !== null && class_exists($this->block)):
            $this->getDI()->get('eventsManager')->fire(
                $this->block . ':beforeBlockSave',
                $this->getBlockTypeInstance()
            );
        endif;
    }

    public function getBlockTypeInstance(): AbstractBlockModel
    {
        $class = $this->getBlock();
        $object = (new $class($this->getDI()->get('view'), $this->getDI()));
        $object->bind($this->toArray());

        return $object;
    }

    public function getBlock(): ?string
    {
        return $this->block;
    }

    public function setBlock(string $block): Block
    {
        $this->block = $block;

        return $this;
    }
}
