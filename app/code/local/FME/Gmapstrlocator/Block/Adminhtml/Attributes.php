<?php
class FME_Gmapstrlocator_Block_Adminhtml_Attributes extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_attributes';
    $this->_blockGroup = 'gmapstrlocator';
    $this->_headerText = Mage::helper('gmapstrlocator')->__('Store Attributes Manager');
    $this->_addButtonLabel = Mage::helper('gmapstrlocator')->__('Add New Attribute');
    parent::__construct();
  }
}