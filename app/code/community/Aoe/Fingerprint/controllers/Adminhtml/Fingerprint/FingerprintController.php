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
 * Fingerprint admin controller
 *
 * @category    Aoe
 * @package     Aoe_Fingerprint
 * @author      Ultimate Module Creator
 */
class Aoe_Fingerprint_Adminhtml_Fingerprint_FingerprintController extends Aoe_Fingerprint_Controller_Adminhtml_Fingerprint
{
    /**
     * init the fingerprint
     *
     * @access protected
     * @return Aoe_Fingerprint_Model_Fingerprint
     */
    protected function _initFingerprint()
    {
        $fingerprintId  = (int) $this->getRequest()->getParam('id');
        $fingerprint    = Mage::getModel('aoe_fingerprint/fingerprint');
        if ($fingerprintId) {
            $fingerprint->load($fingerprintId);
        }
        Mage::register('current_fingerprint', $fingerprint);
        return $fingerprint;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('aoe_fingerprint')->__('Fingerprints'))
             ->_title(Mage::helper('aoe_fingerprint')->__('Fingerprints'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * diff action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function diffAction()
    {
        $fingerprintIds = $this->getRequest()->getParam('fingerprint');
        if (!is_array($fingerprintIds) || count($fingerprintIds) != 2) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('aoe_fingerprint')->__('Please select two fingerprints to compare.')
            );
        } else {
            $fingerprintA = Mage::getModel('aoe_fingerprint/fingerprint')->load($fingerprintIds[0]);
            $fingerprintB = Mage::getModel('aoe_fingerprint/fingerprint')->load($fingerprintIds[1]);

            $filesA = json_decode($fingerprintA->getChecksums(), true);
            $filesB = json_decode($fingerprintB->getChecksums(), true);

            $filesInAButNotInB = array_diff(array_keys($filesA), array_keys($filesB));
            $filesInBButNotInA = array_diff(array_keys($filesB), array_keys($filesB));

            $diff = array_diff_assoc($filesA, $filesB);

            $result = array_combine(array_keys($diff), array_values(array_fill(1, count($diff), 'diff')));
            if (count($filesInAButNotInB)) {
                $result = array_merge($result, array_combine($filesInAButNotInB, array_values(array_fill(1, count($filesInAButNotInB), 'filesInAButNotInB'))));
            }
            if (count($filesInBButNotInA)) {
                $result = array_merge($result, array_combine($filesInBButNotInA, array_values(array_fill(1, count($filesInBButNotInA), 'filesInBButNotInA'))));
            }

            ksort($result);

            $this->getResponse()->setBody(var_export($result, true));
            return;
        }
        $this->_redirect('*/*/index');

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * edit fingerprint - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $fingerprintId    = $this->getRequest()->getParam('id');
        $fingerprint      = $this->_initFingerprint();
        if ($fingerprintId && !$fingerprint->getId()) {
            $this->_getSession()->addError(
                Mage::helper('aoe_fingerprint')->__('This fingerprint no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getFingerprintData(true);
        if (!empty($data)) {
            $fingerprint->setData($data);
        }
        Mage::register('fingerprint_data', $fingerprint);
        $this->loadLayout();
        $this->_title(Mage::helper('aoe_fingerprint')->__('Fingerprints'))
             ->_title(Mage::helper('aoe_fingerprint')->__('Fingerprints'));
        if ($fingerprint->getId()) {
            $this->_title($fingerprint->getCreatedBy());
        } else {
            $this->_title(Mage::helper('aoe_fingerprint')->__('Add fingerprint'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new fingerprint action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save fingerprint - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('fingerprint')) {
            try {
                $fingerprint = $this->_initFingerprint();
                $fingerprint->addData($data);
                $fingerprint->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('aoe_fingerprint')->__('Fingerprint was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $fingerprint->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFingerprintData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_fingerprint')->__('There was a problem saving the fingerprint.')
                );
                Mage::getSingleton('adminhtml/session')->setFingerprintData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('aoe_fingerprint')->__('Unable to find fingerprint to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete fingerprint - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $fingerprint = Mage::getModel('aoe_fingerprint/fingerprint');
                $fingerprint->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('aoe_fingerprint')->__('Fingerprint was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_fingerprint')->__('There was an error deleting fingerprint.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('aoe_fingerprint')->__('Could not find fingerprint to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete fingerprint - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $fingerprintIds = $this->getRequest()->getParam('fingerprint');
        if (!is_array($fingerprintIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('aoe_fingerprint')->__('Please select fingerprints to delete.')
            );
        } else {
            try {
                foreach ($fingerprintIds as $fingerprintId) {
                    $fingerprint = Mage::getModel('aoe_fingerprint/fingerprint');
                    $fingerprint->setId($fingerprintId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('aoe_fingerprint')->__('Total of %d fingerprints were successfully deleted.', count($fingerprintIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_fingerprint')->__('There was an error deleting fingerprints.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $fingerprintIds = $this->getRequest()->getParam('fingerprint');
        if (!is_array($fingerprintIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('aoe_fingerprint')->__('Please select fingerprints.')
            );
        } else {
            try {
                foreach ($fingerprintIds as $fingerprintId) {
                $fingerprint = Mage::getSingleton('aoe_fingerprint/fingerprint')->load($fingerprintId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d fingerprints were successfully updated.', count($fingerprintIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_fingerprint')->__('There was an error updating fingerprints.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'fingerprint.csv';
        $content    = $this->getLayout()->createBlock('aoe_fingerprint/adminhtml_fingerprint_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'fingerprint.xls';
        $content    = $this->getLayout()->createBlock('aoe_fingerprint/adminhtml_fingerprint_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'fingerprint.xml';
        $content    = $this->getLayout()->createBlock('aoe_fingerprint/adminhtml_fingerprint_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/aoe_fingerprint/fingerprint');
    }
}
