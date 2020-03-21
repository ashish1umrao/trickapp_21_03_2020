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
		return true;
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
}	
?>