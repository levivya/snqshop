<?php

class FME_Gmapstrlocator_Model_Mysql4_Attributes extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the gmapstrlocator_id refers to the key field in your database table.
        $this->_init('gmapstrlocator/attributes', 'attributes_id');
    }
    
    
    
    
    
    
    
}