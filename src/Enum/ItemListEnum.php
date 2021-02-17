<?php declare(strict_types=1);

namespace VitesseCms\Block\Enum;

use VitesseCms\Core\AbstractEnum;

class ItemListEnum extends AbstractEnum
{
    public const OPTION_CURRENT_ITEM = '{{currentId}}';

    public const LISTMODE_CURRENT = 'current';
    public const LISTMODE_CHILDREN_OF_ITEM = 'childrenOfItem';
    public const LISTMODE_CURRENT_CHILDREN = 'currentChildren';
    public const LISTMODE_CURRENT_PARENT_CHILDREN = 'currentParentChildren';
    public const LISTMODE_CURRENT_CHILDREN_OF_PARENT = 'currentChildrenOfParent';
    public const LISTMODE_HANDPICKED = 'handpicked';
    public const LISTMODE_DATAGROUPS = 'datagroups';

    public const LISTMODES = [
        self::LISTMODE_CURRENT                    => '%ADMIN_ITEMLIST_MODE_CURRENT_ITEM%',
        self::LISTMODE_CHILDREN_OF_ITEM           => '%ADMIN_ITEMLIST_MODE_CHILDREN_OF_ITEM%',
        self::LISTMODE_CURRENT_CHILDREN           => '%ADMIN_ITEMLIST_MODE_CHILDREN_OF_CURRENT_ITEM%',
        self::LISTMODE_CURRENT_PARENT_CHILDREN    => '%ADMIN_ITEMLIST_MODE_CHILDREN_OF_ITEM_OR_SELECTED%',
        self::LISTMODE_CURRENT_CHILDREN_OF_PARENT => '%ADMIN_ITEMLIST_MODE_CHILDREN_FROM_PARENT_OF_ITEM_OR_SELECTED%',
        self::LISTMODE_HANDPICKED                 => '%ADMIN_ITEMLIST_MODE_HANDPICKED_ITEMS%',
        self::LISTMODE_DATAGROUPS                 => '%ADMIN_ITEMLIST_MODE_DATAGROUP%',
    ];
}
