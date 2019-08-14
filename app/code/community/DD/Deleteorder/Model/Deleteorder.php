<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Model_Deleteorder extends Mage_Core_Model_Abstract {

    protected function _construct() {

        $this->_init("deleteorder/deleteorder");
    }

    /**
     * insert deleted order data into delete_slaes_flat_order table
     * @param string $orderId
     */
    public function insertOrderData($orderId) {
        try {
            $delOrderData = array();

            $order = Mage::getModel("sales/order")->load($orderId);
            $delOrderData = $order->getData();
            if (!empty($delOrderData)) {

                $collection = Mage::getResourceModel('sales/order_grid_collection');
                $collection->addAttributeToFilter('entity_id', $orderId);
                foreach ($collection->getData() as $arr) {
                    $delOrderData['shipping_name'] = $arr['shipping_name'];
                    $delOrderData['billing_name'] = $arr['billing_name'];
                }

                $delOrderData['payment_type'] = $order->getPayment()->getMethodInstance()->getTitle();
                $delOrderData['order_delete_date'] = date("Y-m-d h:i:s");

                $model = $this->setData($delOrderData);
                $model->save();
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
    }

    /**
     * get deleted order details
     * @param string $id
     * @return array
     */
    public function getOrderInfo($id) {
        $data = array();
        $deletedOrderData = Mage::getModel('deleteorder/deleteorder')->getCollection()
                ->addFieldToFilter('id', $id);
        foreach ($deletedOrderData->getData() as $value) {
            foreach ($value as $k => $v) {
                $data[$k] = $v;
            }
        }
        if (empty($data)) {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("Record no longer exists"));
            return false;
        } else {
            return $data;
        }
    }

}
