<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Block_Adminhtml_Sales_Order_View extends Mage_Adminhtml_Block_Sales_Order_View {

    public function __construct() {
        parent::__construct();
        $currentUser = Mage::helper('deleteorder')->getCurrentUser();
        $defaultAdmin = Mage::helper('deleteorder')->getDefaultAdmin();
        $allowedUsers = Mage::helper('deleteorder')->getAllowedUsers();

        if ($defaultAdmin == '' || in_array($currentUser, $allowedUsers) || $currentUser == $defaultAdmin) {
            if (Mage::helper('deleteorder')->isEnabled() && Mage::helper('deleteorder')->isOrderViewDeleteEnabled()) {
                $message = 'Are you sure?';
                $_order = $this->getOrder();
                $order_id = $_order->getId();
                $url = Mage_Adminhtml_Block_Sales_Order_Grid::getUrl('deleteorder/adminhtml_deleteorder/deleteOrder', array('order_id' => $order_id));
                $this->_addButton('delete_order', array(
                    'label' => Mage::helper('deleteorder')->__('Delete'),
                    'onclick' => "confirmSetLocation('{$message}', '{$url}')",
                    'class' => 'delete'
                        ));
            }
        }
    }

}
