<?php
/**
 * 
 * @category Unfraud
 * @package  Unfraud_Unfraud
 * 
 */
class Unfraud_Unfraud_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param $message
     */
    public function log($message){
        Mage::log($message, null, "unfraud.log");
    }

    /**
     * @param $message
     */
    public function logError($message){
        Mage::log($message, Zend_Log::ERR, "unfraud_error.log");
    }
}
?>
