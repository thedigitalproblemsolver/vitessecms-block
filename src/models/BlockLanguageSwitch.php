<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Language\Models\Language;

class BlockLanguageSwitch extends AbstractBlockModel
{
    public function initialize()
    {
        parent::initialize();

        $this->excludeFromCache = true;
    }

    public function parse(Block $block): void
    {
        parent::parse($block);
        $languages = Language::find();

        if($this->view->getVar('currentItem')) :
            $currentItem = $this->view->getVar('currentItem');
            /** @var Language $language */
            foreach ($languages as $key => $language ):
                $language->set('slug',$currentItem->_('slug',$language->_('short')));
                $language->set('showDelimiter', true);
                if($language->_('slug') === '/') :
                    $language->set('slug',null);
                    $language->set('showDelimiter', false);
                elseif (substr_count($language->_('domain'),'/'.$language->getShortCode().'/')) :
                    $language->set('showDelimiter', false);
                endif;
            endforeach;
        endif;

        $this->view->set('hrefLanguages', $languages);
        $block->set('languages', $languages);
    }
}
