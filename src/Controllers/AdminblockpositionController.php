<?php

declare(strict_types=1);

namespace VitesseCms\Block\Controllers;

use ArrayIterator;
use stdClass;
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
use VitesseCms\Block\Enum\BlockPositionEnum;
use VitesseCms\Block\Forms\BlockPositionForm;
use VitesseCms\Block\Models\BlockPosition;
use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Core\AbstractControllerAdmin;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Database\Models\FindOrder;
use VitesseCms\Database\Models\FindOrderIterator;
use VitesseCms\Database\Models\FindValueIterator;

class AdminblockpositionController extends AbstractControllerAdmin implements
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

    private readonly BlockPositionRepository $blockPositionRepository;

    public function onConstruct()
    {
        parent::onConstruct();

        $this->blockPositionRepository = $this->eventsManager->fire(BlockPositionEnum::GET_REPOSITORY, new stdClass());
    }

    public function getModel(string $id): ?AbstractCollection
    {
        return match ($id) {
            'new' => new BlockPosition(),
            default => $this->blockPositionRepository->getById($id, false)
        };
    }

    public function getModelForm(): AdminModelFormInterface
    {
        return new BlockPositionForm();
    }

    public function getModelList(?FindValueIterator $findValueIterator): ArrayIterator
    {
        return $this->blockPositionRepository->findAll(
            $findValueIterator,
            false,
            99999,
            new FindOrderIterator([new FindOrder('createdAt', -1)])
        );
    }

    public function setDatagroupAction(string $id): void
    {
        $message = 'ADMIN_BLOCKPOSITION_NOT_FOUND';

        $datagroups = (array)$this->request->get('datagroup');
        foreach ($datagroups as $datagroup) :
            if (substr_count($datagroup, 'page:') > 0) :
                $datagroups = [$datagroup];
                break;
            endif;
        endforeach;

        $blockPosition = $this->blockPositionRepository->getById($id, false);
        if ($blockPosition !== null):
            $blockPosition->setDatagroups($datagroups)->save();
            $message = 'ADMIN_BLOCKPOSITION_UPDATED';
        endif;

        $this->flash->setSucces($message);

        $this->redirect($this->request->getHTTPReferer());
    }
}
