<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Model_Deleteorderitem extends Mage_Core_Model_Abstract {

    protected function _construct() {

        $this->_init("deleteorder/deleteorderitem");
    }

    /**
     * insert deleted order data into delete_slaes_flat_item table
     * @param string $orderId
     */
    public function insertOrderItemData($orderId) {
        try {
            $delOrderItemData = array();
            $order = Mage::getModel("sales/order")->load($orderId);
            if (!empty($order->getData())) {
                $data = $order->getItemsCollection();
                foreach ($data->getData() as $value) {
                    foreach ($value as $k => $v) {
                        $delOrderItemData[$k] = $v;
                    }
                    $model = $this->setData($delOrderItemData);
                    $model->save();
                }
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
    }

    /**
     * get ordered item details
     * @param string $id
     * @return array
     */
    public function getOrderItem($id) {
        try {
            $data = array();
            $deletedOrderData = Mage::getModel('deleteorder/deleteorderitem')->getCollection()
                    ->addFieldToFilter('order_id', $id);
            foreach ($deletedOrderData->getData() as $key => $value) {
                $data[$key] = $value;
            }
            return $data;
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
    }

    /**
     * delete data from delete_slaes_flat_item table after deleteing records from history
     * @param string $order_id
     * @return boolean
     */
    public function deleteOrderItem($order_id) {
        try {
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $write->query("delete from delete_sales_flat_order_item where order_id ='" . $order_id . "'");
            return true;
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
    }

}
