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
 * Fingerprint edit form tab
 *
 * @category    Aoe
 * @package     Aoe_Fingerprint
 * @author      Ultimate Module Creator
 */
class Aoe_Fingerprint_Block_Adminhtml_Fingerprint_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Aoe_Fingerprint_Block_Adminhtml_Fingerprint_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('fingerprint_');
        $form->setFieldNameSuffix('fingerprint');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'fingerprint_form',
            array('legend' => Mage::helper('aoe_fingerprint')->__('Fingerprint'))
        );

        $fieldset->addField(
            'created_by',
            'text',
            array(
                'label' => Mage::helper('aoe_fingerprint')->__('Created by'),
                'name'  => 'created_by',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'checksums',
            'textarea',
            array(
                'label' => Mage::helper('aoe_fingerprint')->__('Checksums'),
                'name'  => 'checksums',
            'required'  => true,
            'class' => 'required-entry',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('aoe_fingerprint')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('aoe_fingerprint')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('aoe_fingerprint')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_fingerprint')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getFingerprintData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getFingerprintData());
            Mage::getSingleton('adminhtml/session')->setFingerprintData(null);
        } elseif (Mage::registry('current_fingerprint')) {
            $formValues = array_merge($formValues, Mage::registry('current_fingerprint')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
