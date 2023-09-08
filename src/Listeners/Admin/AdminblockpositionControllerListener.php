<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners\Admin;

use Phalcon\Events\Event;
use VitesseCms\Admin\AbstractAdminController;
use VitesseCms\Admin\Forms\AdminlistFormInterface;
use VitesseCms\Block\Controllers\AdminblockpositionController;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class AdminblockpositionControllerListener
{
    public function adminListFilter(
        Event $event,
        AdminblockpositionController $controller,
        AdminlistFormInterface $form
    ): void
    {
        $form->addNameField($form);
        $form->addPublishedField($form);
        $form->addDropdown(
            '%ADMIN_POSITION%',
            'filter[position]',
            (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions(
                $form->configuration->getTemplatePositions()
            ))
        );
    }
}
