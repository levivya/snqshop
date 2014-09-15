<?php
  class SNQ_KupiVipOrderSync_Model_Observer
	{
		public function __construct()
		{
		}
		public function sales_order_place_after_que($observer) {
			//Mage::log('sales_order_place_after_que');
			$order = $observer->getEvent()->getOrder();
			try {
				$order_sender = new SNQ_KupiVipOrderSync_Model_OrderSender();
				$code = $order_sender->SendOrder($order);
				$observer->getEvent()->getContainer()->setStatus($code);
			} catch(Exception $e) {
				Mage::log($e);
				$observer->getEvent()->getContainer()->setStatus(-1);
			}
			return $this;
		}
		public function sales_order_item_cancel($observer) {
			Mage::log('cancel order: '.$observer->getEvent()->getOrder()->getIncrementId());
		}
	}
?>
