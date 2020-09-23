<?php

namespace TIG\PersistentShoppingCart\Helper\Configuration;

class GuestCookieCartLifetime extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XPATH_CART_COOKIE_LIFETIME = 'tig_persistentshoppingcart/general/guest_cookie_lifetime';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    ) {
        parent::__construct($context);

        $this->scopeConfig = $scopeConfigInterface;
    }

    public function getGuestCookieLifetimeConfig()
    {
        return $this->scopeConfig->getValue(
            self::XPATH_CART_COOKIE_LIFETIME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}