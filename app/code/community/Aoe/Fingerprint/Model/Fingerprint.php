<?php
/**
 * Aoe_Fingerprint extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Aoe
 * @package        Aoe_Fingerprint
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Fingerprint model
 *
 * @category    Aoe
 * @package     Aoe_Fingerprint
 * @author      Ultimate Module Creator
 */
class Aoe_Fingerprint_Model_Fingerprint extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'aoe_fingerprint_fingerprint';
    const CACHE_TAG = 'aoe_fingerprint_fingerprint';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'aoe_fingerprint_fingerprint';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'fingerprint';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('aoe_fingerprint/fingerprint');
    }

    /**
     * before save fingerprint
     *
     * @access protected
     * @return Aoe_Fingerprint_Model_Fingerprint
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save fingerprint relation
     *
     * @access public
     * @return Aoe_Fingerprint_Model_Fingerprint
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

}
