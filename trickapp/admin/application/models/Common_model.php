<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
		$this->load->database(); 
	}
	
	/***********************************************************************
	** Function name : add_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for add data
	** Date : 11 JANUARY 2018
	************************************************************************/
	public function add_data($tablename='',$params=array())
	{
		$this->db->insert($tablename,$params);
		return $this->db->insert_id();
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : edit_data
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for edit data
	 * * Date : 11 JANUARY 2018
	 * * **********************************************************************/
	function edit_data($table='',$params='',$id='')
	{ 
		$this->db->where('encrypt_id',$id);
		$this->db->update($table,$params);
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : edit_data_by_multiple_cond
	** Developed By : Manoj Kumar
	** Purpose  : This function used for edit data by multiple condition
	** Date : 11 JANUARY 2018
	************************************************************************/
	function edit_data_by_multiple_cond($table='',$params=array(),$whecon=array())
	{
		$this->db->where($whecon);
		$this->db->update($table,$params);
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : delete_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete data
	** Date : 11 JANUARY 2018
	************************************************************************/
	function delete_data($table='',$id='')
	{
		$this->db->delete($table, array('encrypt_id' => $id));
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : delete_particular_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete particular data
	** Date : 11 JANUARY 2018
	************************************************************************/
	function delete_particular_data($table='',$field='',$id='')
	{
		$this->db->delete($table, array($field=>$id));
		//return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : delete_by_multiple_cond
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete by multiple condition
	** Date : 11 JANUARY 2018
	************************************************************************/
	function delete_by_multiple_cond($table='',$whecon=array())
	{
		$this->db->delete($table,$whecon);
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name: get_data_by_encryptId
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by encryptId
	** Date : 11 JANUARY 2018
	************************************************************************/
	public function get_data_by_encryptId($tableName='',$encryptId='')
	{  
		$this->db->select('*');
		$this->db->from($tableName);	
		$this->db->where('encrypt_id',$encryptId);
		$query = $this->db->get();
		if($query->num_rows() > 0):
			return $query->row_array();
		else:
			return false;
		endif;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name: get_data_by_query
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by query
	** Date : 11 JANUARY 2018
	************************************************************************/
	public function get_data_by_query($action='',$query='')
	{  
		$query = $this->db->query($query);
		
		if($action == 'count'):	
			return $query->num_rows();
		elseif($action == 'single'):	
			if($query->num_rows() > 0):
				return $query->row_array();
			else:
				return false;
			endif;
		elseif($action == 'multiple'):	
			if($query->num_rows() > 0):
				return $query->result_array();
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name: get_data_by_condition
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by condition
	** Date : 11 JANUARY 2018
	************************************************************************/
	public function get_ids_in_array($field='',$query='')
	{  
		$returnarray			=	array();
		$query = $this->db->query($query);
		if($query->num_rows() > 0):
			$data	=	$query->result_array();
			foreach($data as $info):
				array_push($returnarray,$info[$field]);
			endforeach;
		endif;
		return $returnarray;
	}	// END OF FUNCTION
	
		/***********************************************************************
	** Function name: getDataByQuery
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by query
	** Date : 04 DECEMBER 2018
	************************************************************************/
	public function getDataByQuery($action='',$query='',$from='')
	{  
		$query = $this->db->query($query);
		if($from == 'procedure'):
			mysqli_next_result( $this->db->conn_id);
		endif;
		if($action == 'count'):	
			return $query->num_rows();
		elseif($action == 'single'):	
			if($query->num_rows() > 0):
				return $query->row_array();
			else:
				return false;
			endif;
		elseif($action == 'multiple'):	
			if($query->num_rows() > 0):
				return $query->result_array();
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	}	// END OF FUNCTION


	//////////////////////////////////////////////
	// ASHISH CODE START //////////////////////////////////////////////////////
	////////////////////////////////////////////////
	
	/* * *********************************************************************
     * * Function name : getsectionwiseassignteacher
     * * Developed By : Ashish UMrao
     * * Purpose  : This function used for get section wise assign teacher
     * * Date : 07 JUNE 2019
	 * * Updated By : Ashish UMrao
	 * * Updated Date : 12 JUNE 2019
     * ********************************************************************** */

    function getsectionwiseassignteacher($CLASSID='',$CLASSSECTIONNAME='',$CLASSTRACHERID=''){//echo  $TRAINno; die;
        $this->db->select('class_section_id');
        $this->db->from('class_section');
		$this->db->where('class_id',$CLASSID);
		$this->db->where('class_section_name',$CLASSSECTIONNAME);
		$this->db->where('class_teacher_id',$CLASSTRACHERID);
        $query = $this->db->get();
		//echo $this->db->last_query(); die;
		if($query->num_rows() > 0):
			return $query->row()->class_section_id;
		else:
			return 0;
		endif;
	}
	/* * *********************************************************************
     * * Function name : getregistrationnoexistornot
     * * Developed By : Ashish UMrao
     * * Purpose  : This function used for get registration no exist or not
     * * Date : 02 JULY 2019
	 * * Updated By : 
	 * * Updated Date : 
     * ********************************************************************** */

    function getregistrationnoexistornot($RegistrationNO=''){ 
		//echo "<pre>"; print_r($this->session->userdata()); die; 
        $this->db->select('student_branch_id');
        $this->db->from('student_branch');
		$this->db->where('student_registration_no',$RegistrationNO);
		//$this->db->where('student_registration_no',$this->session->userdata('SMS_ADMIN_ID'));
		$this->db->where('franchise_id',$this->session->userdata('SMS_ADMIN_FRANCHISE_ID'));
		$this->db->where('school_id',$this->session->userdata('SMS_ADMIN_SCHOOL_ID'));
		$this->db->where('branch_id',$this->session->userdata('SMS_ADMIN_BRANCH_ID'));
		$this->db->where('board_id',$this->session->userdata('SMS_ADMIN_BOARD_ID'));
        $query = $this->db->get();
		//echo $this->db->last_query(); die;
		if($query->num_rows() > 0):
			return $query->row()->student_branch_id;
		else:
			return false;
		endif;
	}
	/***********************************************************************
** Function name : get_student_fee_data_by_encryptId
** Developed By : Manoj Kumar
** Purpose  : This function used for Select student total amount List Data
** Date : 06 APRIL 2018
************************************************************************/
function get_student_fee_data_by_encryptId($viewId='')
{ //echo $viewId; die;
		$this->db->select('fhead.*, fheading.heading_type,sclasssection.class_section_name as section_name,c.class_name,s.student_f_name');
		$this->db->from('student_final_paybal_amount as fhead');
		$this->db->join('fee_frequency as ffreq','fhead.fee_frequency_id=ffreq.encrypt_id','LEFT');
		$this->db->join('fee_heading as fheading','fhead.fee_head_name=fheading.encrypt_id','LEFT');
		$this->db->join('students as s','fhead.student_id=s.student_qunique_id','LEFT');
		$this->db->join('student_class as sclass','fhead.student_id=sclass.student_qunique_id','LEFT');
		$this->db->join('class_section as sclasssection','sclass.section_id=sclasssection.encrypt_id','LEFT');
		$this->db->join('classes as c','sclass.class_id=c.encrypt_id','LEFT');
		$this->db->where('fhead.encrypt_id',$viewId);
		$query = $this->db->get();
		//echo $this->db->last_query(); die;
		if($query->num_rows() > 0):
		return $query->result_array();
		else:
		return false;
		endif;
		
}	// END OF FUNCTION

	/***********************************************************************
	** Function name : get_all_fee_submitted_month
	** Developed By : Ashish UMrao
	** Purpose  : This function used for select get all fee heading list
	** Date : 07 JULY 2019
	************************************************************************/
	function get_all_fee_submitted_month($id='')
	{
		$this->db->select('month_name');
		$this->db->from('fee_paybal_month');
		$this->db->order_by('month_name');
		$this->db->where('student_pay_fee_id',$id);
		$query = $this->db->get();
		if($query->num_rows() > 0):
			return $query->result();
		else:
			return false;
		endif;
	}

	/* * *********************************************************************
	 * * Function name : muliple_edit_data
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for edit data
	 * * Date : 11 JANUARY 2018
	 * * **********************************************************************/
	function muliple_edit_data($table='',$params='',$date=2018-03-05)
	{ //echo $date; die;
		$this->db->where('date',$date);
		$this->db->update($table,$params);
		//print $this->db->last_query(); die;
		return true;
	}	

	/***********************************************************************
	** Function name : sibling_edit_data_by_multiple_cond
	** Developed By : Manoj Kumar
	** Purpose  : This function used for edit data by multiple condition
	** Date : 11 March 2019
	************************************************************************/
	function sibling_edit_data_by_multiple_cond($table='',$params=array(),$whecon=array())
	{
		$this->db->where($whecon);
		$this->db->update($table,$params);
		return true;
	}	// END OF FUNCTION


}	
?>