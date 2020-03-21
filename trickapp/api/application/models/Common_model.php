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
	** Function name : addData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for add data
	** Date : 04 DECEMBER 2018
	************************************************************************/
	public function addData($tableName='',$param=array())
	{
		$this->db->insert($tableName,$param);
		return $this->db->insert_id();
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : editData
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for edit data
	 * * Date : 04 DECEMBER 2018
	 * * **********************************************************************/
	function editData($tableName='',$param='',$fieldName='',$fieldVallue='')
	{ 
		$this->db->where($fieldName,$fieldVallue);
		$this->db->update($tableName,$param);
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : editDataByMultipleCondition
	** Developed By : Manoj Kumar
	** Purpose  : This function used for edit data by multiple condition
	** Date : 04 DECEMBER 2018
	************************************************************************/
	function editDataByMultipleCondition($tableName='',$param=array(),$whereCondition=array())
	{
		$this->db->where($whereCondition);
		$this->db->update($tableName,$param);
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : deleteData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete data
	** Date : 04 DECEMBER 2018
	************************************************************************/
	function deleteData($tableName='',$fieldName='',$fieldVallue='')
	{
		$this->db->delete($tableName,array($fieldName=>$fieldVallue));
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : deleteParticularData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete particular data
	** Date : 04 DECEMBER 2018
	************************************************************************/
	function deleteParticularData($tableName='',$fieldName='',$fieldValue='')
	{
		$this->db->delete($tableName,array($fieldName=>$fieldValue));
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : deleteByMultipleCondition
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete by multiple condition
	** Date : 04 DECEMBER 2018
	************************************************************************/
	function deleteByMultipleCondition($tableName='',$whereCondition=array())
	{
		$this->db->delete($tableName,$whereCondition);
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name: getDataByParticularField
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by encryptId
	** Date : 04 DECEMBER 2018
	************************************************************************/
	public function getDataByParticularField($tableName='',$fieldName='',$fieldValue='')
	{  
		$this->db->select('*');
		$this->db->from($tableName);	
		$this->db->where($fieldName,$fieldValue);
		$query = $this->db->get();
		if($query->num_rows() > 0):
			return $query->row_array();
		else:
			return false;
		endif;
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
	
	/***********************************************************************
	** Function name: getFieldInArray
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by condition
	** Date : 04 DECEMBER 2018
	************************************************************************/
	public function getFieldInArray($field='',$query='')
	{  
		$returnarray			=	array();
		$query = $this->db->query($query);
		if($query->num_rows() > 0):
			$data	=	$query->result_array();
			foreach($data as $info):
				array_push($returnarray,trim($info[$field]));
			endforeach;
		endif;
		return $returnarray;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getTwoFieldsInArray
	** Developed By: Manoj Kumar
	** Purpose: This function used for get Two Fields In Array
	** Date : 04 DECEMBER 2018
	************************************************************************/
	public function getTwoFieldsInArray($firstField='',$secondField='',$query='')
	{  
		$returnarray			=	array();
		$query = $this->db->query($query);
		if($query->num_rows() > 0):
			$data	=	$query->result_array();
			foreach($data as $info):
				array_push($returnarray,array($firstField=>trim($info[$firstField]),$secondField=>trim($info[$secondField])));
			endforeach;
		endif;
		return $returnarray;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name: getParticularDataByFields
	** Developed By: Manoj Kumar
	** Purpose: This function used for get particular data by fields
	** Date : 04 DECEMBER 2018
	************************************************************************/
	public function getParticularDataByFields($selectField='',$tableName='',$fieldName='',$fieldValue='')
	{  
		$this->db->select($selectField);
		$this->db->from($tableName);	
		$this->db->where($fieldName,ucfirst(strtolower($fieldValue)));
		$this->db->or_where($fieldName,strtolower($fieldValue));
		$query = $this->db->get();
		if($query->num_rows() > 0):
			return $query->row_array();
		else:
			return false;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getLastOrderByFields
	** Developed By: Manoj Kumar
	** Purpose: This function used for get Last Order By Fields
	** Date : 04 DECEMBER 2018
	************************************************************************/
	public function getLastOrderByFields($selectField='',$tableName='',$fieldName='',$fieldValue='')
	{  
		$this->db->select($selectField);
		$this->db->from($tableName);	
		if($fieldName && $fieldValue):
			$this->db->where($fieldName,$fieldValue);
		endif;
		$this->db->order_by($selectField.' DESC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0):
			$data 	=	$query->row_array();
			return $data[$selectField];
		else:
			return 0;
		endif;
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : setAttributeInUse
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for set Attribute In Use
	 * * Date : 04 DECEMBER 2018
	 * * **********************************************************************/
	function setAttributeInUse($tableName='',$param='',$fieldName='',$fieldValue='')
	{ 
		$paramarray[$param]	=	'Y';
		$this->db->where($fieldName,$fieldValue);
		$this->db->update($tableName,$paramarray);
		return true;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name : increaseDecreaseCountByQuery
	** Developed By : Manoj Kumar
	** Purpose  : This function used for increase Decrease Count By Query
	** Date : 04 DECEMBER 2018
	************************************************************************/
	function increaseDecreaseCountByQuery($query='')
	{
		$this->db->query($query);
		return true;
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : encript_password
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for encript_password
	 * * Date : 11 JANUARY 2018
	 * * **********************************************************************/
	public function encript_password($password)
	{
		return $this->encrypt->encode($password, $this->config->item('encryption_key'));
	}	// END OF FUNCTION
}	