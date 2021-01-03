<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Content\Models\Item;
use VitesseCms\Core\Models\Datafield;
use VitesseCms\Core\Models\Datagroup;
use VitesseCms\Form\Forms\BaseForm;
use VitesseCms\Media\Enums\AssetsEnum;
use MongoDB\BSON\ObjectID;

class BlockFilter extends AbstractBlockModel
{
    public function parse(Block $block): void
    {
        parent::parse($block);

        $ids = [];
        $filter = new BaseForm();
        $filter->setLabelAsPlaceholder((bool)$block->_('labelAsPlaceholder'));

        $templateParts = explode('/',$block->_('template'));
        $templateParts = array_reverse($templateParts);
        $templatePath = $this->di->config->get('defaultTemplateDir').
            'views/blocks/Filter/'.
            ucfirst($templateParts[0])
        ;

        $filter->_('html',null,null,[
            'html' => $this->view->renderTemplate('_filter_form',$templatePath)
        ]);

        $filter->_('htmlraw',null,null,[
            'html' => $this->view->renderTemplate('_filter_container_start',$templatePath)
        ]);

        if(substr_count(strtolower($templateParts[0]),'horizontal')) :
            $filter->setFormTemplate('form_horizontal');
        endif;

        foreach((array)$block->_('searchGroups') as $searchGroupId) :
            $datagroup = Datagroup::findById($searchGroupId);
            foreach ( (array)$datagroup->_('datafields') as $field) :
                if (!empty($field['filterable'])) :
                    $datafield = Datafield::findById($field['id']);
                    /** @var Datafield $datafield */
                    if (\is_object($datafield) && $datafield->_('published')) :
                        $datafield->renderFilter($filter);
                    endif;
                endif;
            endforeach;
        endforeach;

        $filter->_('htmlraw', null,null, [
            'html' => $this->view->renderTemplate('_filter_container_end',$templatePath)
        ]);
        $filter->_('hidden',null,'searchGroups', ['value' => implode(',',$ids)]);
        $filter->_('hidden',null,'firstRun', ['value' => true]);

        BlockPosition::setFindValue('datagroup', ['$in' => ['page:'.$block->_('targetPage')]]);
        $resultBlockPosition = BlockPosition::findFirst();

        Block::setFindValue('_id', new ObjectID($resultBlockPosition->_('block')));
        $resultBlock = Block::findFirst();
        $filter->_('hidden', null, 'blockId', ['value' => (string)$resultBlock->getId()]);

        $item = Item::findById($block->_('targetPage'));
        $block->set('filter',$filter->renderForm(
            $item->_('slug'),
            'filter'
        ));
    }

    public function loadAssets(Block $block): void
    {
        parent::loadAssets($block);

        $this->di->assets->load(AssetsEnum::FILTER);
        $this->di->assets->load(AssetsEnum::SELECT2);
    }
}
