<?php
class FME_Gmapstrlocator_Block_Gmapstrlocator extends Mage_Core_Block_Template
{
	
    public function _prepareLayout(){
	
        
        if ($head = $this->getLayout()->getBlock('head')) {
            $head->setTitle(Mage::helper('gmapstrlocator')->getGMapPageTitle());
            $head->setDescription(Mage::helper('gmapstrlocator')->getGMapMetadescription());
            $head->setKeywords(Mage::helper('gmapstrlocator')->getGMapMetaKeywords());
        }
        
        return parent::_prepareLayout();
        
    }
    
    public function getGmapstrlocator(){
	
        if (!$this->hasData('gmapstrlocator')) {
            $this->setData('gmapstrlocator', Mage::registry('gmapstrlocator'));
        }
        return $this->getData('gmapstrlocator');
        
    }
    
    
    public function getAllStores(){
	
	$web_store = Mage::app()->getStore()->getStoreId();
	
	$collection = Mage::getModel('gmapstrlocator/gmapstrlocator')->getCollection()
									->addStoreFilter($web_store)
									->addFieldToFilter('main_table.status',1)
									->addOrder('main_table.created_time','DESC');
	
	
	
	return $collection->getData();
	
    }
    
    
    
    
    public function getAllAttributes(){
	
	$web_store = Mage::app()->getStore()->getStoreId();	
	$collection = Mage::getModel('gmapstrlocator/attributes')->getCollection()									
									->addFieldToFilter('main_table.attributes_status',1)
									->addOrder('main_table.created_time','DESC');
	
	
	return $collection->getData();
	
    }
    
    
    
    
    public function getProductListAuto(){
	    
	    $store_id = Mage::app()->getStore()->getId();
	    
	    $collection = Mage::getResourceModel('catalog/product_collection')->setStoreId($store_id);  
	    Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
	    Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
	    
           if (!isset($query_string)) $query_string="";
   
	    $collection->addAttributeToFilter(
                array(
                    array('attribute'=>'name', 'like'=>'%'.$query_string.'%'),
                    array('attribute'=>'sku', 'like'=>'%'.$query_string.'%')
                )
            );
	    
	    
	    $prod_names = array();	    
	    
	    foreach($collection as $prod){
		
		$prod_names[] = $prod->getName();
		
	    }
	    
	    
	    return $prod_names;    
        
    }
    
    
    
    
    
    
    
    
    
}