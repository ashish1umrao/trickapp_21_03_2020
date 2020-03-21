<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Sms_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
		$this->load->database();
	}
	
	/***********************************************************************
	** Function name : send_transactional_sms
	** Developed By : Manoj Kumar
	** Purpose  : This function used for send transactional sms
	** Date : 25 NOVEMBER 2017
	************************************************************************/
	public function send_sms($mobileNumber='',$message=''){      
            if($mobileNumber !='' && $message !=''):
                 $message = urlencode($message);
			$url  =  'http://www.mybulksms.co/api/mt/SendSMS?user=sameer00&password=123456&senderid=TRICKA&channel=Trans&DCS=0&flashsms=0&number='.$mobileNumber.'&text='.$message ;
                      
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
));

$response = curl_exec($curl);
//print "<pre>"; print_r($response); die;
$err = curl_error($curl);

curl_close($curl);

if ($err) {
   
// echo "cURL Error #:" . $err;

 return  false;
} else {
   //echo $response;
   
    return json_decode($response);
	}
        else:
         return  false;   
        endif;
        }
        
        
        
        /***********************************************************************
	** Function name : get_message_content
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get view data by id
	** Date : 31 OCTOBER 2017
	************************************************************************/
	function get_message_content($smstype='')
	{
		$this->db->select('content');
		$this->db->from('sms_template');
		$this->db->where('smstypedata',$smstype);
		$query = $this->db->get();
		if($query->num_rows() > 0):
			$data = $query->row_array();
                       
                return $data['content'];
		else:
			return false;
		endif;
	}
        
        
}	
?>