<?php declare(strict_types=1);

namespace VitesseCms\Block\Controllers;

use VitesseCms\Admin\AbstractAdminController;
use VitesseCms\Block\Forms\BlockForm;
use VitesseCms\Block\Forms\NewBlockForm;
use VitesseCms\Block\Interfaces\RepositoriesInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Form\AbstractForm;

class AdminblockController extends AbstractAdminController implements RepositoriesInterface
{
    public function onConstruct()
    {
        parent::onConstruct();

        $this->class = Block::class;
        $this->classForm = BlockForm::class;
    }

    public function editAction(
        string $itemId = null,
        string $template = 'editForm',
        string $templatePath = 'src/core/resources/views/admin/',
        AbstractForm $form = null
    ): void {
        if ($itemId === null) :
            parent::editAction($itemId, $template, $templatePath, (new NewBlockForm())->build());
        else :
            parent::editAction($itemId, $template, $templatePath, (new BlockForm())->build(
                $this->repositories->block->getById($itemId, false)
            ));
        endif;
    }

    public function saveAction(
        ?string $itemId = null,
        AbstractCollection $block = null,
        AbstractForm $form = null
    ): void {
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
    }
}
