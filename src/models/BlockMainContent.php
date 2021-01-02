<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Content\Models\Item;
use VitesseCms\Core\Helpers\ItemHelper;
use VitesseCms\Core\Models\Datagroup;
use VitesseCms\Media\Enums\AssetsEnum;

class BlockMainContent extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        $this->fixBloembollen();
        parent::parse($block);

        /** @var Item $item */
        $item = $this->view->getVar('currentItem');
        if ($item) :
            /** @var Datagroup $datagroup */
            $datagroup = Datagroup::findById($item->_('datagroup'));
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

    //TODO verplaaten naar??
    protected function setMetaInformation(Item $item): void
    {
        $this->view->set('metaTitle', $item->_('name'));
        $this->view->set('metaKeywords', $item->_('name'));
        $this->view->set('metaDescription', $item->_('introtext'));
    }

    protected function extendBlock(Datagroup $datagroup, Block $block): void
    {
        if (substr_count($datagroup->_('template'), 'overview')) :
            Item::setFindValue('parentId', (string)$this->view->getVar('currentItem')->getId());
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
                        && \count($items[$itemKey]->designItems) === 1
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

    public function loadAssets(Block $block): void
    {
        parent::parse($block);

        /** @var Item $item */
        $item = $this->view->getVar('currentItem');
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

    /**
     * @TODO hoe dit flexibel op te lossen? ? accoutn evente listener
     */
    protected function fixBloembollen(): void
    {
        if (
            $this->view->getVar('currentItem')
            && substr_count($this->view->getVar('currentItem')->_('bodytext'), 'bloembollenkopen') > 0
        ) :
            $bodyText = $this->view->getVar('currentItem')->_('bodytext');
            $bodyText = str_replace(
                ['href=http://www.bloembollenkopen.nl', '.html>'],
                [
                    'href="http://www.bloembollenkopen.nl/bol/?tt=4889_253953_258885_&r=http://www.bloembollenkopen.nl',
                    '.html" target="_blank" rel="nofollow" >',
                ],
                $bodyText
            );

            $this->view->getVar('currentItem')->set(
                'bodytext',
                $bodyText,
                true,
                $this->di->configuration->getLanguageShort()
            );
        endif;
    }

    public function getCacheKey(Block $block): string
    {
        return parent::getCacheKey($block) . $this->view->getVar('currentItem')->_('updatedOn');
    }
}
