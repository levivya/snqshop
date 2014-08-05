<?php

class FME_Gmapstrlocator_Model_Gmapstrlocator extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('gmapstrlocator/gmapstrlocator');
    }
    
    public function loadAllProductsForSelect(){
        
        $storeId = Mage::app()->getStore()->getId();
         
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addStoreFilter($storeId)
            ->addAttributeToSelect('*');
        
        $prodct_array = array();
        
        
        foreach ($collection as $product) {
            
            $prodct_array[] = array(
                                        'label' => $product->getName(),
                                        'value' => $product->getId()
                                    
                                    );
        }
            
        return $prodct_array;
     
    }
    
    
    
    public function getRelatedProductGmaps($faqId)
    {
	
        $prod_table = Mage::getSingleton('core/resource')->getTableName('gmapstrlocator_products'); 
        
	$collection = Mage::getModel('gmapstrlocator/gmapstrlocator')->getCollection()
				  ->addGmapFilter($faqId);
					  
	$collection->getSelect()
                        ->joinLeft(array('related' => $prod_table),
                                    'main_table.gmapstrlocator_id = related.gmapstrlocator_id'
                            )
			->order('main_table.created_time DESC');
			
                        
	return $collection->getData();

    }
    
}

