<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Form\Models\Attributes;
use VitesseCms\Setting\Enum\CallingNameEnum;

class BlockLogoSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        if (!$form->setting->has(CallingNameEnum::LOGO_DEFAULT)) :
            $form->addFilemanager('Logo standaard', 'logo_default', (new Attributes())->setRequired(true));
        endif;
        if (!$form->setting->has(CallingNameEnum::LOGO_MOBILE)) :
            $form->addFilemanager('Logo mobile', 'logo_mobile', (new Attributes())->setRequired(true));
        endif;
        if (!$form->setting->has(CallingNameEnum::LOGO_EMAIL)) :
            $form->addFilemanager('Logo email', 'logo_email', (new Attributes())->setRequired(true));
        endif;
        if (!$form->setting->has(CallingNameEnum::FAVICON)) :
            $form->addFilemanager('Favicon', 'favicon', (new Attributes())->setRequired(true));
        endif;

        $form->addToggle('Display motto', 'displayMotto');
    }
}
