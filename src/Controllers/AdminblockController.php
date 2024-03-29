<?php

declare(strict_types=1);

namespace VitesseCms\Block\Controllers;

use ArrayIterator;
use VitesseCms\Admin\Interfaces\AdminModelAddableInterface;
use VitesseCms\Admin\Interfaces\AdminModelCopyableInterface;
use VitesseCms\Admin\Interfaces\AdminModelDeletableInterface;
use VitesseCms\Admin\Interfaces\AdminModelEditableInterface;
use VitesseCms\Admin\Interfaces\AdminModelFormInterface;
use VitesseCms\Admin\Interfaces\AdminModelListInterface;
use VitesseCms\Admin\Interfaces\AdminModelPublishableInterface;
use VitesseCms\Admin\Traits\TraitAdminModelAddable;
use VitesseCms\Admin\Traits\TraitAdminModelCopyable;
use VitesseCms\Admin\Traits\TraitAdminModelDeletable;
use VitesseCms\Admin\Traits\TraitAdminModelEditable;
use VitesseCms\Admin\Traits\TraitAdminModelList;
use VitesseCms\Admin\Traits\TraitAdminModelPublishable;
use VitesseCms\Admin\Traits\TraitAdminModelSave;
use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Core\AbstractControllerAdmin;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Database\DTO\GetRepositoryDTO;
use VitesseCms\Database\Enums\RepositoryEnum;
use VitesseCms\Database\Models\FindValueIterator;

class AdminblockController extends AbstractControllerAdmin implements
    AdminModelPublishableInterface,
    AdminModelListInterface,
    AdminModelEditableInterface,
    AdminModelDeletableInterface,
    AdminModelAddableInterface,
    AdminModelCopyableInterface
{
    use TraitAdminModelAddable;
    use TraitAdminModelCopyable;
    use TraitAdminModelDeletable;
    use TraitAdminModelEditable;
    use TraitAdminModelList;
    use TraitAdminModelPublishable;
    use TraitAdminModelSave;

    private readonly BlockRepository $blockRepository;

    public function OnConstruct()
    {
        parent::OnConstruct();

        $this->blockRepository = $this->eventsManager->fire(
            RepositoryEnum::GET_REPOSITORY->value,
            new GetRepositoryDTO(Block::class)
        );
    }

    public function getModel(string $id): ?AbstractCollection
    {
        return match ($id) {
            'new' => new Block(),
            default => $this->blockRepository->getById($id, false)
        };
    }

    public function getModelList(?FindValueIterator $findValueIterator): ArrayIterator
    {
        return $this->blockRepository->findAll(
            $findValueIterator,
            false
        );
    }

    public function getModelForm(): AdminModelFormInterface
    {
        return new BlockForm();
    }
}
