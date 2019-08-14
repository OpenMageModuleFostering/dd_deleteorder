<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Adminhtml_DeleteorderController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()->_setActiveMenu("deleteorder/deleteorder")->_addBreadcrumb(Mage::helper("adminhtml")->__("Delete Order Manager"), Mage::helper("adminhtml")->__("Delete Order Manager"));
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__("Delete Orders"));
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * to view deleted order information
     */
    public function viewInfoAction() {
        $this->loadLayout()->_setActiveMenu("deleteorder/deleteorder");
        $this->_addContent($this->getLayout()->createBlock('deleteorder/adminhtml_viewinfo'))
                ->_addLeft($this->getLayout()->createBlock('deleteorder/adminhtml_viewinfo_edit_tabs'));
        $this->renderLayout();
    }

    /**
     * to get manual delete form view.
     */
    public function manualDeleteViewAction() {
        $this->loadLayout()->_setActiveMenu("deleteorder/deleteorder");
        $this->_addContent($this->getLayout()->createBlock('deleteorder/adminhtml_manualdelete'))
                ->_addLeft($this->getLayout()->createBlock('deleteorder/adminhtml_deleteorder_edit_tabs'));
        $this->renderLayout();
    }

    /**
     * to delete orders by date and Increment Id
     */
    public function manualDeleteAction() {
        $currentUser = Mage::helper('deleteorder')->getCurrentUser();
        $defaultAdmin = Mage::helper('deleteorder')->getDefaultAdmin();
        $allowedUsers = Mage::helper('deleteorder')->getAllowedUsers();
        $collection = array();
        if ($defaultAdmin == '' || $currentUser == $defaultAdmin || in_array($currentUser, $allowedUsers) && Mage::helper('deleteorder')->isEnabled()) {
            try {
                $data = $this->getRequest()->getPost();
                Mage::getSingleton('core/session')->setKey($data['delete_by']);
                $ids = array();
                if (array_key_exists('from_date', $data) && array_key_exists('to_date', $data) && $data['from_date'] != '' && $data['to_date'] != '') {
                    $fromDate = date('Y-m-d', strtotime($data['from_date']));
                    $toDate = date('Y-m-d', strtotime("+1 day", strtotime($data['to_date'])));
                    $collection = Mage::getModel('sales/order')->getCollection()
                            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate));
                    if (empty($collection->getData())) {
                        Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("Records not found between " . date('Y-m-d', strtotime($data['from_date'])) . " to " . date('Y-m-d', strtotime($data['to_date']))));
                        $this->_redirect("admin_deleteorder/adminhtml_deleteorder/manualDeleteView");
                    } else {
                        foreach ($collection->getData() as $key => $value) {
                            $ids[] = $value['entity_id'];
                        }
                        $this->massRemoveAction($ids);
                        $this->_redirect("admin_deleteorder/adminhtml_deleteorder/manualDeleteView");
                    }
                } else {
                    if ($this->orderValidate($data)) {
                        $collection = Mage::getModel('sales/order')->getCollection();
                        $collection->addAttributeToSelect('entity_id')
                                ->addAttributeToFilter('increment_id', array(
                                    'from' => $data['starting_id'],
                                    'to' => $data['ending_id']
                        ));
                        foreach ($collection->getData() as $key => $value) {
                            $ids[] = $value['entity_id'];
                        }
                        $this->massRemoveAction($ids);
                        $this->_redirect("admin_deleteorder/adminhtml_deleteorder/manualDeleteView");
                    }
                }
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError("Oops...!!! Something went wrong. Please try again");
                Mage::log($e->getMessage());
                $this->_redirect("admin_deleteorder/adminhtml_deleteorder/manualDeleteView");
            }
        }
    }

    /**
     * check if order exist or not.
     * @param array $data
     * @return boolean
     */
    public function orderValidate($data) {
        $collection1 = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('increment_id', $data['starting_id'])
                ->getData();
        $collection2 = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('increment_id', $data['ending_id'])
                ->getData();

        if (empty($collection1)) {
            Mage::getSingleton('core/session')->addError(Mage::helper('deleteorder')->__('Starting Increment ID Doesn\'t exist. Please enter valid Order Increment ID'));
            $this->_redirectReferer();
            return false;
        } elseif (empty($collection2)) {
            Mage::getSingleton('core/session')->addError(Mage::helper('deleteorder')->__('Ending Increment ID Doesn\'t exist. Please enter valid Order Increment ID'));
            $this->_redirectReferer();
            return false;
        } elseif (!empty($collection1) && !empty($collection2)) {
            if ($data['starting_id'] > $data['ending_id']) {
                Mage::getSingleton('core/session')->addError(Mage::helper('deleteorder')->__('Starting Order Increment ID is greater than Ending Order Increment ID'));
                $this->_redirectReferer();
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * to delete existing order
     */
    public function deleteOrderAction() {
        $currentUser = Mage::helper('deleteorder')->getCurrentUser();
        $defaultAdmin = Mage::helper('deleteorder')->getDefaultAdmin();
        $allowedUsers = Mage::helper('deleteorder')->getAllowedUsers();

        if ($defaultAdmin == '' || $currentUser == $defaultAdmin || in_array($currentUser, $allowedUsers) && Mage::helper('deleteorder')->isEnabled()) {
            $orderId = $this->getRequest()->getParam("order_id");
            if ($orderId > 0) {
                try {
                    $model = Mage::getModel('sales/order');
                    $model1 = Mage::getModel('sales/order')->load($orderId);
                    if (!empty($model1->getData())) {
                        if (Mage::helper('deleteorder')->isHistoryEnabled()) {
                            Mage::getModel('deleteorder/deleteorder')->insertOrderData($orderId);
                            Mage::getModel('deleteorder/deleteorderitem')->insertOrderItemData($orderId);
                            Mage::getModel('deleteorder/deleteorderaddress')->insertOrderAddressData($orderId);
                        }
                        $model->setId($orderId)->delete();
                        Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Order was successfully deleted"));
                        $this->_redirect("adminhtml/sales_order/index");
                    } else {
                        Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("This order no longer exists."));
                        $this->_redirect("adminhtml/sales_order/index");
                    }
                } catch (Exception $e) {
                    Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("Oops...!!! Something went wrong. Please try again"));
                    Mage::log($e->getMessage());
                    $this->_redirect("adminhtml/sales_order/index");
                }
            }
            $this->_redirect("adminhtml/sales_order/index");
        }
    }

    /**
     * to delete multiple orders
     * @param array $_ids
     */
    public function massRemoveAction($_ids = null) {
        $currentUser = Mage::helper('deleteorder')->getCurrentUser();
        $defaultAdmin = Mage::helper('deleteorder')->getDefaultAdmin();
        $allowedUsers = Mage::helper('deleteorder')->getAllowedUsers();

        if ($defaultAdmin == '' || $currentUser == $defaultAdmin || in_array($currentUser, $allowedUsers) && Mage::helper('deleteorder')->isEnabled()) {
            $ids = '';
            try {
                if ($_ids != null) {
                    $ids = $_ids;
                } else {
                    $ids = $this->getRequest()->getPost('order_ids', array());
                }
                foreach ($ids as $id) {
                    $model = Mage::getModel('sales/order');
                    if (Mage::helper('deleteorder')->isHistoryEnabled()) {
                        Mage::getModel('deleteorder/deleteorder')->insertOrderData($id);
                        Mage::getModel('deleteorder/deleteorderitem')->insertOrderItemData($id);
                        Mage::getModel('deleteorder/deleteorderaddress')->insertOrderAddressData($id);
                    }
                    $model->setId($id)->delete();
                }
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Order(s) was successfully removed"));
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("Oops...!!! Something went wrong. Please try again"));
                Mage::log($e->getMessage());
                if ($_ids != null) {
                    $this->_redirect('admin_deleteorder/adminhtml_deleteorder/index');
                } else {
                    $this->_redirect('adminhtml/sales_order/index');
                }
            }
            $this->_redirect('adminhtml/sales_order/index');
        }
    }

    /**
     * delete multiple orders from delete order history
     */
    public function massRemovePermanentAction() {
        try {
            $ids = $this->getRequest()->getPost('ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel('deleteorder/deleteorder');
                $data = $model->getOrderInfo($id);
                $model->setId($id)->delete();
                Mage::getModel('deleteorder/deleteorderitem')->deleteOrderItem($data['entity_id']);
                Mage::getModel('deleteorder/deleteorderaddress')->deleteOrderAddress($data['entity_id']);
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Order(s) was successfully removed from the Order's History"));
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("Oops...!!! Something went wrong. Please try again"));
            Mage::log($e->getMessage());
            $this->_redirect('admin_deleteorder/adminhtml_deleteorder/index');
        }
        $this->_redirect('admin_deleteorder/adminhtml_deleteorder/index');
    }

    /**
     * delete single order from delete order history
     */
    public function singleRemovePermanentAction() {
        $id = $this->getRequest()->getParam("id");
        if ($id > 0) {
            try {
                $model = Mage::getModel('deleteorder/deleteorder');
                $data = $model->getOrderInfo($id);
                if (!empty($data)) {
                    $model->setId($id)->delete();
                    Mage::getModel('deleteorder/deleteorderitem')->deleteOrderItem($data['entity_id']);
                    Mage::getModel('deleteorder/deleteorderaddress')->deleteOrderAddress($data['entity_id']);
                    Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Order was successfully deleted from the Order's History"));
                    $this->_redirect("admin_deleteorder/adminhtml_deleteorder/index");
                }
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("Oops...!!! Something went wrong. Please try again"));
                Mage::log($e->getMessage());
                $this->_redirect('admin_deleteorder/adminhtml_deleteorder/index');
            }
        }
        $this->_redirect('admin_deleteorder/adminhtml_deleteorder/index');
    }

    /**
     * Export order history grid to CSV format
     */
    public function exportCsvAction() {
        $fileName = 'deleted_orders.csv';
        $grid = $this->getLayout()->createBlock('deleteorder/adminhtml_deleteorder_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order history grid to Excel XML format
     */
    public function exportExcelAction() {
        $fileName = 'deleted_orders.xml';
        $grid = $this->getLayout()->createBlock('deleteorder/adminhtml_deleteorder_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

}
