<?php

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Shop\Enum\OrderStateEnum;
use VitesseCms\Shop\Models\Order;
use VitesseCms\Shop\Models\OrderState;

/**
 * Class BlockShopPaymentResult
 */
class BlockShopPaymentResult extends AbstractBlockModel
{
    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        parent::initialize();
        if (
            !$this->di->user->isLoggedIn()
            || !$this->di->session->get('currentOrderId')
        ) :
            $this->di->flash->_('USER_NO_ACCESS', 'error');
            $this->di->response->redirect($this->di->url->getBaseUri());
        endif;

        if ($this->di->user->isLoggedIn()) :
            Order::setFindPublished(false);
            $order = Order::findById($this->di->session->get('currentOrderId'));
            if ((string)$this->di->user->getId() !== $order->_('shopper')['userId']) :
                $this->di->flash->_('USER_NO_ACCESS', 'error');
                $this->di->response->redirect($this->di->url->getBaseUri());
            endif;
        endif;

        $this->excludeFromCache = true;
    }

    /**
     * {@inheritdoc}
     * @throws \Phalcon\Mvc\Collection\Exception
     */
    public function parse(Block $block): void
    {
        parent::parse($block);

        if ($this->di->session->get('currentOrderId')) :
            Order::setFindPublished(false);
            $order = Order::findById($this->di->session->get('currentOrderId'));
            $orderState = OrderState::findById($order->_('orderState')['_id']);
            $block->set('orderState', $orderState);
            $block->set('order', $order);
            foreach ((array)$orderState->_('analyticsTriggers') as $trigger) :
                switch ($trigger):
                    case OrderStateEnum::ANALYTICS_TRIGGER_MAILCHIMP:
                        if ($this->di->session->get('mailchimpCampaignId')) :
                            //$this->di->mailchimp->addOrder($order, $this->di->session->get('mailchimpCampaignId'));
                        endif;
                        break;
                    default:
                        $block->set('trigger'.ucfirst($trigger), true);
                endswitch;
            endforeach;

            $this->di->log->write(
                $order->getId(),
                Order::class,
                'Order '.$order->_('orderId').' thankyou with orderstate '.$orderState->_('calling_name')
            );
        else :
            $this->di->flash->error('Order could not be found');
        endif;
    }
}
