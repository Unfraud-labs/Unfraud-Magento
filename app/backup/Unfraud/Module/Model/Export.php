<?php

class Unfraud_Module_Model_Export
{
    

    public function exportOrder($order) 
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
			"api_id"=>Mage::getStoreConfig('unfraud_section/unfraud_group/apikey_field',Mage::app()->getStore()), 
			"user_id"=>$order->customer_id, 
			"order_id"=>$order->increment_id, 
			"amount"=>$order->grand_total, 
			"currency_code"=>$order->order_currency_code, 
			"session_id"=>session_id(), 
			"ip_address"=>$order->remote_ip, 
			"timestamp"=>time(), 
			"items"=>$items,
			"billing_address"=>$billing_address,
			"shipping_address"=>$shipping_address,
			"unfraud_plugin"=>"magento"
		);
    	
		$threshold = Mage::getStoreConfig('unfraud_section/unfraud_group/threshold_field',Mage::app()->getStore());
		
		$resp = $this->sendRequest($unfraud_data);
		
		if($threshold != "")
		{
			if($resp->unfraud_score >= $threshold)
			{
				header("Location: /");				
				exit;
			}			
		}

        return true;
    }
    
    function sendRequest($fields)
	{
	
		$url = 'http://api.unfraud.com/events';
				
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($fields));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, true);

		/*
		$verbose = fopen('php://temp', 'rw+');
		curl_setopt($ch, CURLOPT_STDERR, $verbose);		
				
		*/
		$server_output = curl_exec ($ch);
		curl_close ($ch);
		
		/*		
		if ($server_output === FALSE) {
		    printf("cUrl error (#%d): %s<br>\n", curl_errno($ch),
		           htmlspecialchars(curl_error($ch)));
		}
		
		rewind($verbose);
		$verboseLog = stream_get_contents($verbose);
		
		echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";*/		
		$resp = json_decode($server_output);
		return $resp;
	/*	
		echo "<pre>------ Unfraud Request -------\n";
		print_r($fields);
		echo "------ Unfraud Response -------\n";
		print_r($resp);
		exit;*/
			
	}
}