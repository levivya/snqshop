<?php
/**
 * Advance Testimonial extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   FME
 * @package    Advance Testimonial
 * @author     Kamran Rafiq Malik <support@fmeextensions.com>
 *             1- Created - 10-10-2010
 *
 * 	       Asif Hussain <support@fmeextensions.com>
 * 	       1 - Detail page routing - 23-03-2012
 * 	       
 * @copyright  Copyright 2012 © www.fmeextensions.com All right reserved
 */

class FME_Gmapstrlocator_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function initControllerRouters($observer)
    {    		
        $front = $observer->getEvent()->getFront();
        $router = new FME_Gmapstrlocator_Controller_Router();
        $front->addRouter('gmapstrlocator', $router);
        
    }

    public function match(Zend_Controller_Request_Http $request)
    {
	if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
		

        $route = Mage::helper('gmapstrlocator')->getGMapListIdentifier();
	
	$identifier = trim($request->getPathInfo(), '/');
	
        $identifier = str_replace(Mage::helper('gmapstrlocator')->getGMapSeoSuffix(), '', $identifier);
	
	
	$condition = new Varien_Object(array(
            'identifier' => $identifier,
            'continue'   => true
        ));
	
	Mage::dispatchEvent('gmapstrlocator_controller_router_match_before', array(
            'router'    => $this,
            'condition' => $condition
        ));
	
	
	$identifier = $condition->getIdentifier();
	
	
	if ($condition->getRedirectUrl()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($condition->getRedirectUrl())
                ->sendResponse();
            $request->setDispatched(true);
            return true;
        }

        if (!$condition->getContinue()) {
            return false;
        }
	
	
		if ( $identifier == $route ) {

		    $request->setModuleName('gmapstrlocator')
				    ->setControllerName('index')
				    ->setActionName('index');
				    
		    return true;
				    
		}
		
		
		//Get Id against this detail " Url_identifier "
		
		$detail_url_identifier = substr($identifier,strpos($identifier,'/')+1,strlen($identifier)); 
		
		$resource = Mage::getSingleton('core/resource');
		$read = $resource->getConnection('core_read');
		$table_name = $resource->getTableName('gmapstrlocator/gmapstrlocator');
		
		
		$select = $read->select()->from($table_name)->where('store_identifier = ?',$detail_url_identifier);
		$result_row = $read->fetchRow($select);
		
		$str_id = $result_row['gmapstrlocator_id'];
		
		if (!$str_id) {
		    return false;
		}
		    $request->setModuleName('gmapstrlocator')
			    ->setControllerName('index')
			    ->setActionName('detail')
			    ->setParam('id',$str_id);
	
		    $request->setAlias(
				    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
				    $identifier
		    );
		
		
		
		
		return true;
		
		
		    
		
	    
	
	
	
		
       

    }
}