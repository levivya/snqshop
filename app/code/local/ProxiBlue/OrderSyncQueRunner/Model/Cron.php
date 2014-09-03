<?php
/**
 * Cron functions
 *
 * @category   ProxiBlue
 * @package    ProxiBlue_OrderSyncQueRunner
 * @author     Lucas van Staden (support@proxiblue.com.au)
 */
class ProxiBlue_OrderSyncQueRunner_Model_Cron {
    /**
     * Sync via cron schedule
     * 
     * @param object $schedule
     * @return mixed
     */
    public static function sync($schedule) {
        try {
					Mage::log('sync_start');
					$syncModel = mage::getModel('ordersyncquerunner/que')->getCollection()
									->addFieldToFilter('synced_at', array('null' => true));
					ProxiBlue_OrderSyncQueRunner_Model_Que::doSync($syncModel);
					Mage::log('sync');
        } catch (Exception $e) {
					error_log(print_r($e,true),0);
            // save errors.
            mage::logException($e);
        }
    }

    /**
     * Clean old records on schedule
     * 
     * @param object $schedule
     * @return mixed
     */
    public static function clean($schedule) {
        try {
          $syncModel = mage::getModel('ordersyncquerunner/que')->getCollection()
                    ->addFieldToFilter('created_at', array('lteq' => $schedule->getExecutedAt()))
                    ->addFieldToFilter('synced_at', array('notnull' => true));                    
           foreach ($syncModel as $key => $sync) {
               $sync->delete();
           }
           return $this;
        } catch (Dhmedia_Exception $e) {
            // save an errors.
            mage::logException($e);
            return $e->getMessage();
        }
    }
}
