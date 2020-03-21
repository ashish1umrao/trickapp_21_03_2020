<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Admin_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
		$this->load->database(); 
	}
	
	/* * *********************************************************************
	 * * Function name : Authenticate
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for Admin authenticate Page
	 * * Date : 11 JANUARY 2018
	 * * **********************************************************************/
	public function Authenticate($userEmail='')
	{
		$this->db->select('adm.*, 
						   fadm.encrypt_id as franchise_id, fadm.admin_name as franchise_name, fadm.admin_display_name as franchise_display_name, fadm.admin_slug as franchise_slug, fadm.status as franchise_status,
						   sadm.encrypt_id as school_id, sadm.admin_name as school_name, sadm.admin_display_name as school_display_name, sadm.admin_slug as school_slug, sadm.status as school_status,
						   badm.encrypt_id as branch_id, badm.admin_name as branch_name, badm.admin_display_name as branch_display_name, badm.admin_slug as branch_slug, badm.status as branch_status, 
						   dep.department_name');
		$this->db->from('admin as adm');
		$this->db->join('admin as fadm','adm.admin_franchise_id=fadm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','adm.admin_school_id=sadm.encrypt_id','LEFT');
		$this->db->join('admin as badm','adm.admin_branch_id=badm.encrypt_id','LEFT');
		$this->db->join('department as dep','adm.admin_department_id=dep.encrypt_id','LEFT');
		$this->db->where('adm.admin_email_id',$userEmail);
		$query	=	$this->db->get();
          
		if($query->num_rows() >0):
			return $query->row_array();
		else:
			return false;
		endif;
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : authCheck
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for auth Check
	 * * Date : 11 JANUARY 2018
	 * * **********************************************************************/
	public function authCheck($userType='',$showType='')
	{
		if($this->session->userdata('SMS_ADMIN_ID') == ""):
			setcookie('SMS_ADMIN_REFERENCEPAGES', uri_string(), time() + 60*60*24*5, '/');
			redirect(base_url());
		else:
			if($userType == "admin"):	
				if($showType == ''):
					return true;
				elseif($this->checkPermission($showType)):  
					return true;
				else:		
					$this->session->set_flashdata('alert_warning',lang('accessdenied'));
					redirect($this->session->userdata('SMS_ADMIN_PATH').'dashboard');
				endif;
			endif;
		endif;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name: check_permission
	** Developed By: Manoj Kumar
	** Purpose: This function used for check permission
	** Date: 11 JANUARY 2018
	************************************************************************/
	public function checkPermission($showType='')
	{  
		if($this->session->userdata('SMS_ADMIN_TYPE') == 'Super admin'):
			if($this->router->fetch_class() == 'franchising'):
				return true;
			elseif($showType == 'view_data'):
				return true;
			else:
				return false;
			endif;
		elseif($this->session->userdata('SMS_ADMIN_TYPE') == 'Franchising' && $this->router->fetch_class() != 'franchising'):
			if($this->router->fetch_class() == 'school'):
				return true;
			elseif($showType == 'view_data'):
				return true;
			else:
				return false; 
			endif;
		elseif($this->session->userdata('SMS_ADMIN_TYPE') == 'School' && $this->router->fetch_class() != 'franchising' && $this->router->fetch_class() != 'school'):
			return true;
		elseif($this->session->userdata('SMS_ADMIN_TYPE') == 'Branch' && $this->router->fetch_class() != 'franchising' && $this->router->fetch_class() != 'school' && $this->router->fetch_class() != 'branch'):
			return true;
		else:
			$this->db->select($showType);
			$this->db->from('module_permission');
			$this->db->where('module_name',$this->router->fetch_class());
			$this->db->where('admin_id',$this->session->userdata('SMS_ADMIN_ID'));
			$this->db->where("child_data = 'N'");
			$query	=	$this->db->get();
			if($query->num_rows() > 0):		
				$mdata				=	$query->row_array();
				if($mdata[$showType] == 'Y'):
					return true;
				else:
					return false;
				endif;
			else:		
				$this->db->select($showType);
				$this->db->from('child_module_permission');
				$this->db->where('module_name',$this->router->fetch_class());
				$this->db->where('admin_id',$this->session->userdata('SMS_ADMIN_ID'));
				$cquery	=	$this->db->get();
				if($cquery->num_rows() > 0):	
					$cmdata			=	$cquery->row_array();
					if($cmdata[$showType] == 'Y'):
						return true;
					else:
						return false;
					endif;
				endif;	
			endif;
		endif;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name: get_permission_type
	** Developed By: Manoj Kumar
	** Purpose: This function used for get permission type
	** Date: 11 JANUARY 2018
	************************************************************************/
	public function get_permission_type(&$data)	
	{  
		if($this->session->userdata('SMS_ADMIN_TYPE') == 'Super admin'):
			$data['view_data']			=	'Y';
			$data['add_data']			=	'N';
			$data['edit_data']			=	'N';
			$data['delete_data']		=	'N';
		elseif($this->session->userdata['SMS_ADMIN_TYPE']=='Franchising'):
			if($this->router->fetch_class() == 'school'):
				$data['view_data']		=	'Y';
				$data['add_data']		=	'Y';
				$data['edit_data']		=	'Y';
				$data['delete_data']	=	'Y';
			else:
				$data['view_data']		=	'Y';
				$data['add_data']		=	'N';
				$data['edit_data']		=	'N';
				$data['delete_data']	=	'N';
			endif;
		elseif($this->session->userdata['SMS_ADMIN_TYPE']=='School'):
			$data['view_data']			=	'Y';
			$data['add_data']			=	'Y';
			$data['edit_data']			=	'Y';
			$data['delete_data']		=	'Y';
		elseif($this->session->userdata['SMS_ADMIN_TYPE']=='Branch'):
			$data['view_data']			=	'Y';
			$data['add_data']			=	'Y';
			$data['edit_data']			=	'Y';
			$data['delete_data']		=	'Y';
		else:
			$data['view_data']			=	'N';
			$data['add_data']			=	'N';
			$data['edit_data']			=	'N';
			$data['delete_data']		=	'N';
			
			$this->db->select('view_data,add_data,edit_data,delete_data');
			$this->db->from('module_permission');
			$this->db->where('module_name',$this->router->fetch_class());
			$this->db->where('admin_id',$this->session->userdata('SMS_ADMIN_ID'));
			$this->db->where("child_data = 'N'");
			$query	=	$this->db->get();
			if($query->num_rows() > 0):	
				$mdata						=	$query->row_array();
				$data['view_data']			=	$mdata['view_data'];
				$data['add_data']			=	$mdata['add_data'];
				$data['edit_data']			=	$mdata['edit_data'];
				$data['delete_data']		=	$mdata['delete_data'];
			else:	
				$this->db->select('view_data,add_data,edit_data,delete_data');
				$this->db->from('child_module_permission');
				$this->db->where('module_name',$this->router->fetch_class());
				$this->db->where('admin_id',$this->session->userdata('SMS_ADMIN_ID'));
				$cquery	=	$this->db->get();
				if($cquery->num_rows() > 0):	
					$cmdata					=	$cquery->row_array();
					$data['view_data']		=	$cmdata['view_data'];
					$data['add_data']		=	$cmdata['add_data'];
					$data['edit_data']		=	$cmdata['edit_data'];
					$data['delete_data']	=	$cmdata['delete_data'];
				endif;	
			endif;
		endif;
	}
	
	/* * *********************************************************************
	 * * Function name : get_menu_module  
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This is get menu module
	 * * Date : 11 JANUARY 2018
	 * * **********************************************************************/
	function get_menu_module()
	{ 	
		if($this->session->userdata('SMS_ADMIN_TYPE') == 'Super admin' || $this->session->userdata['SMS_ADMIN_TYPE']=='Franchising' || $this->session->userdata['SMS_ADMIN_TYPE']=='School' || $this->session->userdata['SMS_ADMIN_TYPE']=='Branch'):
			$this->db->select('encrypt_id,module_name,module_display_name,module_icone,child_data');
			$this->db->from('module');
			$this->db->where("show_type ='Admin'");
			$this->db->order_by("module_orders ASC");
			$query = $this->db->get();
			if($query->num_rows() > 0):
				return $query->result_array();
			else:
				return false;
			endif;
		else:
			$this->db->select('encrypt_id,module_id,module_name,module_display_name,module_icone,child_data');
			$this->db->from('module_permission');
			$this->db->where('admin_id',$this->session->userdata('SMS_ADMIN_ID'));
			$this->db->group_start();
			$this->db->where("view_data = 'Y'");
			$this->db->where("child_data = 'N'");
			$this->db->or_where("view_data = 'N'");
			$this->db->or_where("child_data = 'Y'");
			$this->db->group_end();
			$this->db->order_by("module_orders ASC");
			$query = $this->db->get();
			if($query->num_rows() > 0):
				return $query->result_array();
			else:
				return false;
			endif;
		endif;
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : get_menu_child_module  
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This is get menu child module
	 * * Date : 11 JANUARY 2018
	 * * **********************************************************************/
	function get_menu_child_module($miduleId='')
	{ 	
		if($this->session->userdata('SMS_ADMIN_TYPE') == 'Super admin' || $this->session->userdata['SMS_ADMIN_TYPE']=='Franchising' || $this->session->userdata['SMS_ADMIN_TYPE']=='School' || $this->session->userdata['SMS_ADMIN_TYPE']=='Branch'):
			$this->db->select('encrypt_id,module_name,module_display_name');
			$this->db->from('child_module');
			$this->db->where("module_id",$miduleId);
			$this->db->where("show_type ='Admin'");
			$this->db->order_by("module_orders ASC");
			$query = $this->db->get();
			if($query->num_rows() > 0):
				return $query->result_array();
			else:
				return false;
			endif;
		else:
			$this->db->select('encrypt_id,module_name,module_display_name');
			$this->db->from('child_module_permission');
			$this->db->where("permission_id",$miduleId);
			$this->db->where('admin_id',$this->session->userdata('SMS_ADMIN_ID'));
			$this->db->where("view_data = 'Y'");
			$this->db->order_by("module_orders ASC");
			$query = $this->db->get();
			if($query->num_rows() > 0):
				return $query->result_array();
			else:
				return false;
			endif;
		endif;
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
	
	/* * *********************************************************************
	 * * Function name : encript_password
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for encript_password
	 * * Date : 11 JANUARY 2018
	 * * **********************************************************************/
	public function decrypts_password($password)
	{
		return $this->encrypt->decode($password, $this->config->item('encryption_key'));
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : SelectAdminData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select admin data
	** Date : 12 JANUARY 2018
	************************************************************************/
	function SelectAdminData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('adm.*, 
		                   fadm.encrypt_id as franchise_id, fadm.admin_name as franchise_name, fadm.admin_display_name as franchise_display_name, fadm.admin_slug as franchise_slug, fadm.status as franchise_status,
						   sadm.encrypt_id as school_id, sadm.admin_name as school_name, sadm.admin_display_name as school_display_name, sadm.admin_slug as school_slug,
						   badm.encrypt_id as branch_id, badm.admin_name as branch_name, badm.admin_display_name as branch_display_name, badm.admin_slug as branch_slug, 
						   dep.department_name');
		$this->db->from($tblName);
		$this->db->join('admin as fadm','adm.admin_franchise_id=fadm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','adm.admin_school_id=sadm.encrypt_id','LEFT');
		$this->db->join('admin as badm','adm.admin_branch_id=badm.encrypt_id','LEFT');
		$this->db->join('department as dep','adm.admin_department_id=dep.encrypt_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
		
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
	** Function name : get_admin_module
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get module
	** Date : 12 JANUARY 2018
	************************************************************************/
	function get_module()
	{
		$this->db->select('*');
		$this->db->from('module');
		$this->db->where("show_type ='Admin'");
		$this->db->order_by("module_orders ASC");
		$query	=	$this->db->get();
		if($query->num_rows() >0):
			return $query->result_array();
		else:
			return false;
		endif;
	}
	
	/***********************************************************************
	** Function name : get_child_module
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get child module
	** Date : 12 JANUARY 2018
	************************************************************************/
	function get_child_module($miduleId='')
	{
		$this->db->select('*');
		$this->db->from('child_module');
		$this->db->where('module_id',$miduleId);
		$this->db->where("show_type ='Admin'");
		$this->db->order_by("module_orders ASC");
		$query	=	$this->db->get();
		if($query->num_rows() >0):
			return $query->result_array();
		else:
			return false;
		endif;
	}
	
	/***********************************************************************
	** Function name : get_franchising_for_navigation
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get franchising for navigation
	** Date : 25 JANUARY 2018
	************************************************************************/
	function get_franchising_for_navigation()
	{
		$html			=	'<option value="">Select franchising</option>';
		$this->db->select('encrypt_id,admin_name,admin_display_name');
		$this->db->from('admin');
		$this->db->where("admin_type = 'Franchising'");
		$this->db->where("status = 'A'");
		$this->db->order_by("admin_name ASC");
		$query	=	$this->db->get();
		if($query->num_rows() >0):	
			$data	=	$query->result_array();
			foreach($data as $info): 
				$optValue	=	$info['encrypt_id'];
				$curValue	=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
				if($curValue == $optValue):  $select ='selected="selected"'; else: $select =''; endif;
					$optName	=	$info['admin_name'];
				$html		.=	'<option value="'.$optValue.'" '.$select.'>'.$optName.'</option>';
			endforeach;
		endif;
			
		return $html;
	}
	
	/***********************************************************************
	** Function name : get_school_branch_for_navigation
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get school branch for navigation
	** Date : 15 JANUARY 2018
	************************************************************************/
	function get_school_branch_for_navigation($schoolId='')
	{
		if($schoolId):
			$html		=	'<option value="">Select branch</option>';
		else:
			$html		=	'<option value="">Select school & branch</option>';
		endif;
		$this->db->select('encrypt_id,admin_name,admin_display_name');
		$this->db->from('admin');
		$this->db->where('admin_franchise_id',$this->session->userdata('SMS_ADMIN_FRANCHISE_ID'));
		if($schoolId):	$this->db->where('encrypt_id',$schoolId);	endif;
		$this->db->where("admin_type = 'School'");
		$this->db->where("status = 'A'");
		$this->db->order_by("admin_name ASC");
		$query	=	$this->db->get();  
		if($query->num_rows() >0):
			$data	=	$query->result_array();
			foreach($data as $info):		
				$this->db->select('encrypt_id,admin_name,admin_display_name');
				$this->db->from('admin');
				$this->db->where('admin_franchise_id',$this->session->userdata('SMS_ADMIN_FRANCHISE_ID'));
				$this->db->where('admin_school_id',$info['encrypt_id']);
				$this->db->where("admin_type = 'Branch'");
				$this->db->where("status = 'A'");
				$this->db->order_by("admin_name ASC");
				$query1	=	$this->db->get();
				if($query1->num_rows() >0):	
					$data1	=	$query1->result_array();
					foreach($data1 as $info1): 
						$optValue	=	$info['encrypt_id'].'_____'.$info1['encrypt_id'];
						$curValue	=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID').'_____'.$this->session->userdata('SMS_ADMIN_BRANCH_ID');
						if($curValue == $optValue):  $select ='selected="selected"'; else: $select =''; endif;
						
						if($schoolId):
							$optName	=	$info1['admin_name'];
						else:
							$optName	=	$info['admin_name'].' - '.$info1['admin_name'];
						endif;
						$html		.=	'<option value="'.$optValue.'" '.$select.'>'.$optName.'</option>';
					endforeach;
				endif;
			endforeach;
		endif;
		return $html;
	}
	
	/***********************************************************************
	** Function name : get_board_for_navigation
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get board for navigation
	** Date : 30 JANUARY 2018
	************************************************************************/
	function get_board_for_navigation()
	{
		$html			=	'<option value="">Select board</option>';
		$this->db->select('encrypt_id,branch_board_name');
		$this->db->from('branch_boards');
		$this->db->where('franchise_id',$this->session->userdata('SMS_ADMIN_FRANCHISE_ID'));
		$this->db->where('school_id',$this->session->userdata('SMS_ADMIN_SCHOOL_ID'));
		$this->db->where('branch_id',$this->session->userdata('SMS_ADMIN_BRANCH_ID'));
		$this->db->where("status = 'Y'");
		$this->db->order_by("branch_board_name ASC");
		$query	=	$this->db->get();
		if($query->num_rows() >0):	
			$data	=	$query->result_array();
			foreach($data as $info): 
				$optValue	=	$info['encrypt_id'];
				$curValue	=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
				if($curValue == $optValue):  $select ='selected="selected"'; else: $select =''; endif;
					$optName	=	$info['branch_board_name'];
				$html		.=	'<option value="'.$optValue.'" '.$select.'>'.$optName.'</option>';
			endforeach;
		endif;
			
		return $html;
	}
	
	/***********************************************************************
	** Function name : get_permission
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get permission
	** Date : 15 JANUARY 2018
	************************************************************************/
	function get_permission($adminId='')
	{
		$selecarray				=	array();
		$this->db->select('*');
		$this->db->from('module_permission');
		$this->db->where('admin_id',$adminId);
		$this->db->order_by("module_orders ASC");
		$query	=	$this->db->get();
		if($query->num_rows() >0):	
			$data	=	$query->result_array();
			foreach($data as $info):	
				$selecarray['mainmodule'.$info['module_id']]							=	'Y';
				if($info['child_data'] == 'N'):
					if($info['view_data'] == 'Y'):
						$selecarray['mainmodule_view_data'.$info['module_id']]			=	'Y';
					endif;
					if($info['add_data'] == 'Y'):
						$selecarray['mainmodule_add_data'.$info['module_id']]			=	'Y';
					endif;
					if($info['edit_data'] == 'Y'):
						$selecarray['mainmodule_edit_data'.$info['module_id']]			=	'Y';
					endif;
					if($info['delete_data'] == 'Y'):
						$selecarray['mainmodule_delete_data'.$info['module_id']]		=	'Y';
					endif; 
				else:
					$this->db->select('*');
					$this->db->from('child_module_permission');
					$this->db->where('permission_id',$info['encrypt_id']);
					$this->db->where('admin_id',$adminId);
					$this->db->order_by("module_orders ASC");
					$query1	=	$this->db->get();
					if($query1->num_rows() >0):
						$data1	=	$query1->result_array();
						foreach($data1 as $info1):
							$selecarray['childmodule'.$info['module_id'].'_'.$info1['module_id']]						=	'Y';
							if($info1['view_data'] == 'Y'):
								$selecarray['childmodule_view_data'.$info['module_id'].'_'.$info1['module_id']]			=	'Y';
							endif;
							if($info1['add_data'] == 'Y'):
								$selecarray['childmodule_add_data'.$info['module_id'].'_'.$info1['module_id']]			=	'Y';
							endif;
							if($info1['edit_data'] == 'Y'):
								$selecarray['childmodule_edit_data'.$info['module_id'].'_'.$info1['module_id']]			=	'Y';
							endif;
							if($info1['delete_data'] == 'Y'):
								$selecarray['childmodule_delete_data'.$info['module_id'].'_'.$info1['module_id']]		=	'Y';
							endif;
						endforeach;
					endif;
				endif;
			endforeach;
		endif;
		return $selecarray;
	}
	
	/***********************************************************************
	** Function name : delete_permission_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete permission data
	** Date : 15 JANUARY 2018
	************************************************************************/
	function delete_permission_data($adminId='')
	{
		$this->db->delete('module_permission', array('admin_id' => $adminId));
		$this->db->delete('child_module_permission', array('admin_id' => $adminId));
		return true;
	}
	
	/***********************************************************************
	** Function name : SelectDesignationData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Designation data
	** Date : 12 JANUARY 2018
	************************************************************************/
	function SelectDesignationData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('dep.*, 
						   badm.encrypt_id as branch_id, badm.admin_name as branch_name, 
						   sadm.encrypt_id as school_id, sadm.admin_name as school_name');
		$this->db->from($tblName);
		$this->db->join('admin as badm','dep.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','dep.school_id=sadm.encrypt_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectSubjectHeadData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Subject Head data
	** Date : 16 JANUARY 2018
	************************************************************************/
	function SelectSubjectHeadData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('subh.*, 
						   badm.encrypt_id as branch_id, badm.admin_name as branch_name, 
						   sadm.encrypt_id as school_id, sadm.admin_name as school_name');
		$this->db->from($tblName);
		$this->db->join('admin as badm','subh.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','subh.school_id=sadm.encrypt_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectSubjectListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Subject List data
	** Date : 16 JANUARY 2018
	************************************************************************/
	function SelectSubjectListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('sub.*, 
						   subh.subject_head_name, subh.subject_head_short_name,
						   badm.encrypt_id as branch_id, badm.admin_name as branch_name, 
						   sadm.encrypt_id as school_id, sadm.admin_name as school_name');
		$this->db->from($tblName);
		$this->db->join('subject_head as subh','sub.subject_head_id=subh.encrypt_id','LEFT');
		$this->db->join('admin as badm','sub.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','sub.school_id=sadm.encrypt_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectTeacherListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Teacher List data
	** Date : 19 JANUARY 2018
	************************************************************************/
	function SelectTeacherListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('user.*, userd.user_gender, userk.user_adhar_no, subj.subject_name');
		$this->db->from($tblName);
		$this->db->join('user_detils as userd','user.encrypt_id=userd.user_id','LEFT');
		$this->db->join('user_address as usera','user.encrypt_id=usera.user_id','LEFT');
		$this->db->join('user_kyc as userk','user.encrypt_id=userk.user_id','LEFT');
		$this->db->join('teacher_subject as users','user.encrypt_id=users.teacher_id','LEFT');
		$this->db->join('subject as subj','users.subject_id=subj.encrypt_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		$this->db->group_by("user.encrypt_id");
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectTDevProgramData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select teacher development program data
	** Date : 23 JANUARY 2018
	************************************************************************/
	function SelectTDevProgramData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('sdpro.*');
		$this->db->from($tblName);
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectWorkingDaysData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for Select Working Days Data
	** Date : 23 JANUARY 2018
	************************************************************************/
	function SelectWorkingDaysData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('wday.*');
		$this->db->from($tblName);
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectClassListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for Select Class List Data
	** Date : 23 JANUARY 2018
	************************************************************************/
	function SelectClassListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('cls.*');
		$this->db->from($tblName);
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectSectionListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for Select Section List Data
	** Date : 23 JANUARY 2018
	************************************************************************/
	function SelectSectionListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('clasec.*, cls.class_name, tec.user_f_name,tec.user_m_name,tec.user_l_name');
		$this->db->from($tblName);
		$this->db->join('classes as cls','clasec.class_id=cls.encrypt_id','LEFT');
		$this->db->join('users as tec','clasec.class_teacher_id=tec.encrypt_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectSessionMonthData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for Select Session Month Data
	** Date : 24 JANUARY 2018
	************************************************************************/
	function SelectSessionMonthData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('amon.*');
		$this->db->from($tblName);
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectStudentListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Student List data
	** Date : 01 FEBRUARY 2018
	************************************************************************/
	function SelectStudentListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('stubr.student_registration_no, stubr.student_admission_date, stubr.student_relieving_date, 
						   stu.*, cls.class_name, clsec.class_section_name,stucls.class_id,stucls.section_id');
		$this->db->from($tblName);
		$this->db->join('students as stu','stubr.student_qunique_id=stu.student_qunique_id','LEFT');
		$this->db->join('student_class as stucls','stu.student_qunique_id=stucls.student_qunique_id','LEFT');
		$this->db->join('classes as cls','stucls.class_id=cls.encrypt_id','LEFT');
		$this->db->join('class_section as clsec','stucls.section_id=clsec.encrypt_id','LEFT');
		$this->db->join('student_parent as stupar','stu.student_qunique_id=stupar.parent_id','LEFT');
		$this->db->join('users as per','stupar.parent_id=per.encrypt_id','LEFT');
		$this->db->join('student_address as stuadd','stu.student_qunique_id=stuadd.student_qunique_id','LEFT');
		$this->db->join('student_health as stuheal','stu.student_qunique_id=stuheal.student_qunique_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		$this->db->group_by("stu.student_qunique_id");
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : check_student_and_parenty_relation
	** Developed By : Manoj Kumar
	** Purpose  : This function used for check student and parenty relation
	** Date : 28 Nov 2016
	************************************************************************/
	function check_student_and_parenty_relation($studentid='',$gurdianid='')
	{
		$this->db->select('*');
		$this->db->from('student_parent');
		$this->db->where('student_qunique_id',$studentid);
		$this->db->where('parent_id',$gurdianid);
		$query = $this->db->get();
		if($query->num_rows() > 0):
			return $query->row();
		else:
			return false;
		endif;
	}
        
        
	/***********************************************************************
	** Function name : get_all_parent_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get all parent data
	** Date : 28 Nov 2016
	************************************************************************/
	function get_all_parent_data($searchtx='',$type='')
	{
		$wherequery		=	"parent_type = '".$type."' AND ( user_f_name LIKE '%".$searchtx."%' OR user_m_name LIKE '%".$searchtx."%' OR user_l_name LIKE '%".$searchtx."%' OR user_email LIKE '%".$searchtx."%' ) AND user_type = 'Parent'";
		$this->db->select('encrypt_id,user_f_name,user_m_name,user_l_name,user_email');
		$this->db->from('users');
		$this->db->where($wherequery);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0):
			  return $query->result();
		else:
			  return false;
		endif;
	}
        /***********************************************************************
	** Function name : SelectVisitorListData
	** Developed By : yogesh dixit
	** Purpose  : This function used for Select Visitor List Data
	** Date : 23 JANUARY 2018
	************************************************************************/
	function SelectVisitorListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('vtr.*');
		$this->db->from($tblName);
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
         
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
	** Function name : VisitorReport
	** Developed By : yogesh dixit
	** Purpose  : This function used for Select Visitor List Data
	** Date : 23 JANUARY 2018
	************************************************************************/
	function VisitorReport($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('vtr.name,vtr.mobile,vtr.in_time,vtr.out_time,vtr.purpose');
		$this->db->from($tblName);
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectNonTeachingStaffListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select staff List data
	** Date : 19 JANUARY 2018
	************************************************************************/
	function SelectNonTeachingStaffListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('user.*, userd.user_gender, userk.user_adhar_no');
		$this->db->from($tblName);
		$this->db->join('user_detils as userd','user.encrypt_id=userd.user_id','LEFT');
		$this->db->join('user_address as usera','user.encrypt_id=usera.user_id','LEFT');
		$this->db->join('user_kyc as userk','user.encrypt_id=userk.user_id','LEFT');
		
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		$this->db->group_by("user.encrypt_id");
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectRouteListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for Select Route List Data
	** Date : 26 MARCH 2018
	************************************************************************/
	function SelectRouteListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select("route.*, count(roudet.route_detail_id) as total_stop, min(roudet.fee) as min_fee, max(roudet.fee) as max_fee");
		$this->db->from($tblName);
		$this->db->join('sms_route_detail as roudet','route.encrypt_id=roudet.route_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		$this->db->group_by("route.encrypt_id");
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
	** Function name : SelectVehicleListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for Select Vehicle List Data
	** Date : 26 MARCH 2018
	************************************************************************/
	function SelectVehicleListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select("vehicle.*, vehiclet.vehicle_type_name");
		$this->db->from($tblName);
		$this->db->join('vehicle_types as vehiclet','vehicle.vehicle_type=vehiclet.encrypt_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
		$query = $this->db->get();
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
        
        
        
         /* * *********************************************************************
     * * Function name : student_details
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for get view data.
     * * Date : 01 FEBRUARY 2018
     * ********************************************************************** */
    function StudentDetails($edit_id = '') 
	{
        $html = '';
        if ($edit_id):
          
            $stuQuery = "SELECT * FROM sms_students WHERE student_qunique_id = '" . $edit_id . "'";
            $viewData = $this->common_model->get_data_by_query('single', $stuQuery);
            if ($viewData <> ""):

                $stubrQuery   = "SELECT student_registration_no,student_admission_date,student_relieving_date
			                             	FROM sms_student_branch WHERE student_qunique_id = '" . $edit_id . "'";
                $stubrDetails = $this->common_model->get_data_by_query('single', $stubrQuery);
                if ($stubrDetails <> ""):
                    $viewData = array_merge($viewData, $stubrDetails);
                endif;

                $ustuclsQuery  = "SELECT cls.class_name, clsec.class_section_name, stucls.student_roll_no
				                             FROM sms_student_class as stucls
				                             LEFT JOIN sms_classes as cls  ON stucls.class_id=cls.encrypt_id 
				                             LEFT JOIN sms_class_section as clsec  ON stucls.section_id=clsec.encrypt_id 
				                             WHERE stucls.student_qunique_id = '" . $edit_id . "'";
                
                $stuclsDetails = $this->common_model->get_data_by_query('single', $ustuclsQuery);
                if ($stuclsDetails <> ""):
                    $viewData = array_merge($viewData, $stuclsDetails);
                endif;
             $subQuery  = "SELECT par.user_f_name ,par.user_m_name,par.user_f_name,par.user_l_name 	FROM sms_student_parent as stupar
										LEFT JOIN sms_users as par ON stupar.parent_id=par.encrypt_id
                                                                                LEFT JOIN sms_user_detils as  det ON stupar.parent_id=det.user_id
									 	WHERE stupar.student_qunique_id = '" . $edit_id . "' 
										AND stupar.parent_type = 'Father'";
              $parentDetails = $this->common_model->get_data_by_query('single', $subQuery);
             
              
              if ($parentDetails <> ""):
                    $viewData = array_merge($viewData, $parentDetails);
                endif;
                $html .= '  <div class="col-md-12 col-sm-12 col-xs-12 stu_d" >
             <table width="100%">
                <tr>
                 <th rowspan="3" width="100px"><div class="stimg"><img src="'.$viewData['student_image'].'" class="img-responsive"></div></th>
                  <th>Name</th>
                  <th>Father name</th>
                  <th>Class / Section</th>
                  <th>Roll / Reg NO</th>
                </tr>
                <tr>
                  <td>'.$viewData['student_f_name'].' '.$viewData['student_m_name'].' '.$viewData['student_l_name'].'</td>
                  <td>'.$viewData['user_f_name'].' '.$viewData['user_m_name'].' '.$viewData['user_l_name'].'</td>
                  <td>'.$viewData['class_name'].' / '.$viewData['class_section_name'].'</td>
                   <td>'.$viewData['student_roll_no'].' / '.$viewData['student_registration_no'].'</td>
                </tr>
                 
             </table>
         </div>';
              
            endif;
        endif;
       
        return $html;
       
    }
    
    
    /***********************************************************************
	** Function name : SelectStudentExcelreportData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Student excel data
	** Date : 01 FEBRUARY 2018
	************************************************************************/
	function SelectStudentExcelreportData($action='',$tblName='',$whereCon='',$shortField='')
	{ 
		$this->db->select('stu.student_f_name,stu.student_m_name,stu.student_l_name,stubr.student_registration_no,stu.student_qunique_id,stu.student_gender,stu.student_religion,stu.student_category,
						     cls.class_name, clsec.class_section_name,stu.status');
		$this->db->from($tblName);
		$this->db->join('students as stu','stubr.student_qunique_id=stu.student_qunique_id','LEFT');
		$this->db->join('student_class as stucls','stu.student_qunique_id=stucls.student_qunique_id','LEFT');
		$this->db->join('classes as cls','stucls.class_id=cls.encrypt_id','LEFT');
		$this->db->join('class_section as clsec','stucls.section_id=clsec.encrypt_id','LEFT');
		$this->db->join('student_parent as stupar','stu.student_qunique_id=stupar.student_qunique_id','LEFT');
		$this->db->join('users as per','stupar.parent_id=per.encrypt_id','LEFT');
		$this->db->join('student_address as stuadd','stu.student_qunique_id=stuadd.student_qunique_id','LEFT');
		$this->db->join('student_health as stuheal','stu.student_qunique_id=stuheal.student_qunique_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		if($shortField):		$this->db->order_by($shortField);			endif;
		
		
		$query = $this->db->get();
		if($action == 'data'):
			if($query->num_rows() > 0):
				return $query->result_array();
			else:
				return false;
			endif;
		elseif($action == 'count'):
			return $query->num_rows();
		endif;
	}
        
        
        
        
        
        /***********************************************************************
** Function name : SelectFeeHeadListData
** Developed By : Manoj Kumar
** Purpose  : This function used for Select Fee Head List Data
** Date : 06 APRIL 2018
************************************************************************/
function SelectFeeHeadListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
{ 
$this->db->select('fhead.*, ffreq.fee_frequency_name');
$this->db->from($tblName);
$this->db->join('fee_frequency as ffreq','fhead.fee_frequency_id=ffreq.encrypt_id','LEFT');
if($whereCon['where']):	$this->db->where($whereCon['where']); endif;
if($whereCon['like']):  $this->db->where($whereCon['like']); endif;
if($shortField):	$this->db->order_by($shortField);	endif;
if($numPage):	$this->db->limit($numPage,$cnt);	endif;
$query = $this->db->get();
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
** Function name : SelectFeeHeadListData
** Developed By : Manoj Kumar
** Purpose  : This function used for Select Fee Head List Data
** Date : 06 APRIL 2018
************************************************************************/
function SelectFeeGroupListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
{ 
$this->db->select('fgroup.* ');
$this->db->from($tblName);

if($whereCon['where']):	$this->db->where($whereCon['where']); endif;
if($whereCon['like']):  $this->db->where($whereCon['like']); endif;
if($shortField):	$this->db->order_by($shortField);	endif;
if($numPage):	$this->db->limit($numPage,$cnt);	endif;
$query = $this->db->get();
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
	** Function name : get_fee_rate_by_group 
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get active veg by category
	** Date : 13 SEPTEMBER 2017
	************************************************************************/
	function get_fee_rate_by_group($group_id='')
	{
		$rate		=	array();
		$this->db->select('fee_head_id,fee_amount,status');
		$this->db->from('fee_group_feehead_relation');
		
			$this->db->where('fee_group_id',$group_id);
                        $this->db->where('session_year',CURRENT_SESSION);
		
		$query = $this->db->get();
		if($query->num_rows() > 0):
			$data		=	$query->result_array();
			foreach($data as $info):
				$rate[$info['fee_head_id']] = array('fee_amount'=>$info['fee_amount'] ,'active'=>$info['status']);
			endforeach;
		endif;
		return $rate;
	}    
        
        
        
           
        /***********************************************************************
** Function name : SelectFeeFineListData
** Developed By : Manoj Kumar
** Purpose  : This function used for Select Fee Head List Data
** Date : 06 APRIL 2018
************************************************************************/
function SelectFeeFineListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
{ 
$this->db->select('fhead.fee_head_name, ffine.*');
$this->db->from($tblName);
$this->db->join('fee_head as fhead','fhead.encrypt_id=ffine.fee_head_id','LEFT');
if($whereCon['where']):	$this->db->where($whereCon['where']); endif;
if($whereCon['like']):  $this->db->where($whereCon['like']); endif;
if($shortField):	$this->db->order_by($shortField);	endif;
if($numPage):	$this->db->limit($numPage,$cnt);	endif;
$query = $this->db->get();
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
** Function name : SelectFeeHeadListData
** Developed By : Manoj Kumar
** Purpose  : This function used for Select Fee Head List Data
** Date : 06 APRIL 2018
************************************************************************/
function SelectFeeConcessionListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
{ 
$this->db->select('con.* ');
$this->db->from($tblName);

if($whereCon['where']):	$this->db->where($whereCon['where']); endif;
if($whereCon['like']):  $this->db->where($whereCon['like']); endif;
if($shortField):	$this->db->order_by($shortField);	endif;
if($numPage):	$this->db->limit($numPage,$cnt);	endif;
$query = $this->db->get();
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
	** Function name : get_fee_concession_by_head_category 
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get active veg by category
	** Date : 13 SEPTEMBER 2017
	************************************************************************/
	function get_fee_concession_by_head_category()
	{
		$concession		=	array();
		$this->db->select('fee_head_id,fee_concession_type,fee_category_name,fee_concession_value');
		$this->db->from('fee_concession');
		        $this->db->where('franchise_id',$this->session->userdata('SMS_ADMIN_FRANCHISE_ID'));
			 $this->db->where('school_id',$this->session->userdata('SMS_ADMIN_SCHOOL_ID'));
                          $this->db->where('branch_id',$this->session->userdata('SMS_ADMIN_BRANCH_ID'));
                           $this->db->where('board_id',$this->session->userdata('SMS_ADMIN_BOARD_ID'));
                         
                            
                        $this->db->where('session_year',CURRENT_SESSION);
		
		$query = $this->db->get();
		if($query->num_rows() > 0):
			$data		=	$query->result_array();
			foreach($data as $info):
				$concession[$info['fee_head_id'].'_'.$info['fee_category_name']] = array('value'=>$info['fee_concession_value'] );
			endforeach;
		endif;
		return $concession;
	}    


}	
?>




