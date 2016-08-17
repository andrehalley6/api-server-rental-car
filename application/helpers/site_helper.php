<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists("debug_var")) {
	function debug_var($var, $exit = FALSE) {
		echo "<pre>";
		print_r($var);
		echo "</pre>";

		if($exit)
			exit;
	}
}

if (!function_exists('getallheaders')) { 
    function getallheaders() { 
		$headers = ''; 
       	foreach ($_SERVER as $name => $value) { 
           	if (substr($name, 0, 5) == 'HTTP_') { 
            	$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
           	} 
       } 
       return $headers; 
    } 
}

if(!function_exists("array_to_xml")) {
	// function defination to convert array to xml
	function array_to_xml($array_param, &$xml) {
	    foreach($array_param as $key => $value) {
	        if(is_array($value)) {
	            if(!is_numeric($key)){
	                $subnode = $xml->addChild("$key");
	                array_to_xml($value, $subnode);
	            }
	            else{
	                $subnode = $xml->addChild("item$key");
	                array_to_xml($value, $subnode);
	            }
	        }
	        else {
	            $xml->addChild("$key",htmlspecialchars("$value"));
	        }
	    }
	}
}

if(!function_exists("deliver_response")) {
	function deliver_response($status, $status_message, $data = NULL, $type = "json") {
		// header("HTTP/1.1: $status $status_message");

		if($type == "xml") {
			$response['http_code'] = $status;
			$response['status_message'] = $status_message;
			$response['data'] = $data;

			$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");
			array_to_xml($response, $xml);
			return $xml->asXML();
		}
		else {
			$response['http_code'] = $status;
			$response['status_message'] = $status_message;
			$response['data'] = $data;

			return json_encode($response);
		}
	}
}

if(!function_exists("car_rental_response")) {
	function car_rental_response($idx, $data) {
		// header("HTTP/1.1: $status $status_message");

		$response[$idx] = $data;
		return json_encode($response);
	}
}

if(!function_exists("generate_signature")) {
	function generate_signature($key, $secret, $tStamp) {
		$apiKey = $key;
 
		$secretKey = $secret;

		// Generates a random string of ten digits
		$salt = md5("$apiKey&$tStamp");	// We hash the salt so it will hard to decypher

		// Computes the signature by hashing the salt with the secret key as the key
		$signature = hash_hmac('sha256', $salt, $secretKey, true);

		// base64 encode...
		$encodedSignature = base64_encode($signature);

		// urlencode...
		$encodedSignature = urlencode($encodedSignature);

		// echo "Voila! A signature: " . $encodedSignature;
		return $encodedSignature;
	}
}

if(!function_exists("generate_api_signature")) {
	function generate_api_signature($param = array()) {
		// Get CI Instance
		$CI = get_instance();
		$CI->load->model('api_m');
		
		// Sort array parameter
	    ksort($param);
	    
	    $string_param = "";
	    foreach($param as $key => $value) {
	        $string_param .= ($key.$value);
	    }
	    
	    return md5($CI->api_m->get_api_secret_by_api_key($param['apikey']).$string_param);
	}
}

if(!function_exists("generate_key")) {
	function generate_key() {
		$factory = new QueryAuth\Factory();
		$keyGenerator = $factory->newKeyGenerator();

		$api = array();

		// 40 character random alphanumeric string
		$api['key'] = $keyGenerator->generateKey();

		// 60 character random string containing the characters
		// 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./
		$api['secret'] = $keyGenerator->generateSecret();

		return $api;
	}
}

if(!function_exists("time_out_of_bound")) {
	function time_out_of_bound($now, $timestamp) {
		$drift = 10;	// 10 second time from now
        if (abs($timestamp - $now) > $drift) {
            return true;
        }

        return false;
    }
}