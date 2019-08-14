<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Model_Deleteorderaddress extends Mage_Core_Model_Abstract {

    protected function _construct() {

        $this->_init("deleteorder/deleteorderaddress");
    }

    /**
     * insert deleted order data into delete_slaes_flat_address table
     * @param string $orderId
     */
    public function insertOrderAddressData($orderId) {
        try {
            $order = Mage::getModel("sales/order")->load($orderId);
            if (!empty($order->getData())) {
                $billingAddress = $order->getBillingAddress();
                $shippingAddress = $order->getShippingAddress();
                if (is_array($billingAddress) && !empty($billingAddress)) {
                    $model1 = $this->setData($billingAddress->getData());
                    $model1->save();
                }
                if (is_array($shippingAddress) && !empty($shippingAddress)) {
                    $model2 = $this->setData($shippingAddress->getData());
                    $model2->save();
                }
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
    }

    /**
     * get shipping details
     * @param string $id
     * @return array
     */
    public function getOrderShippingData($id) {
        $data = array();
        $deletedOrderData = Mage::getModel('deleteorder/deleteorderaddress')->getCollection()
                ->addFieldToFilter('parent_id', $id)
                ->addFieldToFilter('address_type', 'shipping');
        foreach ($deletedOrderData->getData() as $value) {
            foreach ($value as $k => $v) {
                $data[$k] = $v;
            }
        }
        return $data;
    }

    /**
     * get billing details
     * @param string $id
     * @return array
     */
    public function getOrderBillingData($id) {
        $data = array();
        $deletedOrderData = Mage::getModel('deleteorder/deleteorderaddress')->getCollection()
                ->addFieldToFilter('parent_id', $id)
                ->addFieldToFilter('address_type', 'billing');
        foreach ($deletedOrderData->getData() as $value) {
            foreach ($value as $k => $v) {
                $data[$k] = $v;
            }
        }
        return $data;
    }

    /**
     * delete data from delete_slaes_flat_address table after deleteing records from history
     * @param string $order_id
     * @return boolean
     */
    public function deleteOrderAddress($order_id) {
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $write->query("delete from delete_sales_flat_order_address where parent_id ='" . $order_id . "'");
        return true;
    }

}
