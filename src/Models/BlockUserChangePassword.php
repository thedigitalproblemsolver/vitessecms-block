<?php

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;

/**
 * Class BlockShopUserOrders
 */
class BlockUserChangePassword extends AbstractBlockModel
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

        $block->set('loggedIn', $this->di->user->isLoggedIn());
    }

}
