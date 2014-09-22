<?php
require_once 'XML/Serializer.php';
ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
//TODO: move to config
define('ORDER_PROCESS_URL', 'http://test-snowqueen.kupivip.ru/gate/api/order/process');
class SNQ_KupiVipOrderSync_Model_OrderSender {
	public function SendOrder($order) {
		$order_new = $this->ConvertOrder($order);
		$xml = $this->SerializeOrder($order_new);
		if(!$xml) {
			Mage::log('ERROR: Failed to serialize order', $order);
			return;
		}
		Mage::log($xml);
		$code = $this->CallWS($xml, ORDER_PROCESS_URL);
		return $code;
	}

	public function CallWS($xml, $url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: text/xml',
			'Content-Length: ' . strlen($xml)));
		curl_setopt($ch, CURLOPT_POST, TRUE);
		//curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
		$data = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $http_code;
	}

	public function SerializeOrder($data) {
		$options = array(
			'indent'    => '  ',
			'linebreak' => "\n",
			'typeHints' => false,
			'addDecl'   => true,
			'encoding'  => 'UTF-8',
			'rootName'   => 'Orders',
			'mode' => 'simplexml'
		);
		$serializer = new XML_Serializer($options);
		if ($serializer->serialize($data)) {
			return $serializer->getSerializedData();
		}
	}

	private function ConvertOrder($order) {
		// get payment methods
		/*
		$allActivePaymentMethods = Mage::getModel('payment/config')->getActiveMethods();
		foreach($allActivePaymentMethods as $m) {
			Mage::log($m->getCode());
		}
		 */
		$product_size_code = $this->getAttributeId('product_size');
		$payment_type = array(
			'checkmo'                  => 'COD',
			'chronopay'                => 'CHRONOPAY',
			'paypal_billing_agreement' => 'PAYPAL',
			'paypal_mecl'              => 'PAYPAL',
			'cashondelivery'           => 'COD',
			'free'                     => 'COD'
		);
		$ret = array();
		$header = array();
		$header['ProcessType'] = 'SV_OPENTRANS_CREATE_ORDER';
		$header['OrderNo'] = $order->getIncrementId();
		$header['OrderDate'] = $this->formatDateTime($order->getCreatedAt());
		$payment_method = $order->getPayment()->getMethod();
		$header['PaymentType'] = $payment_type[$payment_method];
		foreach($order->getTracksCollection() as $track) {
			if($track->getCarrierCode()) {
				$header['AgentCode'] = $track->getCarrierCode();
			}
			if($track->getTitle()) {
				$header['AgentService'] = $track->getTitle();
			}
		}
		$header['ShippingAmount'] = $order->getShippingAmount();
		$header['OrderAmount'] = $order->getGrandTotal();
		$header['CouponAmountAll'] = 0;
		$header['Account_id'] = $order->getCustomerId();
		$billing_address = $order->getBillingAddress();
		$header['AccountMobile'] = $billing_address->getTelephone();
		$header['AccountPhone'] = $billing_address->getTelephone();
		$shipping_address = $order->getShippingAddress();
		$header['DeliverToCustomerName'] = $shipping_address->getFirstname();
		$header['DeliverToCustomerSurname'] = $shipping_address->getLastname();
		$header['DeliverToPostCode'] = $shipping_address->getPostcode();
		$header['DeliverToCity'] = $shipping_address->getCity();
		$street = implode(' ', $shipping_address->getStreet());
		$header['DeliverToAdressStreet'] = $street;
		/*
		$header['DeliverToAdressHouse'] = '';
		$header['DeliverToAdressCorps'] = '';
		$header['DeliverToAdressApartment'] = '';
		 */
		$header['DeliverToEmail'] = $order->getCustomerEmail();
		$header['DeliverToPhone'] = $shipping_address->getTelephone();
		$header['CouponType'] = $order->getData()['coupon_code'];
		$header['CouponUnit'] = '';
		$header['CouponAmount'] = 0.0000;
		$header['CouponDescription'] = '';
		$header['OrderType'] = 'SNQ';
		$subTotalInclTax = $order->getSubtotalInclTax();
		$shippingInclTax = $order->getShippingInclTax();
		if($header['PaymentType'] != 'COD') {
			$header['AmountPaymentInclVat'] = floatval($subTotalInclTax) + floatval($shippingInclTax);
			if($order->getStatus() == 'pending' || $order->getStatus() == 'new') {
				$header['PaymentCompleted'] = '1';
			} else {
				$header['PaymentCompleted'] = '0';
			}
		}
		$header['DeliverKladrId'] = '';
		//$header['StatusId'] = '';
		$header['Agent_OrderNO'] = '';
		if($order->getCustomerNote()) {
			$header['CustomerComment'] = $order->getCustomerNote();
		}
		foreach($order->getGiftCards() as $card) {
			$header['LoyalCardNumber'] = $card['c'];
		}
		$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
		if(!empty($customer->getDob())) {
			$header['Account_Birthday'] = $this->formatDateTime($customer->getDob());
		}
		$lines = array();
		$ordered_items = $order->getAllItems();
		$line_no = 1;
		foreach($ordered_items as $item){
			$line = array();
			$line['LineNo'] = $line_no;
			$line['ItemId'] = $item->getItemId();
			$line['CskuName'] = $item->getSku();
			$line['VariantCode'] = '';
			$line['Quantity'] = $item->getQtyOrdered();
			$line['PriceInclVat'] = $item->getProduct()->getFinalPrice();
			/*
			$line['CouponExclVat'] = '';
			 */
			if(!empty($item->getDiscountAmount())) {
				$line['CouponInclVat'] = $item->getDiscountAmount();
			}
			$line['ItemName'] = $item->getName();
			$line['VariantName'] = $item->getProduct()->getAttributeText('product_size');
			$line['url'] = Mage::helper('catalog/image')->init($item->getProduct(), 'image');
			if(!empty($item->getDescription())) {
				$line['ItemDescription'] = $item->getDescription();
			}
			//$line['ReturnReason'] = '';
			//$line['Campaign'] = '';
			//$line['Gift'] = '';
			$lines[] = $line;
			++$line_no;
		}
		$header['OrderLine'] = $lines;
		$ret['OrderHeader'] = $header;
		return $ret;
	}

	public function getAttributeId($name) {
		$eavAttribute = new Mage_Eav_Model_Mysql4_Entity_Attribute();
		$code = $eavAttribute->getIdByCode('catalog_product', $name);
		return $code;
	}

	public function formatDateTime($date) {
		return date_format(
			date_create_from_format('Y-m-d H:i:s', $date),
			'Ymd H:i:s.000');
	}
}
?>
