<?php
require_once 'XML/Serializer.php';
//TODO: move to config
define('ORDER_PROCESS_URL', 'http://test-snowqueen.kupivip.ru/gate/api/order/process');
class SNQ_KupiVipOrderSync_Model_OrderSender {
	public function SendOrder(array $order) {
		$order_new = $this->ConvertOrder($order);
		$xml = $this->SerializeOrder($order_new);
		Mage::log($xml);
		if(!$xml) {
			Mage::log('ERROR: Failed to serialize order', $order);
			return;
		}
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
			"indent"    => "  ",
			"linebreak" => "\n",
			"typeHints" => false,
			"addDecl"   => true,
			"encoding"  => "UTF-8",
			"rootName"   => "Orders",
			'mode' => 'simplexml'
		);
		$serializer = new XML_Serializer($options);
		if ($serializer->serialize($data)) {
			return $serializer->getSerializedData();
		}
	}

	private function ConvertOrder($order) {
		//TODO: implement all required fields
		$ret = array();
		$ret['OrderHeader'] = array(
			'foo' => 'bar',
			'OrderLine' => array(
				'baz' => 'bazz'
			)
		);
		return $ret;
	}
}
?>
