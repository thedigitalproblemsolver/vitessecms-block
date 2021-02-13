<?php

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Communication\Models\NewsletterList;

/**
 * Class BlockNewsletterSubscriptions
 */
class BlockNewsletterSubscriptions extends AbstractBlockModel
{

    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        parent::initialize();

        $this->excludeFromCache = true;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Block $block): void
    {
        parent::parse($block);

        $newsletterLists = array_values($this->di->user->_('newsletterLists'));
        foreach ($newsletterLists as $key => $newsletterListArray) :
            $newletterList = NewsletterList::findById($newsletterListArray['newsletterListId']);
            $newsletterLists[$key]['newsletterListName'] = $newletterList->_('name');
        endforeach;
        $block->set('newsletterLists',$newsletterLists);
    }
}
