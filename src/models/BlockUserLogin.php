<?php

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;

/**
 * Class BlockUserLogin
 */
class BlockUserLogin extends AbstractBlockModel
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

        $block->set('loginUrl', 'user/loginform');
        $block->set('loggendIn', 0);

        if ($this->di->user->isLoggedIn()):
            $block->set('loginUrl', 'user/logout');
            $block->set('loggendIn', 1);
        endif;
    }
}
