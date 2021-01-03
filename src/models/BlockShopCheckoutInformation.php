<?php declare(strict_types=1);

namespace VitesseCms\Block\Models;

use VitesseCms\Block\AbstractBlockModel;
use VitesseCms\Content\Models\Item;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Core\Models\Datagroup;
use VitesseCms\Form\Forms\BaseForm;
use VitesseCms\Shop\Models\Shopper;
use VitesseCms\User\Forms\LoginForm;
use MongoDB\BSON\ObjectID;

class BlockShopCheckoutInformation extends AbstractBlockModel
{
    public function initialize()
    {
        parent::initialize();

        $this->excludeFromCache = true;
    }

    public function parse(Block $block): void
    {
        parent::parse($block);

        $logMessage = 'Checkout information';
        if ($this->di->user->isLoggedIn()):
            Shopper::setFindValue('userId', (string)$this->di->user->getId());
            $shopper = Shopper::findFirst();

            if (!$shopper) :
                $this->di->flash->error('We are missing some addres information. Please fil in this form');
                $this->di->response->redirect($this->di->url->getBaseUri().'shop/shopper/edit/');
                $logMessage .= ' but shopper is missing';
                $this->di->view->disable();
            else :
                $datagroup = $this->di->setting->get('SHOP_DATAGROUP_SHOPPERSHIPTO');
                Item::setFindValue('userId', (string)$this->di->user->getId());
                Item::setFindValue('datagroup', $datagroup);
                $shiptoAddresses = Item::findAll();

                Item::setFindValue('datagroup', $this->di->setting->get('SHOP_DATAGROUP_CHECKOUT'));
                /** @var AbstractCollection $checkoutPage */
                $checkoutPage = Item::findFirst();
                /** @var AbstractCollection $nextStep */
                $nextStep = $this->di->shop->checkout->getNextStep();

                $block->set('checkoutLink', $nextStep->_('slug'));
                $block->set('cartLink', $checkoutPage->_('slug'));
                $block->set('shopper', $shopper);
                //TODO set to global variable?
                $block->set('user', $shopper->_('user'));
                $block->set('shiptoAddresses', $shiptoAddresses);
                $block->set('shiptoAddressesSelected', $this->di->session->get('shiptoAddress', 'invoice'));
                $block->set('loggendIn', 1);
            endif;
        else :
            $nextStep = $this->di->shop->checkout->getNextStep();
            $logMessage .= ' user logged out';
            $formRegistration = new BaseForm();
            /** @var Datagroup $datagroup */
            $datagroup = Datagroup::findById($this->di->setting->get('SHOP_DATAGROUP_REGISTRATIONFORM'));
            $datagroup->buildItemForm($formRegistration);
            $formRegistration->_('submit', '%FORM_SUBMIT% %CORE_AND% %CORE_TO% '.strtolower($nextStep->_('name')));

            $formLogin = new LoginForm();

            $block->set('registrationForm', $formRegistration->renderForm('shop/checkout/register/'));
            $block->set('loginForm', $formLogin->renderForm('user/login'));
            $block->set('loggendIn', 0);
        endif;

        $this->di->log->write(
            new ObjectID($this->view->getVar('currentId')),
            Item::class,
            $logMessage
        );
    }
}
