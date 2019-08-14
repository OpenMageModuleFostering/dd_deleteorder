<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid {

    protected function _prepareColumns() {

        $this->addColumn('real_order_id', array(
            'header' => Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type' => 'text',
            'index' => 'increment_id',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header' => Mage::helper('sales')->__('Purchased From (Store)'),
                'index' => 'store_id',
                'type' => 'store',
                'store_view' => true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
        ));

        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type' => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type' => 'currency',
            'currency' => 'order_currency_code',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn('action', array(
                'header' => Mage::helper('sales')->__('Action'),
                'width' => '50px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('sales')->__('View'),
                        'url' => array('base' => '*/sales_order/view'),
                        'field' => 'order_id',
                        'data-column' => 'action',
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));
            $currentUser = Mage::helper('deleteorder')->getCurrentUser();
            $defaultAdmin = Mage::helper('deleteorder')->getDefaultAdmin();
            $allowedUsers = Mage::helper('deleteorder')->getAllowedUsers();

            if ($defaultAdmin == '' || in_array($currentUser, $allowedUsers) || $currentUser == $defaultAdmin) {
                if (Mage::helper('deleteorder')->isEnabled() && Mage::helper('deleteorder')->isGridDeleteEnabled()) {
                    $this->addColumn('Delete', array(
                        'header' => Mage::helper('sales')->__(''),
                        'width' => '50px',
                        'type' => 'action',
                        'getter' => 'getId',
                        'actions' => array(
                            array(
                                'caption' => Mage::helper('sales')->__('Delete'),
                                'url' => array('base' => 'deleteorder/adminhtml_deleteorder/deleteOrder'),
                                'confirm' => Mage::helper('deleteorder')->__('Are you sure?'),
                                'field' => 'order_id',
                                'data-column' => 'action',
                            )
                        ),
                        'filter' => false,
                        'sortable' => false,
                        'index' => 'stores',
                        'is_system' => true,
                    ));
                }
            }
        }

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        parent::_prepareMassaction();
        $currentUser = Mage::helper('deleteorder')->getCurrentUser();
        $defaultAdmin = Mage::helper('deleteorder')->getDefaultAdmin();
        $allowedUsers = Mage::helper('deleteorder')->getAllowedUsers();
        if ($defaultAdmin == '' || in_array($currentUser, $allowedUsers) || $currentUser == $defaultAdmin) {
            if (Mage::helper('deleteorder')->isEnabled() && Mage::helper('deleteorder')->isGridDeleteEnabled()) {
                $this->setMassactionIdField('order_id');
                $this->getMassactionBlock()->setFormFieldName('order_ids');
                $this->getMassactionBlock()->setUseSelectAll(true);
                $this->getMassactionBlock()->addItem('deleteorder', array(
                    'label' => Mage::helper('sales')->__('Delete orders'),
                    'url' => $this->getUrl('deleteorder/adminhtml_deleteorder/massRemove'),
                    'confirm' => Mage::helper('deleteorder')->__('Are you sure?'),
                ));
            }
        }
    }

}
