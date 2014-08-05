<?php

/**
 * Observer to fire event denoting X placed order
 *
 * @category   SNQ
 * @package    SNQ_SPSRBridge
 * @author     Vyacheslav Levin
 */

class SNQ_SPSRBridge_Model_Observer {

     /**
     * Push event denoting customers X placed order
     *
     * @param Varien_Event_Observer $observer
     * @return ProxiBlue_EventFirstPlacedOrder_Model_Adminhtml_Observer
     */
    public function send_order($observer) {

    /*
    $event = $observer->getEvent();
    $order = $event->getOrder();

    $DOC_ID = $order->getIncrementId();
    $DOC_DT = $order->getCreatedAt();
    $numOfLines = 0;

    foreach($order->getItemsCollection() as $prod){
     $numOfLines++;
    }
    Mage::log(“This order has “.$numOfLines.” lines.”);
    $ORD_LINS = $numOfLines;

    $qry = ‘INSERT INTO ORD_HDR (“ID”,”DATE”,”ORD_LINS”) VALUES (“‘.$DOC_ID.’”,”‘.$DOC_DT.’”,”‘.$ORD_LINS.’”)’;
    */

    $postfields = array('field1'=>'value1', 'field2'=>'value2');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://3pl.spsr.ru/webapi/test/execute');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    // Edit: prior variable $postFields should be $postfields;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);

	return $result;
    }

}
