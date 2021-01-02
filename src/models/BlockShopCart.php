<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Content\Models\Item;

class BlockShopCart extends AbstractBlockModel
{
    public function initialize()
    {
        parent::initialize();

        $this->setExcludeFromCache(true);
    }

    /**
     * @param Block $block
     *
     * @throws \Phalcon\Mvc\Collection\Exception
     */
    public function parse(Block $block): void
    {
        parent::parse($block);

        $cart = $this->di->shop->cart->getCartFromSession();
        Item::setFindValue('datagroup', $this->di->setting->_('SHOP_DATAGROUP_CHECKOUT'));
        $checkoutPage = Item::findFirst();

        $template = explode('/', $this->_('template'));
        $template = array_reverse($template);
        switch ($template[0]) :
            case 'mini':
            case 'core':
            default:
                $block->set('cartText', $cart->getTotalText());
                $block->set('cartLink', $checkoutPage->_('slug'));
                break;
            case 'large':
                if (!$cart->hasProducts()) :
                    $block->set('EmptyCartPage', Item::findById($this->di->setting->_('SHOP_PAGE_EMPTYCART')));
                else :
                    Item::setFindValue('datagroup', $this->di->setting->_('SHOP_DATAGROUP_PACKING'));
                    $block->set('packingItems', Item::findAll());
                    if ($block->_('packingItems')) :
                        $block->set('packingTeaser', '%SHOP_PACKING_TEASER%');
                    endif;

                    $this->di->shop->cart->setBlockBasics($block, $cart);

                    if (!$this->di->request->get('embedded', 'int', 0)) :
                        $block->set('checkoutLink', $this->di->shop->checkout->getNextStep()->_('slug'));
                        $block->set('checkoutBar', true);
                    endif;
                endif;
                break;
        endswitch;
    }
}
