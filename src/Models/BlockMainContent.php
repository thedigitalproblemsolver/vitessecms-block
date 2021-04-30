<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Content\Models\Item;
use VitesseCms\Core\Helpers\ItemHelper;
use VitesseCms\Datagroup\Models\Datagroup;
use VitesseCms\Media\Enums\AssetsEnum;
use function count;

class BlockMainContent extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        parent::parse($block);

        /** @var Item $item */
        $item = $this->view->getCurrentItem();
        if ($item) :
            /** @var Datagroup $datagroup */
            $datagroup = Datagroup::findById($item->getDatagroup());
            if ($datagroup->_('template')) :
                $this->extendBlock($datagroup, $block);
                $this->template = $datagroup->_('template');
            endif;
            $this->setMetaInformation($item);

            if (substr_count($datagroup->_('template'), 'address')) :
                $markerFile = $this->di->config->get('uploadDir') . 'google-maps-icon-marker.png';
                $markerUrl = $this->di->url->getBaseUri() . 'uploads/' . $this->di->config->get('account') . '/google-maps-icon-marker.png';
                if (is_file($markerFile)) :
                    $block->set('googleMapsMarkerIcon', $markerUrl);
                endif;
            endif;
        endif;
    }

    protected function extendBlock(Datagroup $datagroup, Block $block): void
    {
        if (substr_count($datagroup->_('template'), 'overview')) :
            Item::setFindValue('parentId', (string)$this->view->getCurrentItem()->getId());
            Item::addFindOrder('name', 1);
            Item::setFindLimit(9999);
            $items = Item::findAll();
            $designMapper = [];
            foreach ($items as $key => $item) :
                //TODO mooier oplossen
                if (isset($item->outOfStock) && $item->_('outOfStock')) :
                    unset($items[$key]);
                else :
                    ItemHelper::parseBeforeMainContent($item);
                    $items[$key] = $item;
                endif;

                //TODO naar event?
                if (
                    substr_count($datagroup->_('template'), 'shop_clothing_design_overview')
                    && !empty($item->_('design'))
                ) :
                    if (!isset($designMapper[$item->_('design')])) :
                        if (isset($items[$key])) :
                            $designMapper[$item->_('design')] = $key;
                            $items[$key]->set('designItems', []);
                            $items[$designMapper[$item->_('design')]]->designItems[] = $item;
                        endif;
                    else :
                        $items[$designMapper[$item->_('design')]]->designItems[] = $item;
                        unset($items[$key]);
                    endif;
                endif;
            endforeach;

            if (substr_count($datagroup->_('template'), 'shop_clothing_design_overview')) :
                foreach ($designMapper as $designId => $itemKey) :
                    if (
                        isset($items[$itemKey]->designItems)
                        && count($items[$itemKey]->designItems) === 1
                    ) :
                        unset($items[$itemKey]->designItems);
                    else :
                        $items[$itemKey]->hasDesignItems = true;
                    endif;
                endforeach;
            endif;
            $block->set('items', array_values($items));
        endif;
        $block->set('imageFullWidth', true);
    }

    protected function setMetaInformation(Item $item): void
    {
        $this->view->set('metaTitle', $item->_('name'));
        $this->view->set('metaKeywords', $item->_('name'));
        $this->view->set('metaDescription', $item->_('introtext'));
    }

    public function loadAssets(Block $block): void
    {
        parent::parse($block);

        /** @var Item $item */
        $item = $this->view->getCurrentItem();
        if ($item) :
            $datagroup = Datagroup::findById($item->getDatagroup());
            if (substr_count($datagroup->_('template'), 'address')) :
                $this->di->assets->loadGoogleMaps($this->di->setting->get('GOOGLE_MAPS_APIKEY'));
            endif;

            if (substr_count($datagroup->_('template'), 'shop_clothing_design_overview')) :
                $this->di->assets->load(AssetsEnum::LAZYLOAD);
            endif;
        endif;
    }

    public function getCacheKey(Block $block): string
    {
        return parent::getCacheKey($block) . $this->view->getCurrentItem()->getUpdatedOn()->getTimestamp();
    }
}
