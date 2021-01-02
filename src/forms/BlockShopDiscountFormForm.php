<?php

namespace VitesseCms\Block\Forms;

use VitesseCms\Database\AbstractCollection;
use VitesseCms\Form\AbstractForm;

/**
 * Class BlockShopDiscountFormForm
 */
class BlockShopDiscountFormForm extends AbstractForm
{

    /**
     * @param AbstractCollection $item
     */
    public function initialize(AbstractCollection $item)
    {
        $this->_(
            'text',
            'Uw kortingscode',
            'code',
            [
                'required' => 'required',
            ]
        )->_(
            'submit',
            '%CORE_SAVE%'
        );
    }
}
