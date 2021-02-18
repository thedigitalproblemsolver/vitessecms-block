<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Models\BlockPosition;
use VitesseCms\Datagroup\Models\Datagroup;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Database\Interfaces\BaseRepositoriesInterface;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\AbstractFormWithRepository;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Interfaces\FormWithRepositoryInterface;
use VitesseCms\Form\Models\Attributes;

class BlockPositionForm extends AbstractFormWithRepository
{
    /**
     * @var BlockPosition
     */
    protected $item;

    /**
     * @var RepositoryInterface
     */
    protected $repositories;

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

        if (
            !is_array($this->item->_('datagroup'))
            && substr_count($this->item->_('datagroup'), 'page:') === 0
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
                (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions($dataGroupOptions))
            );
        endif;

        $this->addAcl('%ADMIN_PERMISSION_ROLES%', 'roles')
            ->addNumber('%ADMIN_ORDERING%', 'ordering')
            ->addSubmitButton('%CORE_SAVE%');

        return $this;
    }

    public function setEntity($entity)
    {
        parent::setEntity($entity);

        $this->item = $entity;
    }
}
