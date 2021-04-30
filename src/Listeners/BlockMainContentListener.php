<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use Phalcon\Events\Event;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Models\BlockMainContent;
use VitesseCms\Datagroup\Models\Datagroup;

class BlockMainContentListener
{
    public function parse(Event $event, BlockMainContent $blockMainContent, Block $block): void
    {
        $this->handleAddressTemplate($blockMainContent, $block);
    }

    public function loadAssets(Event $event, BlockMainContent $blockMainContent, Block $block): void
    {
        if ($blockMainContent->getDi()->view->hasCurrentItem()) :
            $item = $blockMainContent->getDi()->view->getCurrentItem();
            /** @var Datagroup $datagroup */
            $datagroup = $blockMainContent->getDi()->repositories->datagroup->getById($item->getDatagroup());
            if (substr_count($datagroup->getTemplate(), 'address')) :
                $blockMainContent->getDi()->assets->loadGoogleMaps(
                    $blockMainContent->getDi()->setting->get('GOOGLE_MAPS_APIKEY')
                );
            endif;

            if (substr_count($datagroup->getTemplate(), 'shop_clothing_design_overview')) :
                $blockMainContent->getDi()->assets->loadLazyLoading();
            endif;
        endif;
    }

    private function handleAddressTemplate(BlockMainContent $blockMainContent, Block $block):void
    {
        if (substr_count($blockMainContent->getTemplate(), 'address')) :
            $markerFile = $blockMainContent-getDi()->configuration->getUploadDir() . 'google-maps-icon-marker.png';
            $markerUrl = $blockMainContent->getDi()->configuration->getUploadUri() . '/google-maps-icon-marker.png';
            if (is_file($markerFile)) :
                $block->set('googleMapsMarkerIcon', $markerUrl);
            endif;
        endif;
    }
}
