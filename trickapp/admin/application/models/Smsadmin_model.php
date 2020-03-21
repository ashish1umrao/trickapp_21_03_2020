<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Smsadmin_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
		$this->load->database(); 
	}
    /***********************************************************************
	** Function name : SelectsmsAdminData
	** Developed By : Ashish UMrao
	** Purpose  : This function used for select sms admin data
	** Date : 14 March 2020
	************************************************************************/
	function SelectsmsAdminData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('sas.*');
		$this->db->from('sms_admin_user_sms as sas');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
		//echo $this->db->last_query(); die;
		if($action == 'data'):
			if($query->num_rows() > 0):
				return $query->result_array();
			else:
				return false;
			endif;
		elseif($action == 'count'):
			return $query->num_rows();
		endif;
	}	// END OF FUNCTION

    /***********************************************************************
	** Function name: checkauthkeyexist
	** Developed By: Ashish UMrao
	** Purpose: This function used for add user
    ** Created Date : 13 March 2020
    
	************************************************************************/
	function checkauthkeyexist($authkey)
	{
		$this->db->select('authkey');
		$this->db->from('sms_admin_user_sms');
		$this->db->where('authkey',$authkey);
		$query = $this->db->get();
		if($query->num_rows() > 0):
			return $query->row()->authkey;
		else:
			return false;
		endif;
	}
	/* * *********************************************************************
	 * * Function name : edit_data_sms
	 * * Developed By : Ashish UMrao
	 * * Purpose  : This function used for edit data
	 * * Date : 11 JANUARY 2018
	 * * **********************************************************************/
	function edit_data_sms($table='',$params='',$id='')
	{ 
		$this->db->where('user_id',$id);
		$this->db->update($table,$params);
		return true;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: get_data_by_userId
	** Developed By: Ashish UMrao
	** Purpose: This function used for get data by User ID
	** Date : 16 March 2020
	************************************************************************/
	public function get_data_by_userId($tableName='',$encryptId='')
	{  
		$this->db->select('*');
		$this->db->from($tableName);	
		$this->db->where('user_id',$encryptId);
		$query = $this->db->get();
		if($query->num_rows() > 0):
			return $query->row_array();
		else:
			return false;
		endif;
	}	// END OF FUNCTION

	 /***********************************************************************
	** Function name : SelectsmsAdminDatafordashboard
	** Developed By : Ashish UMrao
	** Purpose  : This function used for select sms admin data
	** Date : 14 March 2020
	************************************************************************/
	function SelectsmsAdminDatafordashboard()
	{ 
		$this->db->select('sas.assign_message,a.admin_name');
		$this->db->from('sms_admin_user_sms as sas');
		$this->db->join('admin as a','sas.branch_id=a.encrypt_id','LEFT');
		$this->db->where('a.admin_type',"Branch");
		$query = $this->db->get();
		//echo $this->db->last_query(); die;
			if($query->num_rows() > 0):
				return $query->result_array();
			else:
				return false;
			endif;
		
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name : SelectsmsSUBAdminDatafordashboard
	** Developed By : Ashish UMrao
	** Purpose  : This function used for select sms admin data
	** Date : 14 March 2020
	************************************************************************/
	function SelectsmsSUBAdminDatafordashboard()
	{ //echo "<pre>"; print_r($this->session->userdata('SMS_ADMIN_BRANCH_ID')); die;
		$this->db->select('sas.assign_message');
		$this->db->from('sms_admin_user_sms as sas');
		$this->db->join('admin as a','sas.branch_id=a.encrypt_id','LEFT');
		$this->db->where('sas.branch_id',$this->session->userdata('SMS_ADMIN_BRANCH_ID'));
		$query = $this->db->get();
		if($query->num_rows() > 0):
			return $query->row()->assign_message;
		else:
			return false;
		endif;
		
	}	// END OF FUNCTION
   
}


