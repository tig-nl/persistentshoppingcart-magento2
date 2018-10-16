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

namespace TIG\PersistentShoppingCart\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Cookie\Helper\Cookie as CookieHelper;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Cookie\Helper\Cookie $cookieHelper
     */
    protected $cookieHelper;

    /**
     * Data constructor.
     *
     * @param \Magento\Cookie\Helper\Cookie $cookieHelper
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        CookieHelper                          $cookieHelper,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->cookieHelper = $cookieHelper;

        parent::__construct(
            $context
        );
    }

    /**
     * Check if Cookie Restriction Mode is enabled.
     *
     * @return bool
     */
    public function isCookieRestricted()
    {
        return $this->cookieHelper->isUserNotAllowSaveCookie();
    }
}
