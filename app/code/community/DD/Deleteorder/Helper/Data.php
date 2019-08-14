<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * get delete by type in manual delete form
     * @return array
     */
    public static function getDeleteBy() {
        return array(
            '1' => Mage::helper('deleteorder')->__('Date'),
            '2' => Mage::helper('deleteorder')->__('Increment ID'),
        );
    }

    /**
     * Check if module is enable or not
     * @return int
     */
    public function isEnabled() {
        return Mage::getStoreConfig("deleteordersettings/general/enable");
    }

    /**
     * Check if save delete history is enable or not
     * @return int
     */
    public function isHistoryEnabled() {
        return Mage::getStoreConfig("deleteordersettings/general/orderdeletehistory");
    }

    /**
     * Check if delete from grid is enable or not
     * @return int
     */
    public function isGridDeleteEnabled() {
        return Mage::getStoreConfig("deleteordersettings/general/enablegriddelete");
    }
    
    /**
     * Check if delete from grid is enable or not
     * @return int
     */
    public function isOrderViewDeleteEnabled() {
        return Mage::getStoreConfig("deleteordersettings/general/enableorderviewdelete");
    }

    /**
     * get product options
     * @param array $opt
     * @return array
     */
    public function getOptions($opt) {
        $options = array();
        $customOptions = unserialize($opt);
        foreach ($customOptions as $key => $value) {
            $options[$key] = $value;
        }
        return $options;
    }

    /**
     * get current admin username
     * @return string
     */
    public function getCurrentUser() {
        Mage::getSingleton('core/session', array('name' => 'adminhtml'));
        if (Mage::getSingleton('admin/session')->isLoggedIn()) {
            $user = Mage::getSingleton('admin/session')->getUser();
            if ($user->getId()) {
                return $user->getUsername();
            } else {
                
            }
        }
    }

    /**
     * get all other admin user details
     * @return array
     */
    public function getOtherUsers() {
        $nouser = array(
            0 => array(
                'label' => 'There is no other users for this site',
                'value' => 'There is no other users for this site',
            )
        );
        $currentUser = $this->getCurrentUser();
        $i = 0;
        $arr = array();
        $allUsers = array();
        $users = Mage::getModel('admin/user')->getCollection()->getData();
        foreach ($users as $value) {
            foreach ($value as $k => $v) {
                if ($k == 'username' && $currentUser != $v) {
                    $arr['value'] = $v;
                    $arr['label'] = $v;
                    $allUsers[$i++] = $arr;
                } else {
                    
                }
            }
        }
        if (empty($allUsers)) {
            return $nouser;
        } else {
            return $allUsers;
        }
    }

    /**
     * get all admin user details
     * @return array
     */
    public function getAllUsers() {
        $i = 0;
        $arr = array();
        $allUsers = array();
        $users = Mage::getModel('admin/user')->getCollection()->getData();
        foreach ($users as $value) {
            foreach ($value as $k => $v) {
                if ($k == 'username') {
                    $arr['value'] = $v;
                    $arr['label'] = $v;
                    $allUsers[$i++] = $arr;
                } else {
                    
                }
            }
        }
        return $allUsers;
    }

    /**
     * get all allowed users who have privileges to delete the orders
     * @return array
     */
    public function getAllowedUsers() {
        return explode(',', Mage::getStoreConfig('deleteordersettings/general/deleterole'));
    }

    /**
     * get default(main) admin user
     * @return string
     */
    public function getDefaultAdmin() {
        return Mage::getStoreConfig('deleteordersettings/general/defaultadmin');
    }

}
