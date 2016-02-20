<?php
/**
 * @category    Unfraud
 * @package     Unfraud_Unfraud
 */
class Unfraud_Unfraud_Adminhtml_Unfraud_DashboardController extends Mage_Adminhtml_Controller_Action
{  
    public function indexAction()
    {
        $this->loadLayout();
         
        $block = $this->getLayout()->createBlock('core/text', 'green-block')->setText('<h1>'.$this->__("Unfraud Dashboard").'</h1>');
        $this->_addContent($block);

        $apikey =  Mage::getStoreConfig(Unfraud_Unfraud_Model_Abstract::API_KEY_FIELD,Mage::app()->getStore());
        $email = Mage::getStoreConfig(Unfraud_Unfraud_Model_Abstract::EMAIL_FIELD,Mage::app()->getStore());
        $password = Mage::getStoreConfig(Unfraud_Unfraud_Model_Abstract::PASSWORD_FIELD,Mage::app()->getStore());
        
        if($email == "" || $apikey == "" || $password == "")
        {
	        $error_block = $this->getLayout()->createBlock('core/text', 'error-block')->setText('<h3>'.$this->__("You need to add API KEY, EMAIL and PASSWORD in plugin configuration.").'</h3>');
	        $this->_addContent($error_block); 
        }
        else
        {
            $contents = json_decode(file_get_contents(Unfraud_Unfraud_Model_Abstract::LOGIN_API_URL.'&email='.$email.'&password='.$password),true);
            if($contents["status"]=="logged"){
                $iframe_block = $this->getLayout()->createBlock('core/text', 'iframe-block')->setText('<iframe src="'.Unfraud_Unfraud_Model_Abstract::LOGIN_URL.'?e='.$email.'&p='.$password.'&t='.$apikey.'" width="100%" height="1000" style="border:1px lightGray solid" frameborder="0"></iframe>');
                $this->_addContent($iframe_block);
            }
            else{
                $error_block = $this->getLayout()->createBlock('core/text', 'error-block')->setText('<h3>'.$this->__("Your user credentials are incorrect. Please change your EMAIL and PASSWORD in plugin configuration.").'</h3>');
                $this->_addContent($error_block);
            }

        }

        
        $this->_setActiveMenu('unfraud_menu')->renderLayout();      
    }   
}