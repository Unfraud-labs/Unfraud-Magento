<?php
/**
 * @category    Unfraud
 * @package     Unfraud_Unfraud
 */
class Unfraud_Unfraud_Block_Analytics extends Mage_Core_Block_Template
{
    protected function _prepareLayout()
    {
        $this->setData('api_key', Mage::getStoreConfig(Unfraud_Unfraud_Model_Abstract::API_KEY_FIELD,Mage::app()->getStore()->getId()));
        $this->setData('session_id', Mage::getSingleton("unfraud/analytics")->getSessionId());
        $this->setData('bea_url', Unfraud_Unfraud_Model_Abstract::BEA_URL);
        return parent::_toHtml();

    }
}