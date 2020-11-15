<?php

	function itexmo($number,$message,$apicode){
		$url = 'https://www.itexmo.com/php_api/api.php';
		$itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
		$param = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($itexmo),
			),
		);
		$context  = stream_context_create($param);
		return file_get_contents($url, false, $context);
	}

	/*function itexmo($number,$message,$apicode){
				$ch = curl_init();
				$itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
				curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($itexmo));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				return curl_exec ($ch);
				curl_close ($ch);
	}
	
	$result = itexmo('09958269193', "I'M BUCK", 'TR-M.V.S269193_HBLWE ');
	if($result == ""){
		echo "
			iTexMo: No response from server!!!
			Please check the METHOD used (CURL or CURL-LESS). If you are using CURL then try CURL-LESS and vice versa.	
			Please CONTACT US for help. 
		";	
	}
	else if($result == 0){
		echo "Message Sent!";
	}
	else{
		echo "Error Num ". $result . " was encountered!";
	}*/
	
?>