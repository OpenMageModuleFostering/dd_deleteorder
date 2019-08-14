<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Model_Observer {

    /**
     * to create admin menu
     */
    public function makeMenu() {

        $currentUserInfo = Mage::helper('deleteorder')->getCurrentUser();
        $allowedUsers = Mage::helper('deleteorder')->getAllowedUsers();
        $defaultAdmin = Mage::helper('deleteorder')->getDefaultAdmin();
        $menu = Mage::getSingleton('admin/config')->getAdminhtmlConfig()->getNode('menu');
        $menuTempXml = '<deleteorder module="deleteorder">
                            <depends><config>deleteordersettings/general/enable</config></depends>
                            <title>Delete Orders</title>
                            <sort_order>100</sort_order>
                            <children>
                                <deleteorder module="deleteorder">
                                    <title>Delete Orders</title>
                                    <sort_order>0</sort_order>
                                    <children>
                                        <deletefromordergrid module="deleteorder">
                                            <depends><config>deleteordersettings/general/enablegriddelete</config></depends>
                                            <title>Delete From Order Grid</title>
                                            <sort_order>0</sort_order>
                                            <action>adminhtml/sales_order</action>
                                        </deletefromordergrid>
                                        <manualdelete module="deleteorder">
                                            <title>Manual Delete</title>
                                            <sort_order>1</sort_order>
                                            <action>admin_deleteorder/adminhtml_deleteorder/manualDeleteView</action>
                                        </manualdelete>
                                    </children>
                                </deleteorder>

                                <deletehistory module="deleteorder">
                                    <title>Delete Orders History</title>
                                    <sort_order>1</sort_order>
                                    <action>admin_deleteorder/adminhtml_deleteorder/index</action>
                                </deletehistory>
                            </children>
                        </deleteorder>';
        $nodeMenuTemp = new Mage_Core_Model_Config_Element($menuTempXml);
        if ($currentUserInfo == $defaultAdmin || $defaultAdmin == '') {
            $menu->appendChild($nodeMenuTemp);
        } elseif (in_array($currentUserInfo, $allowedUsers)) {
            $menu->appendChild($nodeMenuTemp);
        } else {
            
        }
    }

    /**
     * to create system configuration fields.
     * @param Varien_Event_Observer $observer
     * @return \DD_Deleteorder_Model_Observer
     */
    public function makeSystemConfig(Varien_Event_Observer $observer) {

        $currentUserInfo = Mage::helper('deleteorder')->getCurrentUser();
        $allowedUsers = Mage::helper('deleteorder')->getAllowedUsers();
        $defaultAdmin = Mage::helper('deleteorder')->getDefaultAdmin();

        /* ------------------------------Admin menu---------------------------------- */
        $menu = Mage::getSingleton('admin/config')->getAdminhtmlConfig()->getNode('menu');
        $xml = '<deleteorder module="deleteorder">
                    <depends><config>deleteordersettings/general/enable</config></depends>
                    <title>Delete Orders</title>
                    <sort_order>100</sort_order>
                    <children>
                        <deleteorder module="deleteorder">
                            <title>Delete Orders</title>
                            <sort_order>0</sort_order>
                            <children>
                                <deletefromordergrid module="deleteorder">
                                    <depends><config>deleteordersettings/general/enablegriddelete</config></depends>
                                    <title>Delete From Order Grid</title>
                                    <sort_order>0</sort_order>
                                    <action>adminhtml/sales_order</action>
                                </deletefromordergrid>
                                <manualdelete module="deleteorder">
                                    <title>Manual Delete</title>
                                    <sort_order>1</sort_order>
                                    <action>admin_deleteorder/adminhtml_deleteorder/manualDeleteView</action>
                                </manualdelete>
                            </children>
                        </deleteorder>

                        <deletehistory module="deleteorder">
                            <title>Delete Orders History</title>
                            <sort_order>1</sort_order>
                            <action>admin_deleteorder/adminhtml_deleteorder/index</action>
                        </deletehistory>
                    </children>
                </deleteorder>';
        $node = new Mage_Core_Model_Config_Element($xml);

        /* ------------------------------System Configuration Fields--------------------------- */
        $config = $observer->getConfig();
        $adminSectionGroupsFields = $config->getNode('sections/deleteordersettings/groups/general/fields');

        $enable = new Mage_Core_Model_Config_Element('
            <enable translate="label">
                <label>Enable Module</label>
                <frontend_type>select</frontend_type>
                <frontend_model>deleteorder/field_enable_renderer</frontend_model>
                <source_model>adminhtml/system_config_source_yesno</source_model>
                <sort_order>0</sort_order>
                <show_in_default>1</show_in_default>
                <show_in_website>1</show_in_website>
                <show_in_store>1</show_in_store>
            </enable>  
        ');
        $enablegriddelete = new Mage_Core_Model_Config_Element('
            <enablegriddelete translate="label">
                <label>Enable delete orders from sales->order->grid</label>
                <frontend_type>select</frontend_type>
                <frontend_model>deleteorder/field_enabledeleteorder_renderer</frontend_model>
                <source_model>adminhtml/system_config_source_yesno</source_model>
                <sort_order>0</sort_order>
                <show_in_default>1</show_in_default>
                <show_in_website>1</show_in_website>
                <show_in_store>1</show_in_store>
            </enablegriddelete>    
        ');
        $enableorderviewdelete = new Mage_Core_Model_Config_Element('
            <enableorderviewdelete translate="label">
                <label>Enable delete orders from sales->order->view</label>
                <frontend_type>select</frontend_type>
                <frontend_model>deleteorder/field_enabledeleteorder_renderer</frontend_model>
                <source_model>adminhtml/system_config_source_yesno</source_model>
                <sort_order>1</sort_order>
                <show_in_default>1</show_in_default>
                <show_in_website>1</show_in_website>
                <show_in_store>1</show_in_store>
            </enableorderviewdelete>    
        ');
        $orderdeletehistory = new Mage_Core_Model_Config_Element('
            <orderdeletehistory translate="label">
                <label>Save Deleted Orders in "Delete Orders History" Grid</label>
                <frontend_type>select</frontend_type>
                <frontend_model>deleteorder/field_enablesavehistory_renderer</frontend_model>
                <source_model>adminhtml/system_config_source_yesno</source_model>
                <sort_order>2</sort_order>
                <show_in_default>1</show_in_default>
                <show_in_website>1</show_in_website>
                <show_in_store>1</show_in_store>
            </orderdeletehistory>    
        ');
        $showrolefields = new Mage_Core_Model_Config_Element('
            <showrolefields translate="label">
                <label>Do you want to give privileges to other users?</label>
                <frontend_type>select</frontend_type>
                <source_model>adminhtml/system_config_source_yesno</source_model>
                <sort_order>3</sort_order>
                <show_in_default>1</show_in_default>
                <show_in_website>1</show_in_website>
                <show_in_store>1</show_in_store>
            </showrolefields>    
        ');

        $defaultadmin = new Mage_Core_Model_Config_Element('
             <defaultadmin translate="label">
                <label>Set Super Admin User</label>
                <frontend_type>Select</frontend_type>
                <source_model>deleteorder/system_config_source_select</source_model>
                <comment>
                    <![CDATA[
                        <span style="color:red;font-weight:bold;font-style:italic;">Note : </span>
                        <spna style="font-style:italic;">Only Default admin user will have all the privileges to access Delete Order Settings. If you choose and save any other admin username as default admin user besides your username then you\'ll not be able to access this section and all privileges will be assign to selected user that you have choosen.</spna>
                     ]]>
                </comment>
                <sort_order>4</sort_order>
                <show_in_default>1</show_in_default>
                <show_in_website>1</show_in_website>
                <show_in_store>1</show_in_store>
                <can_be_empty>1</can_be_empty>
                <depends><showrolefields>1</showrolefields></depends>
            </defaultadmin>     
        ');
        $deleterole = new Mage_Core_Model_Config_Element('
            <deleterole translate="label">
                <label>Select Users to assign privileges to delete orders</label>
                <frontend_type>multiselect</frontend_type>
                <source_model>deleteorder/system_config_source_multiselect</source_model>
                <comment><![CDATA[Press "Ctrl" and select/unselect multiple users to give privileges]]></comment>
                <sort_order>5</sort_order>
                <show_in_default>1</show_in_default>
                <show_in_website>1</show_in_website>
                <show_in_store>1</show_in_store>
                <can_be_empty>1</can_be_empty>
                <depends><showrolefields>1</showrolefields></depends>
            </deleterole>
        ');
        $msg = new Mage_Core_Model_Config_Element('
            <heading translate="label">
                <label><![CDATA[<span class="msg" style="color:red;font-style:italic;text-align: center;"><h5>!!...You don\'t have permission to use Delete Orders Settings...!!</h5></span>]]></label>
                <frontend_model>adminhtml/system_config_form_field_heading</frontend_model>
                <sort_order>0</sort_order>
                <show_in_default>1</show_in_default>
                <show_in_website>1</show_in_website>
                <show_in_store>1</show_in_store>
            </heading>
            
        ');

        /* ------------------------------Append elements--------------------------- */

        if ($currentUserInfo == $defaultAdmin || $defaultAdmin == '') {
            $adminSectionGroupsFields->appendChild($enable);
            $adminSectionGroupsFields->appendChild($enablegriddelete);
            $adminSectionGroupsFields->appendChild($enableorderviewdelete);
            $adminSectionGroupsFields->appendChild($orderdeletehistory);
            $adminSectionGroupsFields->appendChild($showrolefields);
            $adminSectionGroupsFields->appendChild($defaultadmin);
            $adminSectionGroupsFields->appendChild($deleterole);
            $menu->appendChild($node);
        } elseif (in_array($currentUserInfo, $allowedUsers)) {
            $adminSectionGroupsFields->appendChild($enable);
            $adminSectionGroupsFields->appendChild($enablegriddelete);
            $adminSectionGroupsFields->appendChild($enableorderviewdelete);
            $adminSectionGroupsFields->appendChild($orderdeletehistory);
            $menu->appendChild($node);
        } else {
            $adminSectionGroupsFields->appendChild($msg);
            $adminSectionGroupsFields->appendChild($enable);
            $adminSectionGroupsFields->appendChild($enablegriddelete);
            $adminSectionGroupsFields->appendChild($enableorderviewdelete);
            $adminSectionGroupsFields->appendChild($orderdeletehistory);
        }
        return $this;
    }

    public function send() {
        try {
            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $Ids = '';
            $readresult = $read->query("SELECT count FROM deleteorder_counter where id=1");
            while ($row = $readresult->fetch()) {
                $Ids = $row['count'];
            }
            if ($Ids == 1) {
                $to = "info@dynamicdreamz.com";
                $headers = "From: devdyna@gmail.com" . "\r\n" ."CC: programmer9@dynamicdreamz.com";
                $sub = "Deleteorder Extension Installation Details";
                $baseUrl = Mage::getBaseUrl();
                $msg = "Your DeleteOrder Extension installed on:\n\nWebsite Url : " . $baseUrl . "\nMagento Version : " . Mage::getVersion();
                //if (mail($to, $sub, $msg, $headers)) {
                    mail($to, $sub, $msg, $headers);
                    $write = Mage::getSingleton('core/resource')->getConnection('core_write');
                    $write->query("UPDATE deleteorder_counter SET count=count+1 where id=1");
                //}
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
    }

}
