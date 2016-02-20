<?php
class Unfraud_Module_IndexController extends Mage_Adminhtml_Controller_Action
{  
    public function indexAction()
    {
        $this->loadLayout();
         
        $block = $this->getLayout()->createBlock('core/text', 'green-block')->setText('<h1>Unfraud Dashboard</h1>');
        $this->_addContent($block);

        $apikey =  Mage::getStoreConfig('unfraud_section/unfraud_group/apikey_field',Mage::app()->getStore());
        $email = Mage::getStoreConfig('unfraud_section/unfraud_group/email_field',Mage::app()->getStore());
        $password = Mage::getStoreConfig('unfraud_section/unfraud_group/pwd_field',Mage::app()->getStore());
        
        if($email == "" || $apikey == "" || $password == "")
        {
	        $error_block = $this->getLayout()->createBlock('core/text', 'error-block')->setText('<h3>You need to add API KEY, EMAIL and PASSWORD in plugin configuration.</h3>');
	        $this->_addContent($error_block); 
        }
        else
        {
	         $iframe_block = $this->getLayout()->createBlock('core/text', 'iframe-block')->setText('<iframe src="https://www.unfraud.com/srv/login.php?e='.sha1($email).'&p='.sha1(md5($password."asdz!!3")).'&t='.$apikey.'" width="100%" height="1000" style="border:1px lightGray solid" frameborder="0"></iframe>');
	         $this->_addContent($iframe_block);
        }

        
        $this->_setActiveMenu('unfraud_menu')->renderLayout();      
    }   
}