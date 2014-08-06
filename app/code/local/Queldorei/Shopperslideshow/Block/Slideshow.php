<?php
/**
 * @version   1.0 12.0.2012
 * @author    Queldorei http://www.queldorei.com <mail@queldorei.com>
 * @copyright Copyright (C) 2010 - 2012 Queldorei
 */
//require_once($_SERVER['DOCUMENT_ROOT'].'/ipgeobase/ipgeobase.php');

class Queldorei_Shopperslideshow_Block_Slideshow extends Mage_Core_Block_Template
{
	protected function _beforeToHtml()
	{
		$config = Mage::getStoreConfig('shopperslideshow', Mage::app()->getStore()->getId());
		if (Mage::helper('shopperslideshow/data')->isSlideshowEnabled()) {
			$this->setTemplate('queldorei/' . $config['config']['slider'] . '.phtml');
		}

		return $this;
	}

	public function _prepareLayout()
	{
		return parent::_prepareLayout();
	}

	public function getSlideshow()
	{
		if (!$this->hasData('shopperslideshow')) {
			$this->setData('shopperslideshow', Mage::registry('shopperslideshow'));
		}
		return $this->getData('shopperslideshow');

	}

	public function getSlides()
	{
		$config = Mage::getStoreConfig('shopperslideshow', Mage::app()->getStore()->getId());
		if ( $config['config']['slider'] == 'flexslider' ) {
			$model = Mage::getModel('shopperslideshow/shopperslideshow');
		} else {
			$model = Mage::getModel('shopperslideshow/shopperrevolution');
		}
		$slides = $model->getCollection()
			->addStoreFilter(Mage::app()->getStore())
			->addFieldToSelect('*')
			->addFieldToFilter('status', 1)
			->setOrder('sort_order', 'asc');
		// geotargeting filter
		error_log($this->getMyCity());
		$slides->getSelect()->where("(global = 1) or (global = 2 and city like '%".$this->getMyCity()."%')");
//		error_log(print_r($slides->getSelect()->__toString(), true), 0);
		return $slides;
	}

	// Function to get the client IP address
	private function get_client_ip() 
	{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
	}

	private function getMyCity()
	{
	  $gb = new IPGeoBaseRU_IPGeoBase();
	  $data = $gb->getRecord($this->get_client_ip());
		return $data ? $data['city'] : 'Москва';
	}
}
?>
