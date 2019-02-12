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

namespace TIG\PersistentShoppingCart\Model;

use Magento\Checkout\Model\SessionFactory as CheckoutSession;
use Magento\Customer\Model\SessionFactory as CustomerSession;


class QuoteCookie extends AbstractToken
{
    /** @var \Magento\Checkout\Model\SessionFactory */
    private $checkoutSession;

    /** @var \Magento\Customer\Model\SessionFactory */
    private $customerSession;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    private $scopeConfig;

    /**
     * Config path to module status
     */
    const XPATH_MODULE_ENABLED = 'tig_persistentshoppingcart/general/enabled';

    /**
     * QuoteCookie constructor.
     *
     * @param \Magento\Checkout\Model\SessionFactory $checkoutSession
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param \Magento\Framework\Session\Config\ConfigInterface $sessionConfig
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadata
     * @param \TIG\PersistentShoppingCart\Helper\Data $helper
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        \Magento\Framework\Session\Config\ConfigInterface $sessionConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadata,
        \TIG\PersistentShoppingCart\Helper\Data $helper,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;

        parent::__construct(
            $sessionConfig,
            $cookieManager,
            $cookieMetadata,
            $helper,
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }

    /**
     * Initialize ResourceModel
     */
    public function _construct()
    {
        $this->_init('\TIG\PersistentShoppingCart\Model\ResourceModel\QuoteCookie');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->cookieName;
    }

    /**
     * @return $this|\TIG\PersistentShoppingCart\Model\AbstractModel|\TIG\PersistentShoppingCart\Model\AbstractToken
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function writeCookie()
    {
        parent::writeCookie();

        return $this;
    }

    /**
     * @return \Magento\Checkout\Model\Session
     */
    public function getCheckoutSession()
    {
        return $this->checkoutSession->create();
    }

    /**
     * @return \Magento\Customer\Model\Session
     */
    public function getCustomerSession()
    {
        return $this->customerSession->create();
    }

    /**
     * @return boolean
     * Check module config status
     */
    public function getModuleStatus()
    {
        return $this->scopeConfig->getValue(
            self::XPATH_MODULE_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
