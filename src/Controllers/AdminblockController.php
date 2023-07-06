<?php declare(strict_types=1);

namespace VitesseCms\Block\Controllers;

use VitesseCms\Admin\Interfaces\AdminModelAddableInterface;
use VitesseCms\Admin\Interfaces\AdminModelDeletableInterface;
use VitesseCms\Admin\Interfaces\AdminModelEditableInterface;
use VitesseCms\Admin\Interfaces\AdminModelFormInterface;
use VitesseCms\Admin\Interfaces\AdminModelListInterface;
use VitesseCms\Admin\Interfaces\AdminModelPublishableInterface;
use VitesseCms\Admin\Interfaces\AdminModelSaveInterface;
use VitesseCms\Admin\Traits\TraitAdminModelAddable;
use VitesseCms\Admin\Traits\TraitAdminModelDeletable;
use VitesseCms\Admin\Traits\TraitAdminModelEditable;
use VitesseCms\Admin\Traits\TraitAdminModelList;
use VitesseCms\Admin\Traits\TraitAdminModelPublishable;
use VitesseCms\Admin\Traits\TraitAdminModelSave;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Core\AbstractControllerAdmin;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Database\Models\FindOrder;
use VitesseCms\Database\Models\FindOrderIterator;
use VitesseCms\Database\Models\FindValueIterator;

class AdminblockController extends AbstractControllerAdmin implements
    AdminModelPublishableInterface,
    AdminModelListInterface,
    AdminModelEditableInterface,
    AdminModelSaveInterface,
    AdminModelDeletableInterface,
    AdminModelAddableInterface
{
    use TraitAdminModelPublishable,
        TraitAdminModelList,
        TraitAdminModelEditable,
        TraitAdminModelSave,
        TraitAdminModelDeletable,
        TraitAdminModelAddable
        ;


    private readonly BlockRepository $blockRepository;

    public function OnConstruct()
    {
        parent::OnConstruct();

        $this->blockRepository = $this->eventsManager->fire(BlockEnum::LISTENER_GET_REPOSITORY->value,new \stdClass());
    }

    public function getModel(string $id): ?AbstractCollection
    {
        return match ($id) {
            'new' => new Block(),
            default => $this->blockRepository->getById($id, false)
        };
    }

    public function getModelList( ?FindValueIterator $findValueIterator): \ArrayIterator
    {
        return $this->blockRepository->findAll(
            $findValueIterator,
            false,
            99999,
            new FindOrderIterator([new FindOrder('createdAt', -1)])
        );
    }

    public function getModelForm(): AdminModelFormInterface
    {
        return new BlockForm();
    }

    /*public function onConstruct()
    {
        parent::onConstruct();

        $this->class = Block::class;
        $this->classForm = BlockForm::class;
    }

    public function editAction(
        string $itemId = null,
        string $template = 'editForm',
        string $templatePath = '/form/src/Resources/views/admin/',
        AbstractForm $form = null
    ): void
    {
        if ($itemId === null) :
            parent::editAction($itemId, $template, $templatePath, (new NewBlockForm())->build());
        else :
            parent::editAction($itemId, $template, $templatePath, (new BlockForm())->build(
                $this->repositories->block->getById($itemId, false)
            ));
        endif;
    }*/

    /**
     * verwerken
     */
    /*public function saveAction(
        ?string $itemId = null,
        AbstractCollection $block = null,
        AbstractForm $form = null
    ): void
    {
        if ($itemId === null) :
            parent::saveAction($itemId, null, (new NewBlockForm())->build());
        else :
            $block = $this->repositories->block->getById($itemId, false);
            if ($block !== null) :
                parent::saveAction(
                    $itemId,
                    null,
                    (new BlockForm())->build($block)
                );
            endif;
        endif;
    }*/
}
