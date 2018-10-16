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

use TIG\PersistentShoppingCart\Model\AbstractModel;

class AbstractToken extends AbstractModel
{
    /**
     * Name of DB field for storing public use token values.
     *
     * @var string
     */
    protected $_tokenField = 'token';

    /**
     * Expected length of token values in characters.
     *
     * @var int
     */
    protected $_tokenLength = 32;

    /**
     * @return $this|\TIG\PersistentShoppingCart\Model\AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        parent::beforeSave();

        $this->getToken();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        if (!$this->hasData($this->_tokenField)) {
            $this->setData($this->_tokenField, $this->generateToken());
        }

        return $this->getData($this->_tokenField);
    }

    /**
     * Generate a random value suitable for public use.
     * Replace '+' because it doesn't URL encode properly.
     *
     * @param null $length
     *
     * @return string
     */
    public function generateToken($length = null)
    {
        $length = $length ? $length : $this->_tokenLength;

        return strtr(base64_encode(openssl_random_pseudo_bytes($length * 0.75)), '+/' , '..');
    }

    /**
     * @todo replace deprecated load method.
     *
     * @return \TIG\PersistentShoppingCart\Model\AbstractModel|\TIG\PersistentShoppingCart\Model\AbstractToken
     */
    public function readCookie()
    {
        return $this->load($this->_readCookie(), $this->_tokenField);
    }

    /**
     * @return $this|\TIG\PersistentShoppingCart\Model\AbstractModel
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function writeCookie()
    {
        $this->_updateCookie($this->getToken());

        return $this;
    }
}
