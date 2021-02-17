<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Database\Models\FindValue;
use VitesseCms\Database\Models\FindValueIterator;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockItemlistHandpickedSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $datagroupIds = [];
        $datagroups = $repositories->datagroup->findAll(new FindValueIterator(
            [new FindValue('component', ['$in' => ['content', 'webshopProduct']])]
        ));

        while ($datagroups->valid()) :
            $datagroup = $datagroups->current();
            $datagroupIds[] = (string)$datagroup->getId();
            $datagroups->next();
        endwhile;

        $form->addDropdown(
            '%ADMIN_ITEMS%',
            'items',
            (new Attributes())
                ->setMultiple(true)
                ->setOptions(ElementHelper::modelIteratorToOptions($repositories->item->findAll(
                    new FindValueIterator(
                        [new FindValue('datagroup', ['$in' => $datagroupIds])]
                    ),
                    true,
                    999
                )))
                ->setInputClass('select2-sortable')
        );
    }
}
