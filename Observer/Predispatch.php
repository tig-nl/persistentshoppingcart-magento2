<?php
/**
 * @codingStandardsIgnoreStart
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@totalinternetgroup.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * @codingStandardsIgnoreEnd
 */

namespace TIG\PersistentShoppingCart\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use TIG\PersistentShoppingCart\Model\QuoteCookie;

class Predispatch implements ObserverInterface
{
    /**
     * @var \TIG\PersistentShoppingCart\Model\QuoteCookie $quoteCookie
     */
    private $quoteCookie;

    /**
     * Predispatch constructor.
     *
     * @param \Magento\Checkout\Model\SessionFactory $checkoutSession
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param \TIG\PersistentShoppingCart\Model\QuoteCookie $quoteCookie
     */
    public function __construct(
        QuoteCookie $quoteCookie
    ) {
        $this->quoteCookie = $quoteCookie;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @codingStandardsIgnoreStart
     * $observer is never used and that's correct.
     */
    public function execute(
        Observer $observer
    ) {

        /**
         * When module status is false, then it will not run readCartCookie
         */
        if(!$this->quoteCookie->getModuleStatus()){
            return;
        }

        $this->readCartCookie();
    }

    /**
     * Read cookie's value before cart is manipulated.
     */
    private function readCartCookie()
    {
        $customerSession = $this->quoteCookie->getCustomerSession();
        if ($customerSession->isLoggedIn()) {
            return;
        }

        /**
         * If Cart is already in use, do not override.
         */
        $checkoutSession = $this->quoteCookie->getCheckoutSession();
        if ($checkoutSession->getQuoteId()) {
            return;
        }

        /**
         * Get quote_cookie data from custom table. If $quote_cookie->getId() exists,
         * set it as the ID of the current quote.
         */
        $quote = $this->quoteCookie->readCookie();
        if ($quote->getId()) {
            $checkoutSession->setQuoteId($quote->getId());
            $checkoutSession->resetCheckout();
        }
    }
}
