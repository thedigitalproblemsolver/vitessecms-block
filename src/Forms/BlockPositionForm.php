<?php

declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use stdClass;
use VitesseCms\Admin\Interfaces\AdminModelFormInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Database\DTO\GetRepositoryDTO;
use VitesseCms\Database\Enums\RepositoryEnum;
use VitesseCms\Datagroup\Enums\DatagroupEnum;
use VitesseCms\Datagroup\Models\DatagroupIterator;
use VitesseCms\Datagroup\Repositories\DatagroupRepository;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;
use VitesseCms\Mustache\Enum\LayoutEnum;
use VitesseCms\Mustache\Repositories\LayoutRepository;

class BlockPositionForm extends AbstractForm implements AdminModelFormInterface
{
    private readonly BlockRepository $blockRepository;
    private readonly LayoutRepository $layoutRepository;
    private readonly DatagroupRepository $datagroupRepository;


    public function __construct($entity = null, array $userOptions = [])
    {
        parent::__construct($entity, $userOptions);

        $this->blockRepository = $this->eventsManager->fire(
            RepositoryEnum::GET_REPOSITORY->value,
            new GetRepositoryDTO(Block::class)
        );
        $this->layoutRepository = $this->eventsManager->fire(LayoutEnum::GET_REPOSITORY->value, new stdClass());
        $this->datagroupRepository = $this->eventsManager->fire(DatagroupEnum::GET_REPOSITORY->value, new stdClass());
    }

    public function buildForm(): void
    {
        $this->addText('%CORE_NAME%', 'name', (new Attributes())->setRequired()->setMultilang())
            ->addText('%ADMIN_CSS_CLASS%', 'class')
            ->addDropdown(
                '%ADMIN_BLOCK%',
                'block',
                (new Attributes())
                    ->setRequired()
                    ->setOptions(
                        ElementHelper::modelIteratorToOptions($this->blockRepository->findAll())
                    )
            )->addDropdown(
                '%ADMIN_POSITION%',
                'position',
                (new Attributes())
                    ->setRequired()
                    ->setOptions(
                        ElementHelper::arrayToSelectOptions(
                            $this->configuration->getTemplatePositions()
                        )
                    )
            );

        if (
            $this->entity !== null
            && is_array($this->entity->getDatagroup())
            && count($this->entity->getDatagroup()) === 1
            && $this->entity->getDatagroup()[0] !== 'all'
            && $this->entity->getDatagroup()[0] !== ''
        ) :
            $this->addDropdown(
                '%BLOCK_LAYOUT%',
                'layout',
                (new Attributes())->setOptions(
                    ElementHelper::modelIteratorToOptions(
                        $this->layoutRepository->findByDatagroup($this->entity->getDatagroup()[0], null, false)
                    )
                )
            );

            $datagroup = $this->datagroupRepository->getById($this->entity->getDatagroup()[0]);
            $this->addDropdown(
                '%ADMIN_DATAGROUP%',
                'datagroup',
                (new Attributes())->setOptions(
                    ElementHelper::modelIteratorToOptions(
                        new DatagroupIterator($datagroup ? [$datagroup] : [])
                    )
                )->setRequired()
            );
        elseif (
            (
                $this->entity !== null
                && !is_array($this->entity->getDatagroup())
                && substr_count($this->entity->getDatagroup(), 'page:') === 0
            ) ||
            (
                $this->entity !== null
                && is_array($this->entity->getDatagroup())
            )

        ) :
            $datagroups = $this->datagroupRepository->findAll(null, false);
            $dataGroupOptions = ['all' => 'All'];
            while ($datagroups->valid()) :
                $datagroup = $datagroups->current();
                $dataGroupOptions[(string)$datagroup->getId()] = $datagroup->getNameField();
                $datagroups->next();
            endwhile;

            $this->addDropdown(
                '%ADMIN_DATAGROUP%',
                'datagroup',
                (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions($dataGroupOptions))->setMultiple(
                )->setRequired()
            );
        endif;

        $this->addAcl('%ADMIN_PERMISSION_ROLES%', 'roles')
            ->addNumber('%ADMIN_ORDERING%', 'ordering', (new Attributes())->setRequired())
            ->addSubmitButton('%CORE_SAVE%');
    }
}
