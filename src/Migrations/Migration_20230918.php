<?php

declare(strict_types=1);

namespace VitesseCms\Block\Migrations;

use stdClass;
use VitesseCms\Block\Enum\BlockEnum;
use VitesseCms\Database\AbstractMigration;

class Migration_20230918 extends AbstractMigration
{
    public function up(): bool
    {
        $result = true;
        if (!$this->parseBlocks()) :
            $result = false;
        endif;

        return $result;
    }

    private function parseBlocks(): bool
    {
        $result = true;
        $blockRepository = $this->eventsManager->fire(
            BlockEnum::GET_REPOSITORY->value,
            source: new stdClass()
        );

        $blocks = $blockRepository->findAll(null, false);
        $search = [
            'Modules\Block\Models\BlockItemlist',
            'Modules\Block\Models\BlockMailchimpInitialize',
            'Modules\Block\Models\BlockBlocks',
            'Modules\Block\Models\BlockTexteditor',
            'Modules\Block\Models\BlockVideo',
            'Modules\Block\Models\BlockLanguageSwitch',
            'Modules\Craftbeershirts\Block\Models\BlockShopSameDesignByGender',
            'Modules\Craftbeershirts\Block\Models\BlockShopResellerMyAccount',
            'Modules\Block\Models\BlockShopCart',
            'Modules\Block\Models\BlockAffiliateOrderOverview',
            'Modules\Block\Models\BlockNewsletterSubscriptions',
            'Modules\Block\Models\BlockShopUserOrders',
            'Modules\Block\Models\BlockMainContent',
            'Modules\Craftbeershirts\Block\Models\BlockSizeTable',
            'Modules\Block\Models\BlockLogo',
            'Modules\Block\Models\BlockUserLogin',
            'Modules\Block\Models\BlockShopDiscountForm',
            'Modules\Block\Models\BlockNewsletterSubscribe',
            'Modules\Craftbeershirts\Block\Models\BlockHopInvadersResult',
            'Modules\Craftbeershirts\Block\Models\BlockHopInvaders',
            'Modules\Block\Models\BlockFormBuilder',
            'Modules\Block\Models\BlockImage',
            'Modules\Block\Models\BlockFilterResult',
            'Modules\Block\Models\BlockFilter',
            'Modules\Block\Models\BlockBreadcrumbs',
            'Modules\Block\Models\BlockAffiliateInitialize',
            'Modules\Block\Models\BlockSocialMediaChannels',
            'Modules\Block\Models\BlockUserChangePassword',
            'Modules\Block\Models\BlockShopCheckoutSummary',
            'Modules\Block\Models\BlockShopCheckoutInformation',
            'Modules\Block\Models\BlockShopPaymentResult'
        ];
        $replace = [
            'VitesseCms\Content\Blocks\Itemlist',
            'VitesseCms\Communication\Blocks\MailchimpInitialize',
            'VitesseCms\Block\Blocks\Blocks',
            'VitesseCms\Content\Blocks\Texteditor',
            'VitesseCms\Media\Blocks\Video',
            'VitesseCms\Language\Blocks\LanguageSwitch',
            'VitesseCms\Craftbeershirts\Block\Models\BlockShopSameDesignByGender',
            'VitesseCms\Craftbeershirts\Block\Models\BlockShopResellerMyAccount',
            'VitesseCms\Shop\Blocks\ShopCart',
            'VitesseCms\Shop\Blocks\AffiliateOrderOverview',
            'VitesseCms\Communication\Blocks\NewsletterSubscriptions',
            'VitesseCms\Shop\Blocks\ShopUserOrders',
            'VitesseCms\Content\Blocks\MainContent',
            'VitesseCms\Craftbeershirts\Block\Models\BlockSizeTable',
            'VitesseCms\Media\Blocks\Logo',
            'VitesseCms\User\Blocks\UserLogin',
            'VitesseCms\Shop\Blocks\ShopDiscountForm',
            'VitesseCms\Communication\Blocks\NewsletterSubscribe',
            'VitesseCms\Craftbeershirts\Block\Models\BlockHopInvadersResult',
            'VitesseCms\Craftbeershirts\Block\Models\BlockHopInvaders',
            'VitesseCms\Form\Blocks\FormBuilder',
            'VitesseCms\Media\Blocks\Image',
            'VitesseCms\Content\Blocks\FilterResult',
            'VitesseCms\Content\Blocks\Filter',
            'VitesseCms\Content\Blocks\Breadcrumbs',
            'VitesseCms\Shop\Blocks\AffiliateInitialize',
            'VitesseCms\Communication\Blocks\SocialMediaChannels',
            'VitesseCms\User\Blocks\UserChangePassword',
            'VitesseCms\Shop\Blocks\ShopCheckoutSummary',
            'VitesseCms\Shop\Blocks\ShopCheckoutInformation',
            'VitesseCms\Shop\Blocks\ShopPaymentResult'
        ];

        while ($blocks->valid()):
            $block = $blocks->current();
            $newBlockType = str_replace($search, $replace, $block->getBlock());
            if (
                substr_count($newBlockType, 'VitesseCms\\Block\\Models\\') === 0
                && !str_starts_with($newBlockType, 'Modules')
            ) :
                $block->setBlock($newBlockType)->save();
            else :
                $this->terminalService->printError(
                    'wrong blockType "' . $newBlockType . '" for block "' . $block->getNameField() . '"'
                );
                $result = false;
            endif;

            $blocks->next();
        endwhile;

        $this->terminalService->printMessage('Block classnames repaired');

        return $result;
    }
}