<?php
  class SNQ_KupiVipOrderSync_Model_Observer
	{
		public function __construct()
		{
		}
		public function sales_order_place_after_que($observer) {
			Mage::log('sales_order_place_after_que');
			$order = $observer->getEvent();
			try {
				$order_sender = new SNQ_KupiVipOrderSync_Model_OrderSender();
				$code = $order_sender->SendOrder($order);
				$observer->getEvent()->getContainer()->setStatus($code);
			} catch(Exception $e) {
				Mage::log($e);
				$observer->getEvent()->getContainer()->setStatus(-1);
			}
//			$observer->getEvent()->getContainer()->setStatus(200);
			return $this;
		}
	}
?>
