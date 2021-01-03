<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Core\Models\DatafieldIterator;
use VitesseCms\Field\Models\FieldDatagroup;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockDatagroupSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $form->addDropdown(
            '%ADMIN_DATAGROUP%',
            'datagroup',
            (new Attributes())
                ->setInputClass('select2')
                ->setOptions(ElementHelper::modelIteratorToOptions($repositories->datagroup->findAll()))
        );

        if (!empty($block->_('datagroup'))):
            $datagroup = $repositories->datagroup->getById($block->_('datagroup'));
            if ($datagroup !== null):
                $datafieldsIterator = new DatafieldIterator();
                foreach ($datagroup->getDatafields() as $datafieldArray) :
                    $datafield = $repositories->datafield->getById($datafieldArray['id']);
                    if ($datafield !== null):
                        if ($datafield->getFieldType() === FieldDatagroup::class) :
                            $datafieldsIterator->add($datafield);
                        endif;
                    endif;
                endforeach;
                if($datafieldsIterator->count()) :
                    $form->addDropdown(
                        '%ADMIN_DATAFIELD%',
                        'datafield',
                        (new Attributes())
                            ->setOptions(ElementHelper::modelIteratorToOptions($datafieldsIterator))
                            ->setRequired(true)
                    );
                endif;
            endif;
        endif;
    }
}
