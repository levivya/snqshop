<?php

class FME_Gmapstrlocator_Block_Adminhtml_Attributes_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('attributes_form', array('legend'=>Mage::helper('gmapstrlocator')->__('Attributes information')));
     
      $fieldset->addField('attributes_name', 'text', array(
          'label'     => Mage::helper('gmapstrlocator')->__('Attribute Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'attributes_name',
      ));
      
      	
      $fieldset->addField('attributes_status', 'select', array(
          'label'     => Mage::helper('gmapstrlocator')->__('Attribute Status'),
          'name'      => 'attributes_status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('gmapstrlocator')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('gmapstrlocator')->__('Disabled'),
              ),
          ),
      ));
     
     
      
      
      
     
      if ( Mage::getSingleton('adminhtml/session')->getGmapstrlocatorData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getGmapstrlocatorData());
          Mage::getSingleton('adminhtml/session')->setGmapstrlocatorData(null);
      } elseif ( Mage::registry('gmapstrlocator_data') ) {
          $form->setValues(Mage::registry('gmapstrlocator_data')->getData());
      }
      return parent::_prepareForm();
  }
}