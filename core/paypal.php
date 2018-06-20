<?php
if(!defined('IMPARENT')){exit();} // No direct access

/* PayPal Functions */
$nvp_method = array(
				'DoDirectPayment',
				'SetExpressCheckout',
				'DoAuthorization',
				'DoCapture',
				'DoVoid',
				'GetTransactionDetails',
				'TransactionSearch',
				'RefundTransaction',
				'Reauthorization',
				'MassPay',
				'GetBalance'
			);

define('API_USERNAME','bfa1_1298872305_biz_api1.gmail.com');
define('API_PASSWORD','1298872321');
define('API_SIGNATURE','A4ZpX7ySdUQjZYoVSF09NIhvAwYDAw.hi94PNLWByh0nuE.SjCzNI1a6');
define('API_ENDPOINT','https://api-3t.sandbox.paypal.com/nvp');
define('API_VERSION', '65.1');

/* Construct NVP Parameters */
function nvp_construct($nvpParam){
	$str = '';
	if(defined('API_USERNAME') && defined('API_PASSWORD') && 
			defined('API_SIGNATURE') && defined('API_ENDPOINT') && defined('API_VERSION')){
		$nvpParam['USER'] = rawurlencode(API_USERNAME);
		$nvpParam['PWD'] = rawurlencode(API_PASSWORD);
		$nvpParam['SIGNATURE'] = rawurlencode(API_SIGNATURE);
		$nvpParam['VERSION'] = API_VERSION;
		
		foreach($args as $key => $value){
			$str.=($str!=''?'&' :'').$key.'='.$value;
		}
		return $str;
	}
	return '';
}

/* Make NVP Request */
function nvp_request($method,$action,$param,$retobj = true){
	if(in_array($method,$nvp_method) && defined('API_ENDPOINT')){
		$nvpParam = array();
		$nvpParam['METHOD'] = $method;
		$nvpParam['PAYMENTACTION'] = $action;
		$nvpParam = $param;		
	
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,API_ENDPOINT);
		curl_setopt($ch,CURLOPT_VERBOSE,1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,nvp_construct($nvpParam));	
		parse_str(curl_exec($ch),$response);
		curl_close($ch);
		if($retobj){
			$response = (object) $response;
		}
		return $response;
	}
}

?>