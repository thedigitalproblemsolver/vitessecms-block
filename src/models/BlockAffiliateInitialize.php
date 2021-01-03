<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Content\Models\Item;
use VitesseCms\Core\Utils\CookieUtil;
use MongoDB\BSON\ObjectId;
use DateTime;

class BlockAffiliateInitialize extends AbstractBlockModel
{
    public function loadAssets(Block $block): void
    {
        parent::loadAssets($block);

        $itemId = $this->view->getVar('currentId');
        $referer = $this->di->request->getServer('HTTP_REFERER');
        $websiteUrl = parse_url($this->view->getCurrentItem()->_('website'));
        $website = '';
        if (isset($websiteUrl['host'])) :
            $website = str_replace('www.', '', $websiteUrl['host']);
        endif;

        $inQuery = false;
        if ($this->di->request->get('a')) :
            $itemId = $this->di->request->get('a');
            $inQuery = true;
        endif;

        if (
            (
                $inQuery
                && !$this->di->session->has('affiliateParsed')
            ) || (
                in_array(
                    $this->view->getCurrentItem()->getDatagroup(),
                    $block->_('datagroups'),
                    true
                )
                && substr_count($website, $referer) > 0
                && !$this->di->session->has('affiliateParsed')
            )
        ) :
            CookieUtil::set(
                'affiliate-source',
                $itemId,
                (new DateTime())->modify('+30 days')->getTimestamp()
            );
            $this->di->log->write(
                new ObjectId($itemId),
                Item::class,
                'Affiliate entry for '.$website.' from '.$referer
            );
        endif;

        $this->di->session->set('affiliateParsed', true);
    }
}
