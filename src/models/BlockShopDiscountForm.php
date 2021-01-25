<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Forms\BlockShopDiscountFormForm;
use VitesseCms\Sef\Helpers\SefHelper;

//TODO move to shop
class BlockShopDiscountForm extends AbstractBlockModel
{
    public function initialize()
    {
        parent::initialize();

        $this->excludeFromCache = true;
    }

    public function parse(Block $block): void
    {
        parent::parse($block);

        $discount = $this->di->shop->discount->loadFromSession();
        if($discount) :
            $block->set(
                'discountUsedText',
                $this->di->language->get('SHOP_DISCOUNT_CODE_BEING_USED', [$discount->_('code')])
            );
        else :
            $form = new BlockShopDiscountFormForm($block);
            $block->set(
                'form',
                $form->renderForm(
                    SefHelper::getComponentURL(
                        'shop',
                        'discount',
                        'parsecode'
                    )
                ));
        endif;
    }
}
