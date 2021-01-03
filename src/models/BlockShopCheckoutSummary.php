<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Content\Models\Item;
use VitesseCms\Shop\Helpers\CheckoutHelper;
use VitesseCms\Shop\Models\Shopper;
use MongoDB\BSON\ObjectID;

class BlockShopCheckoutSummary extends AbstractBlockModel
{
    public function initialize()
    {
        parent::initialize();

        $this->excludeFromCache = true;
    }

    public function parse(Block $block): void
    {
        parent::parse($block);

        $logMessage = 'Checkout summary';
        if ($this->di->user->isLoggedIn()):
            Shopper::setFindValue('userId',(string)$this->di->user->getId());
            $shopper = Shopper::findFirst();
            if(!$shopper) :
                $this->di->flash->error('We are missing some addres information. Please fil in this form');
                $this->di->response->redirect('shop/shopper/edit/');
                $logMessage .= ' but shopper is missing';
                $this->di->view->disable();
            endif;

            $shiptoAddress = CheckoutHelper::getShiptoAddress($this->di->user);
            $shiptoAddress->set('companyName', $shopper->_('companyName'));

            $previousStep = $this->di->shop->checkout->getPreviousStep();
            $cart = $this->di->shop->cart->getCartFromSession();
            $this->di->shop->cart->setBlockBasics($block, $cart);
            $block->set('shopper', $shopper);
            $block->set('user', $shopper->_('user'));
            $block->set('checkoutLink', 'shop/order/saveAndPay');
            $block->set('shiptoAddresses', $shiptoAddress);
            $block->set('backLink', $previousStep->_('slug'));
        else :
            $logMessage .= ' user logged out';
            $this->di->flash->_('USER_NO_ACCESS', 'error');
            $this->di->response->setStatusCode(401, 'Unauthorized')->redirect('');
        endif;

        $this->di->log->write(
            new ObjectID($this->view->getVar('currentId')),
            Item::class,
            $logMessage
        );
    }
}
