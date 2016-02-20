<?php
/**
 * Unfraud_Module Observer class
 * 
 * PHP version 5.3
 * 
 * @category  Knm
 * @package   Unfraud_Module
 * @author    Oleg Ishenko <oleg.ishenko@Unfraud.com>
 * @copyright 2013 Oleg Ishenko
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version   GIT: <0.1.0>
 * @link      http://www.Unfraud.com/
 *
 */


class Unfraud_Module_Model_Observer
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
        
        Mage::getModel('Unfraud_Module/export')->exportOrder($order);
        
        return true;
        
    }
    

}