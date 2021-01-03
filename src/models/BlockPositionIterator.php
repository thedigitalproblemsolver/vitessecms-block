<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\Helpers\BlockHelper;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Core\Helpers\HtmlHelper;
use VitesseCms\Core\Helpers\ItemHelper;
use VitesseCms\Core\Models\Datagroup;
use VitesseCms\Core\Services\CacheService;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\User\Models\User;
use VitesseCms\User\Utils\PermissionUtils;
use Phalcon\Http\Request;

class BlockPositionIterator extends \ArrayIterator
{
    public function __construct(array $blockPositions)
    {
        parent::__construct($blockPositions);
    }

    public function current(): BlockPosition
    {
        return parent::current();
    }
}
