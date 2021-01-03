<?php declare(strict_types=1);

namespace VitesseCms\Block\Forms;

use VitesseCms\Block\Interfaces\BlockSubFormInterface;
use VitesseCms\Block\Interfaces\RepositoryInterface;
use VitesseCms\Block\Models\Block;
use VitesseCms\Database\Models\FindValue;
use VitesseCms\Database\Models\FindValueIterator;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class BlockNewsletterSubscribeSubForm implements BlockSubFormInterface
{
    public static function getBlockForm(BlockForm $form, Block $block, RepositoryInterface $repositories): void
    {
        $newsletters = $repositories->newsletter->findAll(new FindValueIterator(
            [new FindValue('parentId', null)]
        ));
        $attributes = new Attributes();
        $attributes->setMultilang(true)->setOptions(ElementHelper::modelIteratorToOptions($newsletters));
        $attributes->setMultiple(true);

        $form->addDropdown(
            'Newsletters to subscribe',
            'subscribe',
            $attributes
        )->addDropdown(
            'Newsletters to unsubscribe',
            'unsubscribe',
            $attributes
        )->addDropdown(
            'Newsletters to remove',
            'remove',
            $attributes
        );
    }
}
