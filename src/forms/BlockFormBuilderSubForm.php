<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Database\Models\FindValue;
use VitesseCms\Database\Models\FindValueIterator;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;
use VitesseCms\Media\Enums\AssetsEnum;

class BlockFormBuilderSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $form->addEditor(
            '%ADMIN_INTROTEXT%',
            'introtext',
            (new Attributes())->setMultilang(true)
        )->addEditor(
            '%ADMIN_FORM_THANKYOU_MESSAGE%',
            'pageThankyou',
            (new Attributes())->setRequired(true)->setMultilang(true)
        )->addText(
            '%ADMIN_SYSTEM_MESSAGE%',
            'systemThankyou',
            (new Attributes())->setMultilang(true)
        )->addText(
            '%ADMIN_FORM_INPUT_COLUMN_CSS_CLASS%',
            'inputColumns'
        )->addText(
            '%ADMIN_FORM_LABEL_COLUMN_CSS_CLASS%',
            'labelColumns'
        )->addUrl(
            'Alternative post url',
            'postUrl'
        )->addDropdown(
            '%ADMIN_DATAGROUP%',
            'datagroup',
            (new Attributes())
                ->setRequired(true)
                ->setOptions(ElementHelper::modelIteratorToOptions(
                    $repositories->datagroup->findAll(new FindValueIterator(
                        [new FindValue('component', 'form')]
                    ))
                ))
        )->addDropdown(
            'Add to newsletter',
            'newsletters',
            (new Attributes())
                ->setMultilang(true)
                ->setMultiple(true)
                ->setOptions(ElementHelper::modelIteratorToOptions(
                    $repositories->newsletter->findAll(new FindValueIterator(
                        [new FindValue('parentId', null)]
                    ))))
                ->setInputClass(AssetsEnum::SELECT2)
        )->addText(
            '%ADMIN_FORM_SUBMIT_BUTTON_TEXT%',
            'submitText',
            (new Attributes())->setRequired(true)->setMultilang(true)
        )->addToggle('use reCaptcha', 'useRecaptcha');
    }
}
