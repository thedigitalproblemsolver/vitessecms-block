<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Models\Datagroup;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockPositionForm extends AbstractForm
{
    public function initialize(AbstractCollection $item): void
    {
        $this->addText(
            '%CORE_NAME%',
            'name',
            (new Attributes())->setRequired(true)->setMultilang(true))
            ->addText('%ADMIN_CSS_CLASS%', 'class')
            ->addDropdown(
                '%ADMIN_BLOCK%',
                'block',
                (new Attributes())
                    ->setRequired(true)
                    ->setOptions(ElementHelper::arrayToSelectOptions(
                        Block::findAll(),
                        [$item->_('block')]
                    )
                    )
            )->addDropdown(
                '%ADMIN_POSITION%',
                'position',
                (new Attributes())
                    ->setRequired(true)
                    ->setOptions(ElementHelper::arrayToSelectOptions(
                        $this->configuration->getTemplatePositions()
                    )
                    )
            );

        if (
            !is_array($item->_('datagroup'))
            && substr_count($item->_('datagroup'), 'page:') === 0
        ) :
            Datagroup::setFindPublished(false);
            $dataGroups = Datagroup::findAll();
            $dataGroupOptions = ['all' => 'All'];
            foreach ($dataGroups as $dataGroup) :
                $dataGroupOptions[(string)$dataGroup->getId()] = $dataGroup->_('name');
            endforeach;

            $this->addDropdown(
                '%ADMIN_DATAGROUP%',
                'datagroup',
                (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions(
                    $dataGroupOptions,
                    [$item->_('datagroup')]
                ))
            );
        endif;

        $this->addAcl('%ADMIN_PERMISSION_ROLES%', 'roles')
            ->addNumber('%ADMIN_ORDERING%', 'ordering')
            ->addSubmitButton('%CORE_SAVE%');
    }
}
