<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners\Admin;

use Phalcon\Events\Event;
use VitesseCms\Admin\Forms\AdminlistFormInterface;
use VitesseCms\Block\Controllers\AdminblockController;
use VitesseCms\Block\Utils\BlockUtil;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class AdminblockControllerListener
{
    public function adminListFilter(Event $event, AdminblockController $controller, AdminlistFormInterface $form): string
    {
        $form->addNameField($form);
        $types = BlockUtil::getTypes(SystemUtil::getModules($controller->configuration));
        $types = array_combine($types, $types);
        $form->addDropdown(
            '%ADMIN_BLOCK%',
            'filter[block]',
            (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions($types))
        );
        $form->addPublishedField($form);

        return $form->renderForm(
            $controller->getLink() . '/' . $controller->router->getActionName(),
            'adminFilter'
        );
    }
}
