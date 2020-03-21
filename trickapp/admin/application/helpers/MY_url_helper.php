<?php

/*
 * Un-create URL Title
 * Takes a url "titled" string as de-constructs it to a human readable string.
 */
if (!function_exists('de_url_title')) {

    function de_url_title($string, $separator = '_') {

        $output = ucfirst(str_replace($separator, ' ', $string));

        return trim($output);
    }

}


/**
 * This file is part of the array_column library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
 * @license http://opensource.org/licenses/MIT MIT
 */
if (!function_exists('array_column')) {
    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();
        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }
        if (!is_array($params[0])) {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                E_USER_WARNING
            );
            return null;
        }
        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }
        $resultArray = array();
        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;
            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }
            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }
            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }
        }
        return $resultArray;
    }
}

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
			//$text	=	('MANOJ').$text.('KUMAR');
			//return	base64_encode($text);
			return	$text;
		}
	}
	
	/*
	 * Decription
	 */
	if (!function_exists('manojDecript')) {
		function manojDecript($text) {
			/* $text	=	base64_decode($text);
			$text	=	str_replace(('MANOJ'),'',$text);
			$text	=	str_replace(('KUMAR'),'',$text); */
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
			if(strpos($mode,"s")!==false){$characters.="abcdefghijklmnopqrstuvwxyz";}
			if(strpos($mode,"l")!==false){$characters.="ABCDEFGHIJKLMNOPQRSTUVWXYZ";}
			if(strpos($mode,"n")!==false){$characters.="0123456789";}
		
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
			if(checkFloat($price)):
				return "Rs. ".number_format($price, 2, '.', '');
			else:
				return "Rs. ".number_format($price);
			endif;
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
	
	/*
	 * Get correct link
	 */
	if (!function_exists('correctLink')) {
		function correctLink($text='',$link='') {
			return	sessionData($text)?sessionData($text):$link;
		}
	}
	
	/*
	 * Get full url
	 */
	 if (!function_exists('currentFullUrl')) {
		function currentFullUrl()
		{
			$CI =& get_instance();
			$url = $CI->config->site_url($CI->uri->uri_string());
			return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
		}
	}
	
	/*
	 * Get correct link
	 */
	if (!function_exists('showStatus')) {
		function showStatus($text='') {
			$statusArray	=	array('A'=>'<span class="badge badge-primary">Active</span>',
									  'I'=>'<span class="badge badge-danger">Inactive</span>',
									  'B'=>'<span class="badge badge-danger">Block</span>',
									  'D'=>'<span class="badge badge-danger">Deleted</span>',
									  'Y'=>'<span class="badge badge-primary">Active</span>',
									  'N'=>'<span class="badge badge-danger">Inactive</span>');
			return $statusArray[$text];
		}
	}
        
        /*
	 * Get correct link
	 */
	if (!function_exists('visitorStatus')) {
		function visitorStatus($time='') {
                    if($time):
                        $status ='<span class="badge badge-primary">OUT</span>' ;
                    else:
                        $status ='<span class="badge badge-danger">IN</span>' ;
                    endif;
		
			return $status;
		}
	}
        
         /*
	 * Get correct link
	 */
	if (!function_exists('attandanceStatus')) {
		function attandanceStatus($text='') {
               $statusArray	=	array('Present'=>'<span class="badge badge-primary">Present</span>',
									  'Absent'=>'<span class="badge badge-danger">Absent</span>',
									  'B'=>'<span class="badge badge-danger">Block</span>',
									  'D'=>'<span class="badge badge-danger">Deleted</span>',
									  'Y'=>'<span class="badge badge-primary">Active</span>',
									  'N'=>'<span class="badge badge-danger">Inactive</span>');
			return $statusArray[$text];
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
				$datedata			=	'1970-01-01 00:00:00';
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
	 * Get student unique id make by below roule
	 * Date of birth dd mm yy   6
	 * Student first name first 2 charector
	 * admission date dd mm yy
	 */
	if (!function_exists('studentUniqueId')) {
		function studentUniqueId($studentDob='',$studentFName='') {
			/* if($studentDob && $studentFName):
				return str_replace('-','',YYMMDDtoDDMMYY($studentDob).strtoupper(substr($studentFName,0,2)).date('dmY'));
			else:
				return generateRandomString(6,'n').generateRandomString(2,'l').date('dmY');
			endif; */			
			return uniqid();
		}
	}
        
        
/*
* Get get url segment for pagination
*/
if (!function_exists('getUrlSegment')) {
	function getUrlSegment() {
	   
	    $urlSegment	=	array('Super admin'=>4,'Franchising'=>4,'School'=>4,'Branch'=>5,'Sub admin'=>5,'Warden'=>5);
		return $urlSegment[sessionData('SMS_ADMIN_TYPE')];
	}

	if (!function_exists('sms_template')) {

    function sms_template($string, $actualvalue = '', $newvalue='') {
    	//print $string; die;
        $outputTemplate = str_replace($actualvalue, $newvalue, $string);
        return $outputTemplate;
    }
}
/**
 * 
 * get curl data 
 * Ashish  
 */
if (!function_exists('add_user')) { 
	function add_user($postData,$slug_url){ //echo "pre"; print_r($postData); die;
			$url = "http://sms.bulksmsserviceproviders.com/api/$slug_url";
		// init the resource
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_POST => true,
							CURLOPT_POSTFIELDS => $postData
							//,CURLOPT_FOLLOWLOCATION => true
						));
						//Ignore SSL certificate verification
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						//get response
						$output = curl_exec($ch);
						//Print error if any
						if (curl_errno($ch)) {
							echo 'error:' . curl_error($ch);
						}
						curl_close($ch);
						return $output;
									
								}
							}

	/////////////////  For Get Method ////////////////////////

				if (!function_exists('add_user_get')) { 
					function add_user_get($sms_slug_url){
							$finalResult = array();
							$ch = curl_init();
							//echo $a = "http://sms.bulksmsserviceproviders.com/api/$sms_slug_url";  die;
							curl_setopt($ch, CURLOPT_URL, "http://sms.bulksmsserviceproviders.com/api/$sms_slug_url");
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
							//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$result = curl_exec($ch);
							curl_close($ch);
							//echo "<pre>"; print_r($result); die;
							return $result;
							
							
						}
					}

	/*
	 * show sms Status
	 */
	if (!function_exists('showsmsStatus')) {
		function showsmsStatus($text='') {
			$statusArray	=	array('1'=>'<span class="badge badge-primary">Active</span>',
									  '2'=>'<span class="badge badge-danger">Block</span>',
									  '3'=>'<span class="badge badge-danger">Deleted</span>
									 ');
			return $statusArray[$text];
		}
	}
}