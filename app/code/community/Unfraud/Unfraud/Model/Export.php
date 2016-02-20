<?php
/**
 * @category    Unfraud
 * @package     Unfraud_Unfraud
 */
class Unfraud_Unfraud_Model_Export extends Unfraud_Unfraud_Model_Abstract
{

	/**
	 * @param Mage_Sales_Model_Order $order
	 * @return bool
	 */
    public function exportOrder(Mage_Sales_Model_Order $order)
    {
    	
    	$b_address = $order->getBillingAddress();
    	$s_address = $order->getShippingAddress();
    	$items = array();
    	
    	foreach ($order->getAllVisibleItems() as $item){
 
		   $categoryIds = $item->getProduct()->getCategoryIds();
		   $cat_name = "";
	        if(count($categoryIds) ){
	            $firstCategoryId = $categoryIds[0];
	            $_category = Mage::getModel('catalog/category')->load($firstCategoryId);
	
	            $cat_name =  $_category->getName();
	        }
                  
			array_push($items, array(
			"item_id"=>$item->product_id, 
			"product_title"=>$item->name, 
			"price"=>$item->price, 
			"brand"=>$item->getProduct()->getAttributeText('manufacturer'), 
			"category"=>$cat_name, 
			"quantity"=>$item->getQtyOrdered()));           

        }

		$billing_address = array(
			"name"=>$b_address->firstname." ".$b_address->lastname,
			"address_1"=>$b_address->street,
			"address_2"=>"",
			"city"=>$b_address->city,
			"region"=>$b_address->region,
			"country"=>$b_address->country_id,
			"zipcode"=>$b_address->postcode,
			"phone"=>$b_address->telephone
		);

		$shipping_address = array(
			"address_1"=>$s_address->street,
			"address_2"=>"",
			"city"=>$s_address->city,
			"region"=>$s_address->region,
			"country"=>$s_address->country_id,
			"zipcode"=>$s_address->postcode,
			"phone"=>$s_address->telephone
		);
		
		$unfraud_data = array(
			"type"=>"new_order", 
			"api_id"=>Mage::getStoreConfig(Unfraud_Unfraud_Model_Abstract::API_KEY_FIELD,Mage::app()->getStore()),
			"user_id"=>$order->customer_id,
            "user_email"=>$order->customer_email,
            "name"=>$b_address->firstname,
            "surname"=>$b_address->lastname,
			"order_id"=>$order->increment_id, 
			"amount"=>round($order->grand_total,2),
			"currency_code"=>$order->order_currency_code,
			"session_id"=>$this->session_id,
			"ip_address"=>$order->remote_ip,
			"timestamp"=>time(), 
			"items"=>$items,
			"billing_address"=>$billing_address,
			"shipping_address"=>$shipping_address,
			"unfraud_plugin"=>"unfraud-magento_1.0.0"
		);
    	
		$threshold = Mage::getStoreConfig(Unfraud_Unfraud_Model_Abstract::THRESHOLD_FIELD,Mage::app()->getStore());
		
		$resp = $this->sendRequest($unfraud_data);

        if($resp->success == Unfraud_Unfraud_Model_Abstract::SUCCESS_API_RESPONSE ) {
            $fraud = false;
            if($resp->unfraud_label != Unfraud_Unfraud_Model_Abstract::SAFE_API_RESPONSE){
                $this->_getHelper()->log("Unfraud Response flagged as '{$resp->unfraud_label}'");
                $fraud = true;
            }
            else if ($resp->unfraud_label == Unfraud_Unfraud_Model_Abstract::SAFE_API_RESPONSE && $resp->unfraud_label >= (int)$threshold) {
                $this->_getHelper()->log("Unfraud Response score ({$resp->unfraud_label}) higher than default setted in configuration settings ($threshold)");
                $fraud = true;
            }
            if($fraud == true) {
                throw new Unfraud_Unfraud_Model_Validation_Exception_FraudException(Mage::helper("checkout")->__('There was an error processing your order. Please contact us or try again later.'));
            }
        }
        else
        {
            $this->_getHelper()->logError("Unfraud API Error");
        }
    }

	/**
	 * @param array $fields
	 * @return mixed
	 */
    protected function sendRequest(array $fields)
	{

        $this->_getHelper()->log($fields);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL,$this->url_events);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($fields));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, true);

        $server_output = curl_exec($ch);
        curl_close($ch);

        if ($server_output === FALSE) {
			$this->_getHelper()->logError("Response from ".$this->url_events." :");
            $this->_getHelper()->logError(sprintf("cUrl error (#%d): %s<br>\n", curl_errno($ch),
                   htmlspecialchars(curl_error($ch))));
        }
		else{
			$this->_getHelper()->log("Response from ".$this->url_events." :");
			$this->_getHelper()->log($server_output);
		}

        $resp = json_decode($server_output);
		$this->_getHelper()->log($resp);
        return $resp;

			
	}
}