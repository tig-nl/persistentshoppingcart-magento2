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

use Magento\Framework\Model\AbstractModel as FrameworkAbstractModel;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use TIG\PersistentShoppingCart\Helper\Data as Helper;

abstract class AbstractModel extends FrameworkAbstractModel
{
    /**
     * @var string
     */
    private $cookieName = 'shopping_cart_cookie';

    /**
     * @var \Magento\Framework\Session\Config\ConfigInterface $sessionConfig
     */
    private $sessionConfig;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     */
    private $cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadata
     */
    private $cookieMetadata;

    /**
     * @var \TIG\PersistentShoppingCart\Helper\Data $helper
     */
    private $helper;

    /**
     * @var \TIG\PersistentShoppingCart\Helper\Configuration\GuestCookieCartLifetime
     */
    protected $guestCartCookieConfiguration;

    /**
     * AbstractModel constructor.
     *
     * @param \Magento\Framework\Session\Config\ConfigInterface $sessionConfig
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadata
     * @param \TIG\PersistentShoppingCart\Helper\Data $helper
     * @param \TIG\PersistentShoppingCart\Helper\Configuration\GuestCookieCartLifetime $guestCartCookieConfiguration
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     */
    public function __construct(
        ConfigInterface $sessionConfig,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadata,
        Helper $helper,
        \TIG\PersistentShoppingCart\Helper\Configuration\GuestCookieCartLifetime $guestCartCookieConfiguration,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null
    ) {
        $this->sessionConfig  = $sessionConfig;
        $this->cookieManager  = $cookieManager;
        $this->cookieMetadata = $cookieMetadata;
        $this->helper         = $helper;
        $this->guestCartCookieConfiguration = $guestCartCookieConfiguration;

        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }

    /**
     * Actions to use cookie data.
     *
     * @return \TIG\PersistentShoppingCart\Model\AbstractModel $this
     */
    abstract public function readCookie();

    /**
     * Update cookie with latest data.
     *
     * @return \TIG\PersistentShoppingCart\Model\AbstractModel $this
     */
    abstract public function writeCookie();
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->cookieName;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function removeCookie()
    {
        $cookie = $this->cookieManager;

        if ($cookie->getCookie($this->cookieName)) {
            $cookie->deleteCookie($this->cookieName);
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function _readCookie()
    {
        $value = $this->cookieManager->getCookie($this->cookieName);

        return isset($value) ? (string) $value : null;
    }

    /**
     * First check if we're allowed to create the cookie, if so, start.
     *
     * @param $value
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function _updateCookie($value)
    {
        if ($this->helper->isCookieRestricted()) {
            return;
        }

        if ($this->cookieManager->getCookie($this->cookieName) !== $value) {
            $this->processClientCookie($value);
        }
    }

    /**
     * Create Cookie metadata and if $value is set, set cookie. Otherwise, delete cookie.
     *
     * Uses Cookie Lifetime settings within web/cookie/cookie_restriction_lifetime.
     *
     * @param $value
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    private function processClientCookie($value)
    {
        $metadata = $this->cookieMetadata->createPublicCookieMetadata();
        $metadata->setDuration($this->getCookieDuration());
        $metadata->setPath($this->sessionConfig->getCookiePath());
        $metadata->setDomain($this->sessionConfig->getCookieDomain());

        if (isset($value)) {
            $this->cookieManager->setPublicCookie($this->cookieName, $value, $metadata);

            return;
        }

        $this->cookieManager->deleteCookie($this->cookieName);
    }

    private function getCookieDuration()
    {
        if($this->guestCartCookieConfiguration->getGuestCookieLifetimeConfig() > 0) {
            return intval($this->guestCartCookieConfiguration->getGuestCookieLifetimeConfig());
        }

        return $this->sessionConfig->getCookieLifetime();
    }
}
