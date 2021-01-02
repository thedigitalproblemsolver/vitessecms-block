<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Helpers\BlockHelper;
use VitesseCms\Block\Interfaces\RepositoriesInterface;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class NewBlockForm extends AbstractForm implements RepositoriesInterface
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
                        BlockHelper::getTypes(
                            $this->configuration->getRootDir(),
                            $this->configuration->getAccountDir()
                        )
                    )
                )
        )->addSubmitButton('%CORE_SAVE%');

        return $this;
    }
}
