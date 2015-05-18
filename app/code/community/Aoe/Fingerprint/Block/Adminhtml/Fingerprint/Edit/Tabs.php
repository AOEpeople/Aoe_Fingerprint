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
 * Fingerprint admin edit tabs
 *
 * @category    Aoe
 * @package     Aoe_Fingerprint
 * @author      Ultimate Module Creator
 */
class Aoe_Fingerprint_Block_Adminhtml_Fingerprint_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('fingerprint_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('aoe_fingerprint')->__('Fingerprint'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Aoe_Fingerprint_Block_Adminhtml_Fingerprint_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_fingerprint',
            array(
                'label'   => Mage::helper('aoe_fingerprint')->__('Fingerprint'),
                'title'   => Mage::helper('aoe_fingerprint')->__('Fingerprint'),
                'content' => $this->getLayout()->createBlock(
                    'aoe_fingerprint/adminhtml_fingerprint_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve fingerprint entity
     *
     * @access public
     * @return Aoe_Fingerprint_Model_Fingerprint
     * @author Ultimate Module Creator
     */
    public function getFingerprint()
    {
        return Mage::registry('current_fingerprint');
    }
}
