<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Content\Models\Item;
use VitesseCms\Core\Factories\PaginatonFactory;
use VitesseCms\Shop\Models\Order;

class BlockAffiliateOrderOverview extends AbstractBlockModel
{
    public function initialize()
    {
        parent::initialize();

        $this->excludeFromCache = true;
    }

    public function parse(Block $block): void
    {
        parent::parse($block);

        if ($this->di->user->isLoggedIn()) :
            Item::setFindValue('datagroup', $this->di->setting->get('SHOP_DATAGROUP_AFFILIATE'));
            Item::setFindValue('affiliateUser', (string)$this->di->user->getId());
            $affiliateProperties = Item::findAll();
            if ($affiliateProperties) :
                $affiliatePropertyIds = [];
                foreach ($affiliateProperties as $affiliateProperty) {
                    $affiliatePropertyIds[] = (string)$affiliateProperty->getId();
                }
                Order::setFindPublished(false);
                Order::setFindValue('affiliateId', ['$in' => $affiliatePropertyIds]);
                Order::addFindOrder('orderId', -1);
                $orders = Order::findAll();
                $pagination = PaginatonFactory::createFromArray($orders, $this->di->request, $this->di->url);

                $orderList = $this->view->renderTemplate(
                    'affiliate_orderlist',
                    $this->di->configuration->getRootDir().'template/core/views/partials/shop',
                    [
                        'orderlistOrders' => $pagination->items,
                        'pagination' => $pagination
                    ]
                );

                $block->set('orderList', $orderList);
            endif;
        endif;
    }
}
