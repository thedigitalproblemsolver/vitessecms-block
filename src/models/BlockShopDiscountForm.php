<?php

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Block\Forms\BlockShopDiscountFormForm;
use VitesseCms\Language\Helpers\LanguageHelper;
use VitesseCms\Sef\Helpers\SefHelper;

/**
 * Class BlockShopDiscountForm
 */
class BlockShopDiscountForm extends AbstractBlockModel
{

    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        parent::initialize();

        $this->excludeFromCache = true;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Block $block): void
    {
        parent::parse($block);

        $discount = $this->di->shop->discount->loadFromSession();
        if($discount) :
            $block->set('discountUsedText', LanguageHelper::_('SHOP_DISCOUNT_CODE_BEING_USED', [$discount->_('code')]));
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
