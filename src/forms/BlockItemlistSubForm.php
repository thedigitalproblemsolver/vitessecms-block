<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Enum\ItemListEnum;
use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Content\Models\Item;
use VitesseCms\Core\Helpers\ItemHelper;
use VitesseCms\Core\Models\Datafield;
use VitesseCms\Core\Models\Datagroup;
use VitesseCms\Field\Models\FieldDatagroup;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockItemlistSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $form->addDropdown(
            '%ADMIN_SOURCE%',
            'listMode',
            (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions(ItemListEnum::LISTMODES))
        )->addDropdown(
            '%ADMIN_ITEM_ORDER_DISPLAY%',
            'displayOrdering',
            (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions([
                'ordering'  => '%ADMIN_ITEM_ORDER_ORDERING%',
                'name'      => '%ADMIN_ITEM_ORDER_NAME%',
                'createdAt' => '%ADMIN_ITEM_ORDER_CREATED%',
            ]))
        )->addDropdown(
            'Volgorde sortering ',
            'displayOrderingDirection',
            (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions([
                'oldest' => 'oldest first',
                'newest' => 'newest first',
            ]))
        )->addNumber('%ADMIN_ITEM_ORDER_DISPLAY_NUMBER%', 'numbersToDisplay')
            ->addText(
                '%ADMIN_READMORE_TEXT%',
                'readmoreText',
                (new Attributes())->setMultilang(true)
            );

        $options = [
            [
                'value'    => '',
                'label'    => '%ADMIN_TYPE_TO_SEARCH%',
                'selected' => false,
            ],
        ];
        if ($block->_('readmoreItem')) :
            $selectedItem = $repositories->item->getById($block->_('readmoreItem'));
            if ($selectedItem !== null):
                $itemPath = ItemHelper::getPathFromRoot($selectedItem);
                $options[] = [
                    'value'    => (string)$selectedItem->getId(),
                    'label'    => implode(' - ', $itemPath),
                    'selected' => true,
                ];
            endif;
        endif;
        $form->addDropdown(
            '%ADMIN_READMORE_PAGE%',
            'readmoreItem',
            (new Attributes())
                ->setOptions($options)
                ->setInputClass('select2-ajax')
                ->setDataUrl('/content/index/search/')
        )->addToggle('%ADMIN_READMORE_SHOW_PER_ITEM%', 'readmoreShowPerItem');
    }

    public static function buildDatafieldValueForm(BlockForm $form, string $datagroupId): void
    {
        $datagroup = Datagroup::findById($datagroupId);
        if ($datagroup) :
            $form->_(
                'html',
                'html',
                'html',
                [
                    'html' => '<h2>'.$datagroup->_('name').'</h2>',
                ]
            );
            foreach ($datagroup->_('datafields') as $datafieldOptions) :
                /** @var Datafield $datafield */
                $datafield = Datafield::findById($datafieldOptions['id']);
                if ($datafield) :
                    $fieldName = 'datafieldValue['.$datafield->_('calling_name').']';
                    $name = $datafield->_('name');
                    switch ($datafield->getFieldType()):
                        case 'FieldCheckbox':
                            $form->_(
                                'select',
                                $name,
                                $fieldName,
                                [
                                    'options' => ElementHelper::arrayToSelectOptions([
                                        'both'        => 'selected or not selected',
                                        'selected'    => 'selected',
                                        'notSelected' => 'not selected',
                                    ]),
                                ]
                            );
                            break;
                        case 'FieldPrice':
                            $form->_(
                                'select',
                                $name.' discount',
                                'datafieldValue[discount]',
                                [
                                    'options' => ElementHelper::arrayToSelectOptions([
                                        'bothEmpty' => 'empty or not empty',
                                        'empty'     => 'empty',
                                        'notEmpty'  => 'not empty',
                                    ]),
                                ]
                            );
                            break;
                        case 'FieldDatagroup':
                        case FieldDatagroup::class:
                            $options = [];
                            if ($datafield->getDatagroup() !== null) {
                                Item::setFindValue('datagroup', $datafield->getDatagroup());
                                $options = ElementHelper::arrayToSelectOptions(Item::findAll());
                                $options[] = [
                                    'value'    => ItemListEnum::OPTION_CURRENT_ITEM,
                                    'label'    => '%FORM_OPTION_ACTIVE_ITEM%',
                                    'selected' => null,
                                ];
                            }
                            $form->addDropdown(
                                $name,
                                $fieldName,
                                (new Attributes())->setOptions($options)
                            );
                            break;
                        default:
                            $form->_(
                                'text',
                                $name,
                                $fieldName
                            );
                            break;
                    endswitch;
                endif;
            endforeach;
        endif;
    }
}
