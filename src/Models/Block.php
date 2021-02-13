<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

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
        return $this->class??'';
    }

    public function getMaincontentWrapper(): string
    {
        return $this->maincontentWrapper??'';
    }

    public function getTemplate(): string
    {
        return $this->template??'';
    }

    public function getBlock(): string
    {
        return $this->block?str_replace('Modules','VitesseCms',$this->block):'';
    }
}
