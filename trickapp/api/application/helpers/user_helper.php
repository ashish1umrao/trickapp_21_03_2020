<?php
/*
 * check apiKey valid or not
 */
if (!function_exists('requestAuthenticate')) { 
	function requestAuthenticate($apiKey=''){ 
		$headerData	=	apache_request_headers();  
        if($headerData['Apidate']):
            $apiKey      =	md5("smsorissa".$headerData['Apidate']);
	    endif;
		if($apiKey == $headerData['Apikey']):
			return true;
		else:
			return false;
		endif;
	} 
} 

/*
 * json outPut
 */
if (!function_exists('outPut')) {
	function outPut($status=0,$message='',$returnData=array()){		
		$data					=	array();
		$result 				= 	array();
		if($status==0){
			$data['success'] 	= 	$status;
			$data['message'] 	= 	$message;
			$data['result'] 	=	(object) $result;
		}else{
			$data['success'] 	= 	$status;
			$data['message'] 	= 	$message;			
			$data['result'] 	= 	$returnData;
		}
		header('Content-type: application/json');
		return json_encode($data);
	}
}

/*
 * json outPut
 */
if (!function_exists('logOutPut')) {
	function logOutPut($returnData=array()){
		header('Content-type: application/json');
		return json_encode($returnData);
	}
}