<?php declare(strict_types=1);

namespace VitesseCms\Block\Migrations;

use VitesseCms\Block\Repositories\AdminRepositoryCollection;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Cli\Services\TerminalServiceInterface;
use VitesseCms\Configuration\Services\ConfigServiceInterface;
use VitesseCms\Install\Interfaces\MigrationInterface;

class Migration_20210514 implements MigrationInterface
{
    /**
     * @var AdminRepositoryCollection
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new AdminRepositoryCollection(
            new BlockRepository()
        );
    }

    public function up(
        ConfigServiceInterface $configService,
        TerminalServiceInterface $terminalService
    ): bool
    {
        $result = true;
        if (!$this->parseBlocks($terminalService)) :
            $result = false;
        endif;

        return $result;
    }

    private function parseBlocks(TerminalServiceInterface $terminalService): bool
    {
        $result = true;
        $blocks = $this->repository->block->findAll(null, false);
        $search = [
            'VitesseCms\Block\Models\BlockBlocks',
            'VitesseCms\Block\Models\BlockAffiliateInitialize',
            'VitesseCms\Block\Models\BlockAffiliateOrderOverview',
            'VitesseCms\Block\Models\BlockBreadcrumbs',
            'VitesseCms\Block\Models\BlockDatagroup',
            'VitesseCms\Block\Models\BlockFilter',
            'VitesseCms\Block\Models\BlockFilterResult',
            'VitesseCms\Block\Models\BlockFormBuilder',
            'VitesseCms\Block\Models\BlockImage',
            'VitesseCms\Block\Models\BlockLogo',
            'VitesseCms\Block\Models\BlockLanguageSwitch',
            'VitesseCms\Block\Models\BlockMailchimpInitialize',
            'VitesseCms\Block\Models\BlockNewsletterSubscribe',
            'VitesseCms\Block\Models\BlockNewsletterSubscriptions',
            'VitesseCms\Block\Models\BlockShopCart',
            'VitesseCms\Block\Models\BlockShopCheckoutInformation',
            'VitesseCms\Block\Models\BlockShopCheckoutSummary',
            'VitesseCms\Block\Models\BlockShopDiscountForm',
            'VitesseCms\Block\Models\BlockShopPaymentResult',
            'VitesseCms\Block\Models\BlockShopUserOrders',
            'VitesseCms\Block\Models\BlockTexteditor',
            'VitesseCms\Block\Models\BlockUserChangePassword',
            'VitesseCms\Block\Models\BlockUserLogin',
            'VitesseCms\Block\Models\BlockVideo',
        ];
        $replace = [
            'VitesseCms\Block\Blocks\Blocks',
            'VitesseCms\Shop\Blocks\AffiliateInitialize',
            'VitesseCms\Shop\Blocks\AffiliateOrderOverview',
            'VitesseCms\Content\Blocks\Breadcrumbs',
            'VitesseCms\Datagroup\Blocks\Datagroup',
            'VitesseCms\Content\Blocks\Filter',
            'VitesseCms\Content\Blocks\FilterResult',
            'VitesseCms\Form\Blocks\FormBuilder',
            'VitesseCms\Media\Blocks\Image',
            'VitesseCms\Media\Blocks\Logo',
            'VitesseCms\Language\Blocks\LanguageSwitch',
            'VitesseCms\Communication\Blocks\MailchimpInitialize',
            'VitesseCms\Communication\Blocks\NewsletterSubscribe',
            'VitesseCms\Communication\Blocks\NewsletterSubscriptions',
            'VitesseCms\Shop\Blocks\ShopCart',
            'VitesseCms\Shop\Blocks\ShopCheckoutInformation',
            'VitesseCms\Shop\Blocks\ShopCheckoutSummary',
            'VitesseCms\Shop\Blocks\ShopDiscountForm',
            'VitesseCms\Shop\Blocks\ShopPaymentResult',
            'VitesseCms\Shop\Blocks\ShopUserOrders',
            'VitesseCms\Content\Blocks\Texteditor',
            'VitesseCms\User\Blocks\UserChangePassword',
            'VitesseCms\User\Blocks\UserLogin',
            'VitesseCms\Media\Blocks\Video',
        ];
        while ($blocks->valid()):
            $block = $blocks->current();
            $newBlockType = str_replace($search, $replace, $block->getBlock());
            if (substr_count($newBlockType, 'VitesseCms\\Block\\Models\\') === 0) :
                $block->setBlock($newBlockType)->save();
            else :
                $terminalService->printError('wrong blockType "' . $newBlockType . '" for block "' . $block->getNameField() . '"');
                $result = false;
            endif;

            $blocks->next();
        endwhile;

        $terminalService->printMessage('Block classnames repaired');

        return $result;
    }
}