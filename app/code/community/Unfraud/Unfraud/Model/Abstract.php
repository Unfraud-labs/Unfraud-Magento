<?php
/**
 * @category    Unfraud
 * @package     Unfraud_Unfraud
 */
class Unfraud_Unfraud_Model_Abstract extends Mage_Core_Model_Abstract
{
	protected $_helper;
    protected $url_events = 'http://api.unfraud.com/events';
    protected $url_analytics = 'https://www.unfraud.com/unfraud_analytics/analytics.php?getSession=true';

    protected $session_id;

    const API_KEY_FIELD = 'unfraud_section/unfraud_group/apikey_field';
    const EMAIL_FIELD = 'unfraud_section/unfraud_group/email_field';
    const PASSWORD_FIELD = 'unfraud_section/unfraud_group/pwd_field';
    const THRESHOLD_FIELD= 'unfraud_section/unfraud_group/threshold_field';

    const DASHBOARD_URL = 'https://www.unfraud.com/dashboard/';
    const LOGIN_URL = 'https://unfraud.com/api/helpers/login.php';
    const LOGIN_API_URL = "https://unfraud.com/api/v1.1/index.php/user/?login=true";
    const BEA_URL = '//bea.unfraud.com/bea.js';

    const SUCCESS_API_RESPONSE = 1;
    const SAFE_API_RESPONSE = "safe";
    const FRAUD_API_RESPONSE = "fraud";

    protected function _construct()
    {
        $this->session_id = session_id();
    }

	protected function _getHelper(){
		if(!$this->_helper){
			$this->_helper = Mage::helper("unfraud");
		}
		return $this->_helper;
	}

}