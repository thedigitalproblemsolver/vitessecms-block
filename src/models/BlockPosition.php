<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\Helpers\BlockHelper;
use VitesseCms\Block\Repositories\BlockRepository;
use VitesseCms\Core\Helpers\HtmlHelper;
use VitesseCms\Core\Helpers\ItemHelper;
use VitesseCms\Core\Models\Datagroup;
use VitesseCms\Core\Services\CacheService;
use VitesseCms\Core\Services\ViewService;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\User\Models\User;
use VitesseCms\User\Utils\PermissionUtils;

class BlockPosition extends AbstractCollection
{
    /**
     * @var string
     */
    public $block;

    /**
     * @var string
     */
    public $class;

    public function render(
        ViewService $view,
        User $user,
        BlockRepository $blockRepository,
        CacheService $cacheService
    ): string {
        $return = '';

        $block = $blockRepository->getById($this->block);
        if ($block !== null) :
            $return = BlockHelper::render($block, $view, $cacheService);
            if (
                !empty($return)
                && (
                    PermissionUtils::check($user, 'block', 'adminblock', 'edit')
                    || PermissionUtils::check($user, 'block', 'adminblockposition', 'edit')
                )
            ) :
                $return = $this->getEditElements($view, $user, (string)$block->getId()).$return;
            endif;

            if (!empty($this->class) || !empty($block->getClass())) :
                $return = '<div '.HtmlHelper::makeAttribute([$block->getClass(), $this->class], 'class').'>'.
                    $return.
                    '</div>';
            endif;
        endif;

        return $return;
    }

    public function getEditElements(ViewService $view, User $user, string $blockId): string
    {
        $publishedIcon = ItemHelper::getPublishIcon($this->isPublished(), true);

        $return = '<div class="blockposition-toolbar">
                Block : '.$this->getNameField().'
                <div class="btn-group" role="group">';
        if (PermissionUtils::check($user, 'block', 'adminblock', 'edit')) :
            $return .= '<a
                            class="fa fa-edit btn btn-info openmodal"
                            href="admin/block/adminblock/edit/'.$blockId.'"
                        ></a>';
        endif;
        if (PermissionUtils::check($user, 'block', 'adminblockposition', 'edit')) :
            $return .= '<a
                            id="publish-toggle-'.$this->getId().'"
                            class="'.$publishedIcon.'"
                            href="'.$this->di->url->getBaseUri().'admin/block/adminblockposition/togglepublish/'.$this->getId().'"
                        ></a>
                        <a
                            class="fa fa-trash btn btn-danger"
                            href="'.$this->di->url->getBaseUri().'admin/block/adminblockposition/delete/'.$this->getId().'"
                        ></a>';
        endif;
        $return .= '</div>';
        if (PermissionUtils::check($user, 'block', 'adminblockposition', 'edit')) :
            Datagroup::setFindPublished(false);
            $datagroups = Datagroup::findAll();

            $selected = '';
            if (\in_array('all', (array)$this->_('datagroup'))) :
                $selected = ' selected="selected" ';
            endif;

            $return .= '<form
                        action="admin/block/adminblockposition/setdatagroup/'.$this->getId().'"
                        method="post"
                        class="adminform-datagroup"
                    >
                    <select name="datagroup[]" multiple="multiple" >
                        <option value="all" '.$selected.' >All</option>';
            if ($view->getVar('currentId')) :
                $selectedPage = '';
                if (\in_array('page:'.$view->getVar('currentId'), (array)$this->_('datagroup'))) :
                    $selectedPage = ' selected="selected" ';
                endif;
                $return .= '<option value="page:'.$view->getVar('currentId').'" '.$selectedPage.' >Only on current page</option>';
            endif;
            /** @var Datagroup $datagroup */
            foreach ($datagroups as $datagroup) :
                $selected = '';
                if (\in_array($datagroup->getId(), (array)$this->_('datagroup'))) :
                    $selected = ' selected="selected" ';
                endif;
                $return .= '<option 
                                value="'.$datagroup->getId().'"
                                '.$selected.'
                            >'.
                    $datagroup->getNameField().'
                            </option>';
            endforeach;
            $return .= '</select><br />
                    <button type="submit" class="btn btn-success fa fa-floppy-o"></button>
                    </form>';
        endif;
        $return .= '</div>';

        return $return;
    }

    public function beforeSave(): void
    {
        $this->set('ordering', (int)$this->_('ordering'));
    }
}
