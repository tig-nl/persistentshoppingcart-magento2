<?php
/**
 *
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */

namespace TIG\PersistentShoppingCart\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use TIG\PersistentShoppingCart\Model\QuoteCookie;

class SendResponseBefore implements ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session $checkoutSession
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    protected $customerSession;

    /**
     * @var \TIG\PersistentShoppingCart\Model\QuoteCookie $quoteCookie
     */
    protected $quoteCookie;

    /**
     * SendResponseBefore constructor.
     *
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \TIG\PersistentShoppingCart\Model\QuoteCookie $quoteCookie
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        QuoteCookie     $quoteCookie
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->quoteCookie     = $quoteCookie;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function execute(
        Observer $observer
    ) {
        $this->writeCartCookie();
    }

    /**
     * Process Cookie if customer is not logged in.
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function writeCartCookie()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $quote = $this->getQuote();

            $this->processCookie($quote);

            return;
        }

        $this->quoteCookie->removeCookie();
    }

    /**
     * Write cookie only if QuoteCookie object is new.
     *
     * @param \TIG\PersistentShoppingCart\Model\QuoteCookie $quote
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    protected function processCookie(QuoteCookie $quote)
    {
        if (!$quote->isObjectNew()) {
            $quote->writeCookie();

            return;
        }

        $quote->removeCookie();
    }

    /**
     * @todo: replace deprecated load and save methods.
     *
     * @return \TIG\PersistentShoppingCart\Model\QuoteCookie
     * @throws \Exception
     */
    protected function getQuote()
    {
        $quote   = $this->quoteCookie;
        $quoteId = $this->checkoutSession->getQuoteId();

        if ($quoteId && $quote->isObjectNew()) {
            $this->processQuote($quote, $quoteId);
        }

        return $quote;
    }

    /**
     * Load Quote object. If there's nothing to load, create it.
     *
     * @param \TIG\PersistentShoppingCart\Model\QuoteCookie $quote
     * @param $quoteId
     *
     * @throws \Exception
     */
    protected function processQuote(QuoteCookie $quote, $quoteId)
    {
        $quote->load($quoteId);

        if ($quote->isObjectNew()) {
            $quote->setId($quoteId)
                ->save();
        }
    }
}
