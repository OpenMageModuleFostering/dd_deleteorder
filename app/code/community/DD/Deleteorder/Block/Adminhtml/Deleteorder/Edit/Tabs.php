<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */

class DD_Deleteorder_Block_Adminhtml_Deleteorder_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId("deleteorder_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("deleteorder")->__("Manual Order Delete"));
    }

    protected function _beforeToHtml() {
        $this->addTab("form_section", array(
            "label" => Mage::helper("deleteorder")->__("Manual Order Delete"),
            "title" => Mage::helper("deleteorder")->__("Manual Order Delete"),
        ));
        return parent::_beforeToHtml();
    }

}
