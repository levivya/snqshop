<?php
  class SNQ_KupiVipOrderSync_Model_Observer
	{
		public function __construct()
		{
		}
		public function sales_order_place_after_que($observer)
		{
			Mage::log('sales_order_place_after_que');
			$observer->getEvent()->getContainer()->setStatus(500);
//			$observer->getEvent()->getContainer()->setStatus(200);
			return $this;
		}
	}
?>
