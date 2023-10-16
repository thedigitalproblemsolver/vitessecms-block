<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners\Repositories;

use Phalcon\Events\Event;
use VitesseCms\Block\Repositories\BlockFormBuilderRepository;

class BlockFormBuilderRepositoryListener {
    public function __construct(private readonly BlockFormBuilderRepository $blockFormBuilderRepository){}

    public function getRepository(): BlockFormBuilderRepository
    {
        return $this->blockFormBuilderRepository;
    }
}