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
 * Fingerprint admin edit form
 *
 * @category    Aoe
 * @package     Aoe_Fingerprint
 * @author      Ultimate Module Creator
 */
class Aoe_Fingerprint_Block_Adminhtml_Fingerprint_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        parent::__construct();
        $this->_blockGroup = 'aoe_fingerprint';
        $this->_controller = 'adminhtml_fingerprint';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('aoe_fingerprint')->__('Save Fingerprint')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('aoe_fingerprint')->__('Delete Fingerprint')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('aoe_fingerprint')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_fingerprint') && Mage::registry('current_fingerprint')->getId()) {
            return Mage::helper('aoe_fingerprint')->__(
                "Edit Fingerprint '%s'",
                $this->escapeHtml(Mage::registry('current_fingerprint')->getCreatedBy())
            );
        } else {
            return Mage::helper('aoe_fingerprint')->__('Add Fingerprint');
        }
    }
}
