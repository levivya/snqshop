<?php
class FME_Gmapstrlocator_Block_Detail extends Mage_Core_Block_Template
{
	
    public function _prepareLayout(){
	
        if($id = $this->getRequest()->getParam('id')){
		    
		    $store_model = Mage::getModel('gmapstrlocator/gmapstrlocator');
		    
		    $store_data = $store_model->load($id);
		    $store_data = $store_data->getData();
		    
		    if ($head = $this->getLayout()->getBlock('head')) {
			$store_data['meta_title'] ? $head->setTitle($store_data['meta_title']) : $head->setTitle($this->helper('gmapstrlocator')->getGMapPageTitle());
			$store_data['meta_contents'] ? $head->setDescription($store_data['meta_contents']) : $head->setDescription($this->helper('gmapstrlocator')->getGMapMetadescription());
			$store_data['meta_keywords'] ? $head->setKeywords($store_data['meta_keywords']) : $head->setKeywords($this->helper('gmapstrlocator')->getGMapMetaKeywords());
		    }
		    
	}
        
        
        return parent::_prepareLayout();
        
    }
    
    
    
    public function getProductsOfCurrentStore($g_store_id){
	
	
	$read_connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	$store_prod_table = Mage::getSingleton('core/resource')->getTableName('gmapstrlocator/gmapstrlocator_products');
	
	$select = $read_connection->select()->from($store_prod_table)
					    ->where('gmapstrlocator_id = '.$g_store_id);
		
	$resulting_data = $read_connection->fetchAll($select);
	
	return $resulting_data;
    }
    
    
    
    
    
    
    
    
    
    
    
}