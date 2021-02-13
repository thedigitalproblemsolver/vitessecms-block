<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;

class BlockMailchimpInitialize extends AbstractBlockModel
{
    public function loadAssets(Block $block): void
    {
        if ($this->di->request->get('mc_cid')) :
            $this->di->session->set('mailchimpCampaignId', $this->di->request->get('mc_cid'));
        endif;
    }
}
