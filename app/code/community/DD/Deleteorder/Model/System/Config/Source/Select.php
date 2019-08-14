<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */

class DD_Deleteorder_Model_System_Config_Source_Select {

    /**
     * get all users
     * @return array
     */
    public function toOptionArray() {
        return Mage::helper('deleteorder')->getAllUsers();
    }

}
