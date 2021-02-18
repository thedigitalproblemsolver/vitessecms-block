<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Database\AbstractCollection;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Models\Attributes;

class BlockShopDiscountFormForm extends AbstractForm
{
    public function initialize(AbstractCollection $item)
    {
        $this->addText('Uw kortingscode', 'code', (new Attributes())->setRequired(true))
            ->addSubmitButton('%CORE_SAVE%');
    }
}
