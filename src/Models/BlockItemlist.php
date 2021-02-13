<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Enum\ItemListEnum;
use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Forms\BlockItemlistChildrenOfItemSubForm;
use VitesseCms\Block\Forms\BlockItemlistDatagroupSubForm;
use VitesseCms\Block\Forms\BlockItemlistHandpickedSubForm;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Content\Models\Item;
use VitesseCms\Core\Helpers\ItemHelper;
use VitesseCms\Database\Utils\MongoUtil;
use VitesseCms\Media\Enums\AssetsEnum;
use MongoDB\BSON\ObjectID;

//TODO refactor om hem overzichtelijk te maken
class BlockItemlist extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        parent::parse($block);

        $parseList = true;
        $list = $block->_('items');
        switch ($block->_('listMode')) :
            case ItemListEnum::LISTMODE_CURRENT:
                if ($this->view->getVar('currentId')) :
                    $list = [$this->view->getVar('currentId')];
                else :
                    $parseList = false;
                endif;
                break;
            case ItemListEnum::LISTMODE_CHILDREN_OF_ITEM:
                $list = [$block->_('item')];
                break;
            case ItemListEnum::LISTMODE_CURRENT_CHILDREN:
            case ItemListEnum::LISTMODE_CURRENT_PARENT_CHILDREN:
                Item::setFindValue('parentId', $this->view->getVar('currentId'));
                break;
        endswitch;

        if ($parseList) :
            if (\is_array($list) && \count($list) > 0) :
                $ids = [];
                foreach ($list as $id) :
                    if (MongoUtil::isObjectId($id)) :
                        $ids[] = new ObjectID($id);
                    endif;
                endforeach;
                Item::setFindValue('_id', ['$in' => $ids]);
            endif;

            $this->setItemDefaults($block);
            $items = Item::findAll();

            switch ($block->_('listMode')):
                case ItemListEnum::LISTMODE_CHILDREN_OF_ITEM:
                    $this->parseDatafieldValues($block);
                    $this->setItemDefaults($block);
                    Item::setFindValue('parentId', $block->_('item'));
                    $items = Item::findAll();
                    break;
                case ItemListEnum::LISTMODE_CURRENT_PARENT_CHILDREN:
                    if (\count($items) === 0) :
                        $currentItem = $this->view->getVar('currentItem');
                        Item::setFindValue('parentId', $currentItem->_('parentId'));
                        $this->setItemDefaults($block);
                        $items = Item::findAll();
                    endif;
                    break;
                case ItemListEnum::LISTMODE_DATAGROUPS:
                    $ids = [];
                    foreach ($list as $key => $id) :
                        $ids[] = $id;
                    endforeach;
                    Item::setFindValue('datagroup', ['$in' => $ids]);
                    $this->parseDatafieldValues($block);
                    $this->setItemDefaults($block);
                    $items = Item::findAll();
                    break;
                case ItemListEnum::LISTMODE_HANDPICKED:
                    $items = [];
                    foreach ($list as $itemId) :
                        $item = Item::findById($itemId);
                        if ($item) :
                            $items[] = $item;
                        endif;
                    endforeach;
                    break;
            endswitch;
            /** @var Item $item */
            foreach ($items as $key => $item) :
                ItemHelper::parseBeforeMainContent($item);
                $items[$key] = $item;
            endforeach;

            $this->parseReadmore($block);
            $block->set('items', $items);
        endif;

        $markerFile = $this->di->config->get('uploadDir') . 'google-maps-icon-marker.png';
        $markerUrl = $this->di->url->getBaseUri() . 'uploads/' . $this->di->config->get('account') . '/google-maps-icon-marker.png';
        if (is_file($markerFile)) :
            $block->set('googleMapsMarkerIcon', $markerUrl);
        endif;
    }

    public function buildBlockForm(BlockForm $form, Block $item, RepositoryInterface $repositories): void
    {
        parent::buildBlockForm($form, $item, $repositories);

        switch ($item->_('listMode')) :
            case ItemListEnum::LISTMODE_HANDPICKED:
                BlockItemlistHandpickedSubForm::getBlockForm($form, $item, $this->di->repositories);
                break;
            case ItemListEnum::LISTMODE_CHILDREN_OF_ITEM:
                BlockItemlistChildrenOfItemSubForm::getBlockForm($form, $item, $this->di->repositories);
                break;
            case ItemListEnum::LISTMODE_DATAGROUPS:
                BlockItemlistDatagroupSubForm::getBlockForm($form, $item, $this->di->repositories);
                break;
        endswitch;

        if (
            substr_count($item->_('template'), 'card_two_columns')
            || substr_count($item->_('template'), 'card_three_columns')
            || substr_count($item->_('template'), 'card_four_columns')
        ):
            $form->addToggle('Image fullwidth', 'imageFullWidth')
                ->addToggle('Hide intro text', 'hideIntroText');
        endif;
    }

    protected function parseReadmore(Block $block): void
    {
        if ($block->_('readmoreItem')) :
            $item = Item::findById($block->_('readmoreItem'));
            $block->set('readmoreItem', $item);
        endif;
    }

    protected function setItemDefaults(Block $block): void
    {
        if (!empty($block->_('displayOrdering'))) :
            $sort = 1;
            if ($block->_('displayOrderingDirection')) :
                switch ($block->_('displayOrderingDirection')) :
                    case 'oldest':
                        $sort = 1;
                        break;
                    case 'newest':
                        $sort = -1;
                        break;
                    default :
                        $sort = (int)$block->_('displayOrderingDirection');
                endswitch;
            endif;
            Item::addFindOrder($block->_('displayOrdering'), $sort);
        endif;

        if ($block->_('numbersToDisplay')) :
            Item::setFindLimit((int)$block->_('numbersToDisplay'));
        endif;
    }

    protected function parseDatafieldValues(Block $block): void
    {
        if (
            \is_array($block->_('datafieldValue'))
            && \count($block->_('datafieldValue')) > 0
        ) :
            foreach ($block->_('datafieldValue') as $name => $value):
                if (\in_array($value, ['both', 'selected', 'notSelected'], true)) :
                    switch ($value) :
                        case 'selected':
                            Item::setFindValue($name, true);
                            break;
                        case 'notSelected':
                            Item::setFindValue($name, ['$in' => ['', false, null]]);
                            break;
                    endswitch;
                elseif (\in_array($value, ['bothEmpty', 'empty', 'notEmpty'], true)):
                    switch ($value) :
                        case 'empty':
                            Item::setFindValue($name, null);
                            break;
                        case 'notEmpty':
                            Item::setFindValue($name, ['$nin' => [null, '']]);
                            break;
                    endswitch;
                else :
                    $value = str_replace(
                        ['{{currentId}}'],
                        [$this->view->getVar('currentId')],
                        $value
                    );
                    if (trim($value) !== '') :
                        Item::setFindValue($name, ['$in' => explode(',', $value)]);
                    endif;
                endif;
            endforeach;
        endif;
    }

    public function loadAssets(Block $block): void
    {
        parent::loadAssets($block);

        if (substr_count($block->getTemplate(), 'address_list')) :
            $this->di->assets->loadGoogleMaps($this->di->setting->get('GOOGLE_MAPS_APIKEY'));
        endif;
    }
}
