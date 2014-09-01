<?php

/**
 * 
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_OrderSyncQueRunner
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_OrderSyncQueRunner_Model_Que extends Mage_Core_Model_Abstract
{
    protected function _construct(){
       $this->_init("ordersyncquerunner/que");
    }
   
    /**
     * Handle sync of data
     * Dispatches new event to efect sync
     * 
     * @param type $syncModel
     */
    static public function doSync($syncModel) {
        foreach ($syncModel as $key => $sync) {
            $order = mage::getModel('sales/order')->load($sync->getEntityId());
            try {
							  $container = new Varien_Object();
                Mage::dispatchEvent('sales_order_place_after_que', array('order' => $order, 'container' => $container));
								if($container->getStatus() != 200) {
									throw new Exception('External order sync failed');
								}
                $order->addStatusHistoryComment(mage::helper('ordersyncquerunner')->__('Order Synced'));
                $sync->setSyncedAt(now());
                $sync->save();
            } catch (Exception $e) {
                $order->addStatusToHistory($order->getStatus(), 'Order failed sync: ' . $e->getMessage(), false);
            }
            $order->save();
        }
    }

}
	 
