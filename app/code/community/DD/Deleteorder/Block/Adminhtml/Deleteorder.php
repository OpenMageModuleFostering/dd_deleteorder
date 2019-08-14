<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Block_Adminhtml_Deleteorder extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {

        $this->_controller = "adminhtml_deleteorder";
        $this->_blockGroup = "deleteorder";
        $this->_headerText = Mage::helper("deleteorder")->__("Deleteorder History Manager");
        parent::__construct();
        $this->_removeButton('add');
    }

}
