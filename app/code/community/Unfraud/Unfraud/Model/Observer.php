<?php
/**
 * @category    Unfraud
 * @package     Unfraud_Unfraud
 */
class Unfraud_Unfraud_Model_Observer extends Unfraud_Unfraud_Model_Abstract
{
    
    /**
     * Exports an order after it is placed
     * 
     * @param Varien_Event_Observer $observer observer object 
     * 
     * @return boolean
     */
    public function exportOrder(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $this->_getHelper()->log("Observer sending order ".$order->getIncrementId());
        try{
            Mage::getModel('unfraud/export')->exportOrder($order);
        }
        catch(Unfraud_Unfraud_Model_Validation_Exception_FraudException $e){
            Mage::throwException($e->getMessage());
        }
        catch(Exception $e){
            $this->_getHelper()->logError($e->getMessage());
        }

    }



}