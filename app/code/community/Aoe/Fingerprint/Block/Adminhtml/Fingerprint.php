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
 * Fingerprint admin block
 *
 * @category    Aoe
 * @package     Aoe_Fingerprint
 * @author      Ultimate Module Creator
 */
class Aoe_Fingerprint_Block_Adminhtml_Fingerprint extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_fingerprint';
        $this->_blockGroup         = 'aoe_fingerprint';
        parent::__construct();
        $this->_headerText         = Mage::helper('aoe_fingerprint')->__('Fingerprint');
        $this->_updateButton('add', 'label', Mage::helper('aoe_fingerprint')->__('Add Fingerprint'));

    }
}
