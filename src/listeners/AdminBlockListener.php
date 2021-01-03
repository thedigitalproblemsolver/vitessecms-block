<?php declare(strict_types=1);

namespace VitesseCms\Block\Listeners;

use VitesseCms\Block\Models\Block;
use VitesseCms\Block\Models\BlockLogo;
use VitesseCms\Setting\Enum\CallingNameEnum;
use VitesseCms\Setting\Enum\TypeEnum;
use VitesseCms\Setting\Factory\SettingFactory;
use Phalcon\Events\Event;

class AdminBlockListener
{
    public function beforeModelSave(Event $event, Block $block): void
    {
        switch ($block->getBlock()) :
            case BlockLogo::class:
                $this->parseLogo($block);
                break;
        endswitch;
    }

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
}
