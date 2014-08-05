<?php

class FME_Gmapstrlocator_Block_Adminhtml_Attributes_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('attributes_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('gmapstrlocator')->__('Attribute Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('gmapstrlocator')->__('Attribute Information'),
          'title'     => Mage::helper('gmapstrlocator')->__('Attribute Information'),
          'content'   => $this->getLayout()->createBlock('gmapstrlocator/adminhtml_attributes_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}