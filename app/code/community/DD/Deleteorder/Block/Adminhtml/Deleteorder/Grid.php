<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Block_Adminhtml_Deleteorder_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("deleteorderGrid");
        $this->setDefaultSort("order_delete_date");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("deleteorder/deleteorder")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn("increment_id", array(
            "header" => Mage::helper("deleteorder")->__("Order #"),
            'width' => '80px',
            'type' => 'text',
            'index' => 'increment_id',
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header' => Mage::helper('deleteorder')->__('Purchased From (Store)'),
                'index' => 'store_id',
                'type' => 'store',
                'width' => '80px',
                'store_view' => true,
                'display_deleted' => true,
            ));
        }
        $this->addColumn('order_delete_date', array(
            'header' => Mage::helper('deleteorder')->__('Order Deleted Date'),
            'index' => 'order_delete_date',
            'type' => 'datetime',
        ));
        $this->addColumn('created_at', array(
            'header' => Mage::helper('deleteorder')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));
        $this->addColumn('billing_name', array(
            'header' => Mage::helper('deleteorder')->__('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('deleteorder')->__('Ship to Name'),
            'index' => 'shipping_name',
        ));
        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('deleteorder')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type' => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('deleteorder')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type' => 'currency',
            'currency' => 'order_currency_code',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('deleteorder')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));


        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn('action', array(
                'header' => Mage::helper('deleteorder')->__('Action'),
                'width' => '50px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('deleteorder')->__('View'),
                        'url' => array('base' => 'deleteorder/adminhtml_deleteorder/viewInfo'),
                        'field' => 'o_id',
                        'data-column' => 'action',
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));
            $this->addColumn('Delete', array(
                'header' => Mage::helper('deleteorder')->__(''),
                'width' => '50px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('deleteorder')->__('Delete'),
                        'url' => array('base' => 'deleteorder/adminhtml_deleteorder/singleRemovePermanent'),
                        'confirm' => Mage::helper('deleteorder')->__('Do you want to delete the record permanently?'),
                        'field' => 'id',
                        'data-column' => 'action',
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));
        }

        $this->addExportType('*/*/exportCsv', Mage::helper('deleteorder')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('deleteorder')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl("deleteorder/adminhtml_deleteorder/viewInfo", array("o_id" => $row->getId()));
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_deleteorder', array(
            'label' => Mage::helper('deleteorder')->__('Delete Orders Permanently'),
            'url' => $this->getUrl('*/adminhtml_deleteorder/massRemovePermanent'),
            'confirm' => Mage::helper('deleteorder')->__('Do you want to delete the record permanently?')
        ));
        return $this;
    }

}
