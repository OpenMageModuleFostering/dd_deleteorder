<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Block_Adminhtml_Viewinfo extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        $message = 'Do you want to delete the record permanently?';
        $id = $this->getRequest()->getParam('o_id');
        $url = $this->getUrl('deleteorder/adminhtml_deleteorder/singleRemovePermanent', array('id' => $id));
        parent::__construct();
        $this->_objectId = 'o_id';
        $this->_blockGroup = 'deleteorder';
        $this->_controller = 'adminhtml_viewinfo';
        $this->_addButton('delete_order', array(
            'label' => Mage::helper('deleteorder')->__('Delete'),
            'onclick' => "confirmSetLocation('{$message}', '{$url}')",
            'class' => 'delete'
                ), 0, 100, 'header', 'header');
        $this->_removeButton('save');
        $this->_removeButton('reset');
    }

    /**
     * 
     * @return string
     */
    public function getHeaderText() {
        return Mage::helper('deleteorder')->__('Deleted Order Information');
    }

}
