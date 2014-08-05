<?php

class FME_Gmapstrlocator_Block_Adminhtml_Attributes_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
     protected function _prepareLayout()
    {
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled() && ($block = $this->getLayout()->getBlock('head'))) {
            $block->setCanLoadTinyMce(true);
        }
		return parent::_prepareLayout();
     }
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'gmapstrlocator';
        $this->_controller = 'adminhtml_attributes';
        
        $this->_updateButton('save', 'label', Mage::helper('gmapstrlocator')->__('Save Attribute'));
        $this->_updateButton('delete', 'label', Mage::helper('gmapstrlocator')->__('Delete Attribute'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
               function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('gmapstrlocator_data') && Mage::registry('gmapstrlocator_data')->getId() ) {
            return Mage::helper('gmapstrlocator')->__("Edit Attribute '%s'", $this->htmlEscape(Mage::registry('gmapstrlocator_data')->getStoreName()));
        } else {
            return Mage::helper('gmapstrlocator')->__('Add Attribute');
        }
    }
}