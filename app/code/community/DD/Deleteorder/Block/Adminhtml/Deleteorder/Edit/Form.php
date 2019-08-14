<?php

/**
 * Dynamic Dreamz
 * @category   DD
 * @package    DD_Deleteorder
 * @copyright  Copyright (c) 2014-2015 Dynamic Dreamz. (http://www.dynamicdreamz.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL)
 */
class DD_Deleteorder_Block_Adminhtml_Deleteorder_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * generate manual delete form
     */
    protected function _prepareForm() {

        $_key = '';
        try {
            if (Mage::getSingleton('core/session')->getKey() != null) {
                $_key = Mage::getSingleton('core/session')->getKey();
            } else {
                $_key = '';
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('admin_deleteorder/adminhtml_deleteorder/manualDelete', array()),
            'method' => 'post',
        ));
        $fieldset = $form->addFieldset('manualdelete', array('legend' => Mage::helper('deleteorder')->__('Delete Orders By Date and Increment Id')));

        if ($_key != '') {
            $field = $fieldset->addField('delete_by', 'select', array(
                'name' => 'delete_by',
                'label' => Mage::helper('deleteorder')->__('Delete By'),
                'required' => true,
                'onchange' => "",
                'value' => $_key,
                'values' => Mage::helper('deleteorder')->getDeleteBy(),
            ));
            Mage::getSingleton('core/session')->unsKey();
        } else {
            $field = $fieldset->addField('delete_by', 'select', array(
                'name' => 'delete_by',
                'label' => Mage::helper('deleteorder')->__('Delete By'),
                'required' => true,
                'onchange' => "",
                'value' => '',
                'values' => Mage::helper('deleteorder')->getDeleteBy(),
            ));
        }
        $fieldset->addField('starting_id', 'text', array(
            'name' => 'starting_id',
            'label' => Mage::helper('deleteorder')->__('Starting Order Increment ID'),
            'required' => true,
            'after_element_html' => '<small>Order Increment Id. eg. 145000035 </small>',
            'class' => 'validate-digits',
        ));
        $fieldset->addField('ending_id', 'text', array(
            'name' => 'ending_id',
            'label' => Mage::helper('deleteorder')->__('Ending Order Increment ID'),
            'required' => true,
            'after_element_html' => '<small>Order Increment Id. eg. 145000040 </small>',
            'class' => 'validate-digits',
            'note' => '<span style="color:red;font-size:13px;"><b><i>Note : </i></b></span>Order(s) will be deleted including "Starting and Ending Increment IDs."',
        ));
        $fieldset->addField('from_date', 'date', array(
            'name' => 'from_date',
            'class' => 'dates',
            'required' => true,
            'label' => $this->__('From Date'),
            'after_element_html' => '<small>Date Format eg : Oct 8, 2015</small>',
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
            'tabindex' => 2
        ));
        $fieldset->addField('to_date', 'date', array(
            'name' => 'to_date',
            'class' => 'dates',
            'required' => true,
            'label' => $this->__('To Date'),
            'after_element_html' => '<small>Date Format eg : Oct 10, 2015</small>',
            'note' => '<span style="color:red;font-size:13px;"><b><i>Note : </i></b></span>Order(s) will be deleted including "From Date" and "To Date".',
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
            'tabindex' => 2
        ));
        $field = $fieldset->addField('submit', 'submit', array(
            'type' => 'submit',
            'class' => 'form-button',
            'value' => 'Delete',
            'tabindex' => 1,
        ));

        if (Mage::getSingleton('adminhtml/session')->getDeleteorderData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getDeleteorderData());
            Mage::getSingleton('adminhtml/session')->setDeleteorderData(null);
        } elseif (Mage::registry('deleteorder_data')) {
            $form->setValues(Mage::registry('deleteorder_data')->getData());
        }
        $form->setUseContainer(true);
        $this->setForm($form);
        $this->setChild
                (
                'form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                        ->addFieldMap('delete_by', 'delete_by_id')
                        ->addFieldMap('starting_id', 'starting_id_id')
                        ->addFieldMap('ending_id', 'ending_id_id')
                        ->addFieldMap('from_date', 'from_date_id')
                        ->addFieldMap('to_date', 'to_date_id')
                        ->addFieldDependence('from_date_id', 'delete_by_id', '1')
                        ->addFieldDependence('to_date_id', 'delete_by_id', '1')
                        ->addFieldDependence('starting_id_id', 'delete_by_id', '2')
                        ->addFieldDependence('ending_id_id', 'delete_by_id', '2')
        );

        return parent::_prepareForm();
    }

}
