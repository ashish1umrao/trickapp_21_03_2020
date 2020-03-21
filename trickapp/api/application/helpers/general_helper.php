<?php

	// make friendly url using any string
	if (!function_exists('friendlyURL')) {
		function friendlyURL($inputString){
			$url = strtolower($inputString);
			$patterns = $replacements = array();
			$patterns[0] = '/(&amp;|&)/i';
			$replacements[0] = '-and-';
			$patterns[1] = '/[^a-zA-Z01-9]/i';
			$replacements[1] = '-';
			$patterns[2] = '/(-+)/i';
			$replacements[2] = '-';
			$patterns[3] = '/(-$|^-)/i';
			$replacements[3] = '';
			$url = preg_replace($patterns, $replacements, $url);
		return $url;
		}
	}
	
	// sanitized number :  function auto remove unwanted character form given value 
	if (!function_exists('sanitizedNumber')) {
		function sanitizedNumber($_input) 
		{ 
			return (float) preg_replace('/[^0-9.]*/','',$_input); 
		}
	}
	
	// sanitized filename :  function auto remove unwanted character form given file name
	if (!function_exists('sanitizedFilename')) {
		function sanitizedFilename($filename){
			$sanitized = preg_replace('/[^a-zA-Z0-9-_\.]/','', $filename);
			return $sanitized;
		}
	}
	
	// check, is file exist in folder or not
	if (!function_exists('fileExist')) {
		function fileExist($source='', $file='', $defalut=''){
			if(!$file) return base_url().$source.$defalut;
				
			if(file_exists(FCPATH.$source.$file)):
				return base_url().$source.$file;
			else:
				return base_url().$source.$defalut;
			endif;
		}
	}
	
	if (!function_exists('myExplode')) {
		function myExplode($string){
			if($string):
			$array = explode(",",$string);
			// print_r($array);die;
				return $array;
				
			else:
				return '';
			endif;
		}
	}
	
	/*
	 * Show correct image
	 */
	if (!function_exists('correctImage')) {
		function correctImage($imageurl, $type = '') {
			if($type=='original'):
				$imageurl = str_replace('/thumb','',$imageurl);
			elseif($type):
				$imageurl = str_replace('thumb',$type,$imageurl);
			endif;
			return trim($imageurl);
		}
	}

	/*
	 * Encription
	 */
	if (!function_exists('manojEncript')) {
		function manojEncript($text) {
			$text	=	('MANOJ').$text.('KUMAR');
			return	base64_encode($text);
		}
	}
	
	/*
	 * Decription
	 */
	if (!function_exists('manojDecript')) {
		function manojDecript($text) {
			$text	=	base64_decode($text);
			$text	=	str_replace(('MANOJ'),'',$text);
			$text	=	str_replace(('KUMAR'),'',$text);
			return $text;
		}
	}
	
	/*
	 * Word Limiter
	 */
	define("STRING_DELIMITER", " ");
	if (!function_exists('wordLimiter')){
		function wordLimiter($str, $limit = 10){
			$str = strip_tags($str); 
			if (stripos($str, STRING_DELIMITER)){
				$ex_str = explode(STRING_DELIMITER, $str);
				if (count($ex_str) > $limit){
					for ($i = 0; $i < $limit; $i++){
						$str_s.=$ex_str[$i].'&nbsp;';
					}
					return $str_s.'...';
				}else{
					return $str;
				}
			}else{
				return $str;
			}
		}
	}

	/*
	 * Character Limiter
	 */
	if (!function_exists('characterLimit')){
		function characterLimit($value, $limit = 100, $end = '...'){
			$value		=	htmlspecialchars_decode(stripslashes($value));
		    if (mb_strwidth($value, 'UTF-8') <= $limit) {
		        return $value;
		    }
		    return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).' '.$end;
		}
	}
	
	if (!function_exists('currentDateTime')) {
		function currentDateTime() {
			date_default_timezone_set('Asia/Calcutta');
			return date("Y-m-d H:i:s");
		}
	}
	
	if (!function_exists('currentIp')) {
		function currentIp() {
			return $_SERVER['REMOTE_ADDR']=='::1'?'192.168.1.100':$_SERVER['REMOTE_ADDR'];
		}
	}
	
	if (!function_exists('generateRandomString')) {
		function generateRandomString($length = 10, $mode="sln") {
			$characters = "";
			if($mode=="sln"){$characters.="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";}
			elseif(strpos($mode,"s")!==false){$characters.="abcdefghijklmnopqrstuvwxyz";}
			elseif(strpos($mode,"l")!==false){$characters.="ABCDEFGHIJKLMNOPQRSTUVWXYZ";}
			elseif(strpos($mode,"n")!==false){$characters.="0123456789";}
		
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
	}
	
	if (!function_exists('displayPrice')) {
		function displayPrice($price) {
			//if(checkFloat($price)):
				return number_format($price, 2);
			//else:
			//	return number_format($price);
			//endif;
		}
	}

	if (!function_exists('displayPercent')) {
		function displayPercent($price) {
			if(checkFloat($price)):
				return number_format($price, 2, '.', '').'%';
			else:
				return number_format($price).'%';
			endif;
		}
	}

	if (!function_exists('calculatePercent')) {
		function calculatePercent($prevpr,$curpr) {
			$val = $prevpr-$curpr;
			if($val<=0):
				$amountper = 0;
			else:
				$amountper = (($prevpr-$curpr)/($prevpr))*100;
			endif;
		
			if($amountper==100):
				$amountper = 0;
			endif;
		
			if(checkFloat($amountper)):
				return number_format($amountper,2, '.', '');
			else:
				return number_format($amountper);
			endif;
		}
	}
	
	if (!function_exists('checkFloat')) {
		function checkFloat($s_value) {
			$regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';
			return preg_match($regex, $s_value);
		}
	}
	
	/*
	 * Get session data
	 */
	if (!function_exists('sessionData')) {
		function sessionData($text) {
			$CIOBJ = & get_instance();
			return	$CIOBJ->session->userdata($text);
		}
	}
	

	if (!function_exists('generateUniqueId')) {
		function generateUniqueId($currentId = 1) {
			$newId		=	1000000000+$currentId;
			return $newId;
		}
	}

	if (!function_exists('getSessionForOrder')) {
		function getSessionForOrder() {
			return "SESSION".rand(10000000000,999999999999);
		}
	}

	if (!function_exists('getRandomOrderId')) {
		function getRandomOrderId() {
			return "ORDS".rand(10000000000,999999999999);
		}
	}

	/*
	 * Change ddmmyy to yymmdd
	 */
	if (!function_exists('DDMMYYtoYYMMDD')) {
		function DDMMYYtoYYMMDD($date) {
			if($date):
				$datedata			=	explode('-',$date);
				$datedata			=	$datedata[2].'-'.$datedata[1].'-'.$datedata[0];
			else:
				$datedata			=	'';
			endif;
			return $datedata;
		}
	}
	
	/*
	 * Change yymmdd to ddmmyy
	 */
	if (!function_exists('YYMMDDtoDDMMYY')) {
		function YYMMDDtoDDMMYY($date) {
			if($date && $date != '1970-01-01 00:00:00' && $date != '0000-00-00 00:00:00'):
				$datedata			=	explode(' ',$date);
				$datedata			=	explode('-',$datedata[0]);
				$datedata			=	$datedata[2].'-'.$datedata[1].'-'.$datedata[0];
			else:
				$datedata			=	'';
			endif;
			return $datedata;
		}
	}
	
	/*
	 * add time in date
	 */
	if (!function_exists('addTimeInDate')) {
		function addTimeInDate($date) {
			return $date.' 00:00:00';
		}
	}
	
	/*
	 * remove time from date
	 */
	if (!function_exists('removeTimeFromDate')) {
		function removeTimeFromDate($date) {
			$datedata				=	explode(' ',$date);
			return $datedata[0];
		}
	}
	
	/*
	 * Get charector acording to number
	 */
	if (!function_exists('getCharAccodNumber')){
		function getCharAccodNumber($num=''){
			$numeric 		= 	($num - 1) % 26;
			$letter 		= 	chr(65 + $numeric);
			$num2 			= 	intval(($num - 1) / 26);
			if($num2 > 0){
				return getCharAccodNumber($num2).$letter;
			}else{
				return $letter;
			}
		}
	}

	if (!function_exists('getTablePrefix')) {
		function getTablePrefix() 
		{ 
			return 'sms_'; 
		}
	}
	
	function array_orderby()
	{
	    $args = func_get_args();
	    $data = array_shift($args);
	     foreach ($args as $n => $field) {
	        if (is_string($field)) {
	            $tmp = array();
	            foreach ($data as $key => $row)
	                $tmp[$key] = $row[$field];
	            $args[$n] = $tmp;
	            }
	    }
	    $args[] = &$data;
	    call_user_func_array('array_multisort', $args);
	    return array_pop($args);
	}

	/*
	* Get day range list between two date
	*/
	if (!function_exists('dateRangeBetweenTwoDate')) {
		function dateRangeBetweenTwoDate($first='',$last='',$step='+1 day',$outputFormat='Y-m-d') {
		
			$dates		= 	array();
			$current 	= 	strtotime($first);
			$last 		= 	strtotime($last);
			while($current <= $last):
				$dates[] 	= 	date($outputFormat,$current);
				$current 	= 	strtotime($step,$current);
			endwhile;
			return $dates;
		}
	}
	
	if (!function_exists('getRandomOTP')) {
		function getRandomOTP() {
			return rand(1000,9999);
		}
	}
