<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Content\Models\Item;
use VitesseCms\Database\Models\FindValue;
use VitesseCms\Database\Models\FindValueIterator;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockFilterSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $datagroups = $repositories->datagroup->findAll(new FindValueIterator([new FindValue('component', 'content')]));
        $datagroupIds = [];
        while ($datagroups->valid()) :
            $datagroup = $datagroups->current();
            $datagroupIds[] = (string)$datagroup->getId();
            $datagroups->next();
        endwhile;
        Item::setFindValue('datagroup', ['$in' => $datagroupIds]);

        $form->addDropdown(
            '%ADMIN_FILTER_RESULT_TARGET_PAGE%',
            'targetPage',
            (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions(Item::findAll()))
        )->addDropdown(
            '%ADMIN_FILTER_SEARCHABLE_GROUPS%',
            'searchGroups',
            (new Attributes())->setOptions(ElementHelper::modelIteratorToOptions($datagroups))->setMultiple(true)
        )->addToggle('Use label placeholders', 'labelAsPlaceholder');
    }
}
