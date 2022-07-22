<?php declare(strict_types=1);

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
        return $this->class !== null;
    }

    public function getMaincontentWrapper(): string
    {
        return $this->maincontentWrapper ?? '';
    }

    public function setTemplate(string $template): Block
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template ?? '';
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

    public function getBlockTypeInstance(): AbstractBlockModel
    {
        $class = $this->getBlock();
        $object = (new $class($this->di->view));
        $object->bind($this->toArray());

        return $object;
    }

    public function beforeSave(): void
    {
        if($this->block !== null):
            $this->di->eventsManager->fire($this->block.':beforeBlockSave', $this->getBlockTypeInstance());
        endif;
    }
}
