<?php declare(strict_types=1);

namespace VitesseCms\Block;

use VitesseCms\Admin\Utils\AdminUtil;
use VitesseCms\Block\Repositories\AdminRepositoryCollection;
use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Block\Repositories\RepositoryCollection;
use VitesseCms\Content\Repositories\ItemRepository;
use VitesseCms\Core\AbstractModule;
use VitesseCms\Database\Enums\DatabaseEnum;
use VitesseCms\Datafield\Repositories\DatafieldRepository;
use VitesseCms\Datagroup\Repositories\DatagroupRepository;
use Phalcon\Di\DiInterface;
use VitesseCms\Mustache\Repositories\LayoutRepository;

class Module extends AbstractModule
{
    public function registerServices(DiInterface $di, string $string = null)
    {
        parent::registerServices($di, 'Block');
        if(AdminUtil::isAdminPage()) :
            $di->setShared(DatabaseEnum::REPOSITORIES, new AdminRepositoryCollection(
                new BlockRepository(),
                new LayoutRepository(),
                new DatagroupRepository(),
                new BlockPositionRepository(),
                new ItemRepository()
            ));
        else :
            $di->setShared(DatabaseEnum::REPOSITORIES, new RepositoryCollection(
                new BlockPositionRepository(),
                new BlockRepository(),
                new DatagroupRepository(),
                new DatafieldRepository()
            ));
        endif;
    }
}
