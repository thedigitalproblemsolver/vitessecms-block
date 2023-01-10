<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use Phalcon\Forms\Form;
use VitesseCms\Block\Models\BlockPosition;
use VitesseCms\Block\Repositories\AdminRepositoryCollection;
use VitesseCms\Datagroup\Models\DatagroupIterator;
use VitesseCms\Form\AbstractFormWithRepository;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Interfaces\FormWithRepositoryInterface;
use VitesseCms\Form\Models\Attributes;

class BlockPositionForm extends AbstractFormWithRepository
{
    public function buildForm(): FormWithRepositoryInterface
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
                    ->setOptions(ElementHelper::modelIteratorToOptions($this->repositories->block->findAll())
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

        if(
            is_array($this->entity->getDatagroup())
            && count($this->entity->getDatagroup()) === 1
            && $this->entity->getDatagroup()[0] !== 'all'
            && $this->entity->getDatagroup()[0] !== ''
        ) :
            $this->addDropdown(
                '%BLOCK_LAYOUT%',
                'layout',
                (new Attributes())->setOptions(
                    ElementHelper::modelIteratorToOptions(
                        $this->repositories->layout->findByDatagroup($this->entity->getDatagroup()[0],null, false)
                    )
                )
            );

            $datagroup = $this->repositories->datagroup->getById($this->entity->getDatagroup()[0]);
            $this->addDropdown(
                '%ADMIN_DATAGROUP%',
                'datagroup',
                (new Attributes())->setOptions(ElementHelper::modelIteratorToOptions(
                    new DatagroupIterator($datagroup?[$datagroup]:[])
                ))
            );
        elseif (
            (
                !is_array($this->entity->getDatagroup())
                && substr_count($this->entity->getDatagroup(), 'page:') === 0
            ) ||
            is_array($this->entity->getDatagroup())

        ) :
            $datagroups = $this->repositories->datagroup->findAll(null, false);
            $dataGroupOptions = ['all' => 'All'];
            while ($datagroups->valid()) :
                $datagroup = $datagroups->current();
                $dataGroupOptions[(string)$datagroup->getId()] = $datagroup->getNameField();
                $datagroup = $datagroups->next();
            endwhile;

            $this->addDropdown(
                '%ADMIN_DATAGROUP%',
                'datagroup',
                (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions($dataGroupOptions))->setMultiple()
            );
        endif;

        $this->addAcl('%ADMIN_PERMISSION_ROLES%', 'roles')
            ->addNumber('%ADMIN_ORDERING%', 'ordering')
            ->addSubmitButton('%CORE_SAVE%');

        return $this;
    }
}
