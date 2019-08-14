<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Block_Field_Enabledeleteorderview_Renderer extends Mage_Adminhtml_Block_System_Config_Form_Field {

    /**
     * to disable 'enable delete order' field.
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $currentUser = Mage::helper('deleteorder')->getCurrentUser();
        $allowedUsers = Mage::helper('deleteorder')->getAllowedUsers();
        $defaultAdmin = Mage::helper('deleteorder')->getDefaultAdmin();
        if ($defaultAdmin == '' || empty($allowedUsers)) {
            
        } elseif ($currentUser != $defaultAdmin && !in_array($currentUser, $allowedUsers)) {
            $element->setDisabled('disabled');
        }
        return parent::_getElementHtml($element);
    }

}
