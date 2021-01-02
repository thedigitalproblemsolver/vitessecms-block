<?php declare(strict_types=1);

namespace VitesseCms\Block\Controllers;

use VitesseCms\Admin\AbstractAdminController;
use VitesseCms\Block\Forms\BlockPositionForm;
use VitesseCms\Block\Interfaces\RepositoriesInterface;
use VitesseCms\Block\Models\BlockPosition;

class AdminblockpositionController extends AbstractAdminController implements RepositoriesInterface
{
    public function onConstruct()
    {
        parent::onConstruct();

        $this->class = BlockPosition::class;
        $this->classForm = BlockPositionForm::class;
    }

    public function setDatagroupAction(): void
    {
        $message = 'ADMIN_BLOCKPOSITION_NOT_FOUND';

        if ($this->dispatcher->getParam(0) !== null) :
            $datagroups = (array)$this->request->get('datagroup');
            foreach ($datagroups as $datagroup) :
                if (substr_count($datagroup, 'page:') > 0) :
                    $datagroups = [$datagroup];
                    break;
                endif;
            endforeach;

            $blockPosition = $this->repositories->blockPosition->getById(
                $this->dispatcher->getParam(0),
                false
            );
            if ($blockPosition instanceof BlockPosition):
                $blockPosition->set('datagroup', $datagroups)->save();
                $message = 'ADMIN_BLOCKPOSITION_UPDATED';
            endif;
        endif;

        $this->flash->_($message);

        $this->redirect();
    }
}
