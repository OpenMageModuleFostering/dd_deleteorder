<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Model_Mysql4_Deleteorderitem extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init("deleteorder/deleteorderitem", "id");
    }

}
