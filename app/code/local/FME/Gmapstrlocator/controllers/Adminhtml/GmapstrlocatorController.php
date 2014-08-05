<?php

class FME_Gmapstrlocator_Adminhtml_GmapstrlocatorController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('gmapstrlocator/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Store Locator Manager'), Mage::helper('adminhtml')->__('Store Locator Manager'));
		$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('gmapstrlocator/gmapstrlocator')->load($id);

		if ($model->getId() || $id == 0) {
			
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('gmapstrlocator_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('gmapstrlocator/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Store Locator Manager'), Mage::helper('adminhtml')->__('Store Locator Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Store Locator Manager'), Mage::helper('adminhtml')->__('Store Locator Manager'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			//$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

			$this->_addContent($this->getLayout()->createBlock('gmapstrlocator/adminhtml_gmapstrlocator_edit'))
				->_addLeft($this->getLayout()->createBlock('gmapstrlocator/adminhtml_gmapstrlocator_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gmapstrlocator')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			
			//echo '<pre>'; print_r($data); exit;
			
			if($data['linked_attributes']){
				$data['linked_attributes'] = implode(',',$data['linked_attributes']);			
			}
			
			
			if($data['store_identifier']){ 
				
				$colec = Mage::getModel('gmapstrlocator/gmapstrlocator')->getCollection()
									->addFieldTofilter('store_identifier',array('eq'=>$data['store_identifier']));		
				
				if($this->getRequest()->getParam('id')){ //edit mode, do not include current record
					$colec->addFieldToFilter('gmapstrlocator_id',array('neq'=>$this->getRequest()->getParam('id')));
				}
				
				if($colec->getData() != null){
					
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gmapstrlocator')->__('Store Identifier already exist'));
					Mage::getSingleton('adminhtml/session')->setFormData($data);
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
					return;
				}
				
			}
			
			
			
			if(isset($_FILES['store_image']['name']) && $_FILES['store_image']['name'] != '') {
		        //this way the name is saved in DB
	  			$data['store_image'] = $_FILES['store_image']['name'];
			}
			
			if(isset($_FILES['store_marker']['name']) && $_FILES['store_marker']['name'] != '') {
		        //this way the name is saved in DB
	  			$data['store_marker'] = $_FILES['store_marker']['name'];
			}
				
	  			
			$model = Mage::getModel('gmapstrlocator/gmapstrlocator');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				
				$lastInsertedId = $model->getId();
				
				if(isset($_FILES['store_image']['name']) && $_FILES['store_image']['name'] != '') {
					try {	
						/* Starting upload */	
						$uploader = new Varien_File_Uploader('store_image');
						
						// Any extention would work
						$uploader->setAllowedExtensions(array('jpg','JPG','jpeg','gif','GIF','png','PNG'));
						$uploader->setAllowRenameFiles(false);
						$uploader->setFilesDispersion(false);
								
						// We set media/gmapstrlocator as the upload dir
						$path = Mage::getBaseDir('media') . DS ;
						$path = $path .'gmapstrlocator'. DS .$lastInsertedId. DS;
						
						$uploader->save($path, $_FILES['store_image']['name'] );
						
						//create a thumb and save it
						
						$imgPathFull = $path.$_FILES['store_image']['name'];
						$thumbResizedPath = $path.'thumb'. DS .$_FILES['store_image']['name'];
						$imageObj = new Varien_Image($imgPathFull);
						$imageObj->constrainOnly(TRUE);
						$imageObj->keepAspectRatio(TRUE);
						$imageObj->resize(110,110);
						$imageObj->save($thumbResizedPath);						
						
					} catch (Exception $e) {
			      
					}
				}
				
				
				if(isset($_FILES['store_marker']['name']) && $_FILES['store_marker']['name'] != '') {
					try {	
						/* Starting upload */	
						$uploader = new Varien_File_Uploader('store_marker');
						
						// Any extention would work
						$uploader->setAllowedExtensions(array('jpg','JPG','jpeg','gif','GIF','png','PNG'));
						$uploader->setAllowRenameFiles(false);
						$uploader->setFilesDispersion(false);
								
						// We set media/gmapstrlocator as the upload dir
						$path = Mage::getBaseDir('media') . DS ;
						$path = $path .'gmapstrlocator'. DS .'marker'. DS .$lastInsertedId. DS;
						
						$uploader->save($path, $_FILES['store_marker']['name'] );
						
						//create a thumb and save it
						
						$imgPathFull = $path.$_FILES['store_marker']['name'];
						$thumbResizedPath = $path.'thumb'. DS .$_FILES['store_marker']['name'];
						$imageObj = new Varien_Image($imgPathFull);
						$imageObj->constrainOnly(TRUE);
						$imageObj->keepAspectRatio(TRUE);
						$imageObj->backgroundColor(array(255, 255, 255));
						$imageObj->resize(21,18);
						$imageObj->save($thumbResizedPath);						
						
					} catch (Exception $e) {
			      
					}
				}
				
				
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('gmapstrlocator')->__('Store was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gmapstrlocator')->__('Unable to find Store to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('gmapstrlocator/gmapstrlocator');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Store was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $gmapstrlocatorIds = $this->getRequest()->getParam('gmapstrlocator');
        if(!is_array($gmapstrlocatorIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Store(s)'));
        } else {
            try {
                foreach ($gmapstrlocatorIds as $gmapstrlocatorId) {
                    $gmapstrlocator = Mage::getModel('gmapstrlocator/gmapstrlocator')->load($gmapstrlocatorId);
                    $gmapstrlocator->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($gmapstrlocatorIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $gmapstrlocatorIds = $this->getRequest()->getParam('gmapstrlocator');
        if(!is_array($gmapstrlocatorIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Store(s)'));
        } else {
            try {
                foreach ($gmapstrlocatorIds as $gmapstrlocatorId) {
                    $gmapstrlocator = Mage::getSingleton('gmapstrlocator/gmapstrlocator')
                        ->load($gmapstrlocatorId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($gmapstrlocatorIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'gmapstrlocator.csv';
        //$content    = $this->getLayout()->createBlock('gmapstrlocator/adminhtml_gmapstrlocator_grid')
          //  ->getCsv();
	    
		    
		$store_model = Mage::getModel('gmapstrlocator/gmapstrlocator');
		$store_collection = $store_model->getCollection();
		$store_data = $store_collection->getData();
	    
		/** checks columns */
		$headers  = new Varien_Object(array(
		    'store_name' 	=> Mage::helper('gmapstrlocator')->__('Store Name'),
		    'country' 		=> Mage::helper('gmapstrlocator')->__('Country'),
		    'district' 		=> Mage::helper('gmapstrlocator')->__('District / City'),
		    'state' 		=> Mage::helper('gmapstrlocator')->__('State'),
		    'postal_code' 	=> Mage::helper('gmapstrlocator')->__('Postal / Zip Code'),
		    'address' 		=> Mage::helper('gmapstrlocator')->__('Address'),
		    'latitude' 		=> Mage::helper('gmapstrlocator')->__('Latitude'),
		    'longitude' 	=> Mage::helper('gmapstrlocator')->__('Longitude'),
		    'status' 		=> Mage::helper('gmapstrlocator')->__('Status'),
		    'store_phone' 	=> Mage::helper('gmapstrlocator')->__('Phone'),
		    'store_fax' 	=> Mage::helper('gmapstrlocator')->__('Fax'),
		    'store_image' 	=> Mage::helper('gmapstrlocator')->__('Store Image'),
		    'store_description' => Mage::helper('gmapstrlocator')->__('Store Description'),	    
		));
		
		$template = '"{{store_name}}","{{country}}","{{district}}","{{state}}","{{postal_code}}"'
			. ',"{{address}}","{{latitude}}","{{longitude}}","{{status}}","{{store_phone}}","{{store_fax}}","{{store_image}}","{{store_description}}"';
		
		
		$content = $headers->toString($template);
		$content .= "\n";
		
		$storeDataTemplate       = array();
		 
		while ($data = $store_collection->fetchItem()) {
			
			$data->addData($storeDataTemplate);
			$content .= $data->toString($template) . "\n";
		}
		
			
		//echo "<pre>"; print_r($content); exit;
	    

        $this->_sendUploadResponse($fileName, $content);
    }
    
    
    
    
    
    

    public function exportXmlAction()
    {
        $fileName   = 'gmapstrlocator.xml';
        $content    = $this->getLayout()->createBlock('gmapstrlocator/adminhtml_gmapstrlocator_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }



    
    
    public function importAction(){
	
	$this->_title($this->__('Google Map Stores'));
             
        $this->_title($this->__('Import Google Map Stores'));
        
        $this->loadLayout()
            ->_setActiveMenu('gmapstrlocator/gmapstrlocator_import')
            ->_addContent($this->getLayout()->createBlock('gmapstrlocator/adminhtml_gmapstrlocator_import'))
            ->renderLayout();
	
    }
    
    
    
    public function importPostAction()
    {
	
	
        if ($this->getRequest()->isPost() && !empty($_FILES['import_stores_file']['tmp_name'])) {
            try { 
                $this->_importStores();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('gmapstrlocator')->__('The GMap Stores has been imported.'));
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gmapstrlocator')->__('Invalid file upload attempt'));
            }
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gmapstrlocator')->__('Invalid file upload attempt'));
        }
        $this->_redirect('*/*/import');
    }
    
    
    protected function _importStores()
    {
        $fileName   = $_FILES['import_stores_file']['tmp_name'];
        $csvObject  = new Varien_File_Csv();
        $csvData = $csvObject->getData($fileName);

	

        /** checks columns */
        $csvFields  = array(
            0   => Mage::helper('gmapstrlocator')->__('Store Name'),
            1   => Mage::helper('gmapstrlocator')->__('Country'),
            2   => Mage::helper('gmapstrlocator')->__('District / City'),
            3   => Mage::helper('gmapstrlocator')->__('State'),
            4   => Mage::helper('gmapstrlocator')->__('Postal / Zip Code'),
            5   => Mage::helper('gmapstrlocator')->__('Address'),
            6   => Mage::helper('gmapstrlocator')->__('Latitude'),
            7   => Mage::helper('gmapstrlocator')->__('Longitude'),
	    8   => Mage::helper('gmapstrlocator')->__('Status'),
	    9   => Mage::helper('gmapstrlocator')->__('Phone'),
	    10   => Mage::helper('gmapstrlocator')->__('Fax'),
	    11   => Mage::helper('gmapstrlocator')->__('Store Image'),
	    12   => Mage::helper('gmapstrlocator')->__('Store Description'),	    
        );
	
	//echo "<pre>"; print_r($csvFields);
	//echo "<pre>"; print_r($csvData[0]);
	
	
	
	
	
	
	//if csv columns are same with, store columns
	if($csvFields == $csvData[0]){
		
		// iteration on rows = one record
		foreach($csvData as $k => $v){
			
			
			//first row as filed-name
			if($k == 0){ 
				
				continue;
			}
			
			//end of file has more then one empty lines
			if (count($v) <= 1 && !strlen($v[0])) {
				continue;
			}
			
			//$v is an array of row records
			if (count($csvFields) != count($v)) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gmapstrlocator')->__('Invalid file upload attempt'));
			}
			
			
			
			$storeData = array(
				'store_name' 		=> $v[0],
				'country' 		=> $v[1],
				'district' 		=> $v[2],
				'state' 		=> $v[3],
				'postal_code' 		=> $v[4],
				'address' 		=> $v[5],
				'latitude' 		=> $v[6],
				'longitude' 		=> $v[7],
				'status' 		=> $v[8],
				'store_phone' 		=> $v[9],
				'store_fax' 		=> $v[10],
				'store_image' 		=> $v[11],
				'store_description' 	=> $v[12],				
			);
			
			
			//save data to GMap-stores
			$storeModel = Mage::getModel('gmapstrlocator/gmapstrlocator');			
			foreach($storeData as $dataName => $dataValue) {
				$storeModel->setData($dataName, $dataValue);
			}
			
			$storeModel->setCreatedTime(now());
			$storeModel->setUpdateTime(now());
			
			$storeModel->save();
			
			//save store-view against currently added gmap-store
			$current_gmap_id = $storeModel->getId();
			if($current_gmap_id){
				
				$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
				$table_name = Mage::getSingleton('core/resource')->getTableName('gmapstrlocator_store');
				$connection->beginTransaction();				
				
				$condition = array($connection->quoteInto('gmapstrlocator_id=?', $current_gmap_id));
				$connection->delete($table_name, $condition);
				
				$fields = array();
				$fields['gmapstrlocator_id'] = $current_gmap_id;
				$fields['store_id'] = 0; //for all store-views
				
				$connection->insert($table_name, $fields);
				
				$connection->commit();
			}
			
			//echo "<pre>"; print_r($v);
			
			
		}
		
		
	}else {
            Mage::throwException(Mage::helper('gmapstrlocator')->__('Invalid file format upload attempt'));
        }
	
	
    }
    
    
    
    
    //Store Products Attatchments    
    
    
    
    protected function _initProductGmaps() {
		
		$productfaqsId  = (int) $this->getRequest()->getParam('id');
		
		if (!$productfaqsId) {
		    return false;
		}
		
		$producfaqs = Mage::getModel('gmapstrlocator/gmapstrlocator');
		if ($productfaqsId) {
			$producfaqs->load($productfaqsId);
		}
		
			
		Mage::register('current_products_gmaps', $producfaqs);
		
		return $producfaqs;
			
	}
	
	public function productsAction(){
		
		$this->_initProductGmaps();
		
		
		$this->loadLayout();		
		
		$this->getLayout()->getBlock('gmapstrlocator.edit.tab.products')
		 		->setProductsRelatedGmaps($this->getRequest()->getPost('products_related_gmaps', null));
		
		$this->renderLayout();		
	}
	
	
	public function productsGridAction(){
		
		$this->_initProductGmaps();
		   //Push Existing Values in Array
		   $productsarray = array();
		   $Id  = (int) $this->getRequest()->getParam('id');
		   
		      
		   if($Id != 0) {
			   foreach (Mage::registry('current_products_gmaps')->getRelatedProductGmaps($Id) as $products) {
			      $productsarray = $products["product_id"];
			   }
			   
			   if($productsarray){ 
				   array_push($_POST["products_related_gmaps"],$productsarray);
			   }
			   Mage::registry('current_products_gmaps')->setProductsRelatedGmaps($productsarray);
		   }
		   
		$this->loadLayout();
		$this->getLayout()->getBlock('gmapstrlocator.edit.tab.products')
					  ->setProductsRelatedGmaps($this->getRequest()->getPost('products_related_gmaps', null));
		$this->renderLayout();
	}
    
    
    
    
    
    
    
    
    
    
    
    
    
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}
