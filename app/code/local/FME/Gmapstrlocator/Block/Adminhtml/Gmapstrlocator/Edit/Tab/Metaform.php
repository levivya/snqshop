<?php

class FME_Gmapstrlocator_Block_Adminhtml_Gmapstrlocator_Edit_Tab_Metaform extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $metaform = new Varien_Data_Form();
      $this->setForm($metaform);
      $fieldset = $metaform->addFieldset('gmapstrlocator_metaform', array('legend'=>Mage::helper('gmapstrlocator')->__('Store Meta information')));
      
      
      $fieldset->addField('meta_title', 'text', array(
          'name'      => 'meta_title',
	  'label'     => Mage::helper('gmapstrlocator')->__('Meta Title'),
	  'title'     => Mage::helper('gmapstrlocator')->__('Meta Title'),
          'class'     => '',
          'required'  => false,          	  
      ));
      
     
      $fieldset->addField('meta_keywords', 'textarea', array(
          'name'      => 'meta_keywords',
	  'label'     => Mage::helper('gmapstrlocator')->__('Meta Keywords'),
	  'title'     => Mage::helper('gmapstrlocator')->__('Meta Keywords'),
          'class'     => '',
          'required'  => false          
      ));
      
      
      $fieldset->addField('meta_contents','textarea',array(
	    'name'      => 'meta_contents',
            'label'     => Mage::helper('gmapstrlocator')->__('Meta Contents'),
            'title'     => Mage::helper('gmapstrlocator')->__('Meta Contents'),
            'class'	=> '',
	    'required'  => false,    
      ));
      
      
     
      if ( Mage::getSingleton('adminhtml/session')->getGmapstrlocatorData() )
      {
          $metaform->setValues(Mage::getSingleton('adminhtml/session')->getGmapstrlocatorData());
          Mage::getSingleton('adminhtml/session')->setGmapstrlocatorData(null);
      } elseif ( Mage::registry('gmapstrlocator_data') ) {
          $metaform->setValues(Mage::registry('gmapstrlocator_data')->getData());
      }
      return parent::_prepareForm();
  }
}