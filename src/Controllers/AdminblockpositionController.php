<?php declare(strict_types=1);

namespace VitesseCms\Block\Controllers;

use VitesseCms\Admin\AbstractAdminEventController;
use VitesseCms\Block\Forms\BlockPositionForm;
use VitesseCms\Block\Interfaces\RepositoriesInterface;
use VitesseCms\Block\Models\BlockPosition;
use VitesseCms\Core\Enum\FlashEnum;
use VitesseCms\Core\Services\FlashService;
use \stdClass;

class AdminblockpositionController extends AbstractAdminEventController implements RepositoriesInterface
{
    /**
     * @var FlashService
     */
    private $flash;

    public function onConstruct()
    {
        parent::onConstruct();

        $this->class = BlockPosition::class;
        $this->classForm = BlockPositionForm::class;
        $this->flash = $this->eventsManager->fire(FlashEnum::ATTACH_SERVICE_LISTENER,new stdClass());
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

        $blockPosition = $this->repositories->blockPosition->getById($id, false);
        if ($blockPosition !== null):
            $blockPosition->setDatagroups($datagroups)->save();
            $message = 'ADMIN_BLOCKPOSITION_UPDATED';
        endif;

        $this->flash->setSucces($message);

        $this->redirect();
    }
}
