<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Utils\BlockUtil;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class NewBlockForm extends AbstractForm
{
    public function build(): NewBlockForm
    {
        $this->addText(
            '%CORE_NAME%',
            'name',
            (new Attributes())->setRequired(true)->setMultilang(true)
        )
            ->addText('%ADMIN_CSS_CLASS%', 'class')
            ->addDropdown(
                '%ADMIN_BLOCK%',
                'block',
                (new Attributes())->setRequired(true)
                    ->setOptions(
                        ElementHelper::arrayToSelectOptions(
                            BlockUtil::getTypes(SystemUtil::getModules($this->configuration))
                        )
                    )
            )->addSubmitButton('%CORE_SAVE%');

        return $this;
    }
}
