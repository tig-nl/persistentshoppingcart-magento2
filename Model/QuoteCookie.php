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

use TIG\PersistentShoppingCart\Model\AbstractToken;

class QuoteCookie extends AbstractToken
{
    /**
     * Initialize ResourceModel
     */
    public function _construct(
    ) {
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
}
