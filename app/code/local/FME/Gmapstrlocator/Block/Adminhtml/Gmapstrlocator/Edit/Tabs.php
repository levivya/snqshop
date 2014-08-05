<?php

class FME_Gmapstrlocator_Block_Adminhtml_Gmapstrlocator_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('gmapstrlocator_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('gmapstrlocator')->__('Store Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('gmapstrlocator')->__('Store Information'),
          'title'     => Mage::helper('gmapstrlocator')->__('Store Information'),
          'content'   => $this->getLayout()->createBlock('gmapstrlocator/adminhtml_gmapstrlocator_edit_tab_form')->toHtml(),
      ));
      
      
      $this->addTab('meta_section',array(
          'label'   => Mage::helper('gmapstrlocator')->__('Meta Information'),
          'title'   => Mage::helper('gmapstrlocator')->__('Meta Information'),
          'content' => $this->getLayout()->createBlock('gmapstrlocator/adminhtml_gmapstrlocator_edit_tab_metaform')->toHtml(),          
      ));
      
      
      $this->addTab('products_section', array(
          'label'     => Mage::helper('gmapstrlocator')->__('Store Products'),
          'title'     => Mage::helper('gmapstrlocator')->__('Store Products'),
          'url'       => $this->getUrl('*/*/products', array('_current' => true)),
          'class'     => 'ajax',
      ));
     
      return parent::_beforeToHtml();
  }
  
  
}