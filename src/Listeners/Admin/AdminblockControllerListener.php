<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners\Admin;

use Phalcon\Events\Event;
use VitesseCms\Admin\AbstractAdminController;
use VitesseCms\Block\Controllers\AdminblockController;
use VitesseCms\Block\Models\Block;
use VitesseCms\Admin\Forms\AdminlistFormInterface;
use VitesseCms\Block\Utils\BlockUtil;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;
use VitesseCms\Media\Blocks\Logo;
use VitesseCms\Setting\Enum\CallingNameEnum;
use VitesseCms\Setting\Enum\TypeEnum;
use VitesseCms\Setting\Factory\SettingFactory;

class AdminblockControllerListener
{
    public function beforeModelSave(Event $event, AdminblockController $controller, Block $block): void
    {
        switch ($block->getBlock()) :
            case Logo::class:
                $this->parseLogo($block);
                break;
        endswitch;
    }

    //TODO move to block listener
    protected function parseLogo(Block $block)
    {
        if ($block->logo_default !== null) :
            SettingFactory::create(
                CallingNameEnum::LOGO_DEFAULT,
                TypeEnum::IMAGE,
                $block->logo_default,
                'Logo core',
                true
            )->save();
        endif;

        if ($block->logo_mobile !== null) :
            SettingFactory::create(
                CallingNameEnum::LOGO_MOBILE,
                TypeEnum::IMAGE,
                $block->logo_mobile,
                'Logo mobile',
                true
            )->save();
        endif;

        if ($block->logo_email !== null) :
            SettingFactory::create(
                CallingNameEnum::LOGO_EMAIL,
                TypeEnum::IMAGE,
                $block->logo_email,
                'Logo e-mail',
                true
            )->save();
        endif;

        if ($block->favicon !== null) :
            SettingFactory::create(
                CallingNameEnum::FAVICON,
                TypeEnum::IMAGE,
                $block->favicon,
                'Logo favicon',
                true
            )->save();
        endif;

        unset($block->logo_default, $block->logo_mobile, $block->logo_email, $block->favicon);
    }

    public function adminListFilter(Event $event, AbstractAdminController $controller, AdminlistFormInterface $form): string
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
