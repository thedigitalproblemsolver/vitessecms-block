<?php declare(strict_types=1);

namespace VitesseCms\Block\Services;

use VitesseCms\Block\Repositories\BlockPositionRepository;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Configuration\Services\ConfigService;
use VitesseCms\Core\Helpers\HtmlHelper;
use VitesseCms\Core\Services\CacheService;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Mustache\DTO\RenderLayoutDTO;
use VitesseCms\Mustache\Enum\ViewEnum;
use VitesseCms\User\Models\User;
use VitesseCms\User\Utils\PermissionUtils;

class BlockService
{
    /**
     * @var ViewService
     */
    protected $view;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var BlockPositionRepository
     */
    protected $blockPositionRepository;

    /**
     * @var BlockRepository
     */
    protected $blockRepository;

    /**
     * @var CacheService
     */
    protected $cache;

    /**
     * @var ConfigService
     */
    protected $configuration;

    public function __construct(
        ViewService $viewService,
        User $user,
        BlockPositionRepository $blockPositionRepository,
        BlockRepository $blockRepository,
        CacheService $cacheService,
        ConfigService $configService
    )
    {
        $this->view = $viewService;
        $this->user = $user;
        $this->blockPositionRepository = $blockPositionRepository;
        $this->blockRepository = $blockRepository;
        $this->cache = $cacheService;
        $this->configuration = $configService;
    }

    public function parseTemplatePosition(string $templatePosition, $templateLayoutClass): string
    {
        $content = '';
        $layout ='';

        $dataGroups = ['all'];
        if ($this->view->hasCurrentItem()) :
            $dataGroups[] = 'page:' . $this->view->getCurrentItem()->getId();
            $dataGroups[] = $this->view->getCurrentItem()->getDatagroup();
        endif;

        $blockPositions = $this->blockPositionRepository->getByPositionNameAndDatagroup(
            $templatePosition,
            $dataGroups
        );
        while ($blockPositions->valid()) :
            $blockPosition = $blockPositions->current();
            if($blockPosition->hasLayout()):
                $layout = $blockPosition->getDi()->get('eventsManager')->fire(
                    ViewEnum::RENDER_LAYOUT_EVENT,
                    new RenderLayoutDTO($blockPosition->getLayout())
                );
            endif;

            if(empty($layout)) :
                $content .= $blockPosition->render(
                    $this->view,
                    $this->user,
                    $this->blockRepository,
                    $this->cache
                );
            else :
                $content .= $layout;
            endif;

            $layout = '';
            $blockPositions->next();
        endwhile;

        $classes = ['container-' . $templatePosition];
        if (!empty($templateLayoutClass)):
            if (is_string($templateLayoutClass)):
                $classes[] = $templateLayoutClass;
            elseif (is_array($templateLayoutClass)) :
                $templateLayoutClass = reset($templateLayoutClass);
                if (!empty($templateLayoutClass)) :
                    $classes[] = $templateLayoutClass;
                endif;
            endif;
        endif;

        if (empty($content)) :
            return '';
        else :
            $contentExtra = '';
            if (PermissionUtils::check(
                $this->user,
                'block',
                'adminblockposition',
                'edit')
            ) :
                $contentExtra = $this->view->renderTemplate(
                    'block_position_toolbar',
                    $this->configuration->getVendorNameDir() . 'block/src/Resources/views/admin/',
                    ['templatePosition' => $templatePosition]
                );
            endif;

            return '<div ' . HtmlHelper::makeAttribute($classes, 'class') . ' >'
                . $contentExtra . $content .
                '</div>';
        endif;
    }
}
