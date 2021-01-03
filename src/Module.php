<?php declare(strict_types=1);

namespace VitesseCms\Block;

use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Block\Repositories\RepositoryCollection;
use VitesseCms\Communication\Repositories\NewsletterRepository;
use VitesseCms\Content\Repositories\ItemRepository;
use VitesseCms\Core\AbstractModule;
use VitesseCms\Core\Repositories\DatafieldRepository;
use VitesseCms\Core\Repositories\DatagroupRepository;
use Phalcon\DiInterface;

class Module extends AbstractModule
{
    public function registerServices(DiInterface $di, string $string = null)
    {
        parent::registerServices($di, 'Block');
        $di->setShared('repositories', new RepositoryCollection(
            new BlockPositionRepository(),
            new BlockRepository(),
            new NewsletterRepository(),
            new DatagroupRepository(),
            new ItemRepository(),
            new DatafieldRepository()
        ));
    }
}
