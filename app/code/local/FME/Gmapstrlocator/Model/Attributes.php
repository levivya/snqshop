<?php

class FME_Gmapstrlocator_Model_Attributes extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('gmapstrlocator/attributes');
    }
    
    
    public function loadAllAttributesForSelect(){
        
        $collection = $this->getCollection()
            ->addFieldToFilter('attributes_status',1);
        
        $attr_array = array();
        
        
        foreach ($collection as $attr) {
            
            $attr_array[] = array(
                                        'label' => $attr->getAttributesName(),
                                        'value' => $attr->getAttributesId()
                                    
            );
        }
            
        return $attr_array;
     
    }
    
}

