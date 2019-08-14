<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Block_Adminhtml_Manualdelete extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'deleteorder';
        $this->_controller = 'adminhtml_deleteorder';
        $this->_removeButton('delete');
        $this->_removeButton('save');
        $this->_removeButton('reset');
    }

    /**
     * 
     * @return string
     */
    public function getHeaderText() {
        return Mage::helper('deleteorder')->__('Manual Order Delete');
    }

}
