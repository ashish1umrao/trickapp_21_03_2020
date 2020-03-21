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
		$this->db->select('usr.user_id,usr.encrypt_id, usr.employee_id ,usr.notification_id ,usr.user_f_name ,usr.user_m_name ,usr.user_l_name ,usr.user_email ,usr.user_l_name ,usr.user_password ,usr.user_mobile ,usr.user_type ,usr.user_last_login
                     ,usr.user_last_login_ip ,usr.status ,detail.user_pic as admin_image ,address.user_c_address ,address.user_c_locality ,address.user_c_city ,address.user_c_state ,address.user_c_zipcode ,
						   fadm.encrypt_id as franchise_id, fadm.admin_name as franchise_name, fadm.admin_display_name as franchise_display_name, fadm.admin_slug as franchise_slug, fadm.status as franchise_status,
						   sadm.encrypt_id as school_id, sadm.admin_name as school_name, sadm.admin_display_name as school_display_name, sadm.admin_slug as school_slug, sadm.status as school_status,
						   adm.encrypt_id as branch_id, adm.admin_name as branch_name, adm.admin_display_name as branch_display_name, adm.admin_slug as branch_slug, adm.status as branch_status,
                                                     h.encrypt_id as hostel_id, h.hostel_name as hostel_name, h.hostel_display_name as hostel_display_name, h.hostel_slug as hostel_slug, h.status as hostel_status, 
						 ');
		$this->db->from('users as usr');
               $this->db->join('admin as adm','adm.encrypt_id=usr.branch_id','LEFT');
		$this->db->join('admin as fadm','adm.admin_franchise_id=fadm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','adm.admin_school_id=sadm.encrypt_id','LEFT');
                $this->db->join('user_detils as detail','detail.user_id =usr.encrypt_id','LEFT');
                  $this->db->join('user_address as address','address.user_id =usr.encrypt_id','LEFT');
		  $this->db->join('hostel as h','h.hostel_warden_id=usr.encrypt_id','LEFT');
		$this->db->where('usr.user_email',$userEmail);
   
		$query	=	$this->db->get();
          
		if($query->num_rows() >0):
                    $result = $query->row_array() ;
            
                    if($result['hostel_id'] and $result['hostel_name'] and $result['hostel_display_name'] and $result['hostel_slug']  and $result['hostel_status']):
                        return $result ;
                    endif;
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
		if($this->session->userdata('SMS_HOSTEL_ADMIN_ID') == ""):
			setcookie('SMS_HOSTEL_ADMIN_REFERENCEPAGES', uri_string(), time() + 60*60*24*5, '/');
			redirect(base_url());
		else:
              
			if($userType == "admin"):	
				if($showType == ''):
					return true;
				elseif($this->checkPermission($showType)):  
					return true;
				else:		
					$this->session->set_flashdata('alert_warning',lang('accessdenied'));
					redirect($this->session->userdata('SMS_HOSTEL_ADMIN_PATH').'dashboard');
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
		if($this->session->userdata('SMS_HOSTEL_ADMIN_TYPE') == 'WARDEN'):
                   
			
			
				return true;
			
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
		if($this->session->userdata('SMS_HOSTEL_ADMIN_TYPE') == 'WARDEN'):
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
			$this->db->where('admin_id',$this->session->userdata('SMS_HOSTEL_ADMIN_ID'));
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
				$this->db->where('admin_id',$this->session->userdata('SMS_HOSTEL_ADMIN_ID'));
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
		if($this->session->userdata('SMS_HOSTEL_ADMIN_TYPE') == 'WARDEN' ):
                    
			$this->db->select('encrypt_id,module_name,module_display_name,module_icone,child_data');
			$this->db->from('module');
			$this->db->where("show_type ='Hostal'");
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
			$this->db->where('admin_id',$this->session->userdata('SMS_HOSTEL_ADMIN_ID'));
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
		if($this->session->userdata('SMS_HOSTEL_ADMIN_TYPE') == 'WARDEN' ):
			$this->db->select('encrypt_id,module_name,module_display_name');
			$this->db->from('child_module');
			$this->db->where("module_id",$miduleId);
			$this->db->where("show_type ='Hostel'");
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
			$this->db->where('admin_id',$this->session->userdata('SMS_HOSTEL_ADMIN_ID'));
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
		$this->db->select('usr.user_id,usr.encrypt_id, usr.employee_id ,usr.notification_id ,usr.user_f_name ,usr.user_m_name ,usr.user_l_name ,usr.user_email ,usr.user_l_name ,usr.user_password ,usr.user_mobile ,usr.user_type ,usr.user_last_login
                     ,usr.user_last_login_ip ,usr.status ,detail.user_pic as admin_image ,address.user_c_address ,address.user_c_locality ,address.user_c_city ,address.user_c_state ,address.user_c_zipcode ,
						   fadm.encrypt_id as franchise_id, fadm.admin_name as franchise_name, fadm.admin_display_name as franchise_display_name, fadm.admin_slug as franchise_slug, fadm.status as franchise_status,
						   sadm.encrypt_id as school_id, sadm.admin_name as school_name, sadm.admin_display_name as school_display_name, sadm.admin_slug as school_slug, sadm.status as school_status,
						   adm.encrypt_id as branch_id, adm.admin_name as branch_name, adm.admin_display_name as branch_display_name, adm.admin_slug as branch_slug, adm.status as branch_status, 
						 ');
		$this->db->from($tblName);
               $this->db->join('admin as adm','adm.encrypt_id=usr.branch_id','LEFT');
		$this->db->join('admin as fadm','adm.admin_franchise_id=fadm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','adm.admin_school_id=sadm.encrypt_id','LEFT');
                $this->db->join('user_detils as detail','detail.user_id =usr.encrypt_id','LEFT');
                  $this->db->join('user_address as address','address.user_id =usr.encrypt_id','LEFT');
		
		
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
		$this->db->where("show_type ='Hostel'");
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
		$this->db->where("show_type ='Hostel'");
		$this->db->order_by("module_orders ASC");
		$query	=	$this->db->get();
		if($query->num_rows() >0):
			return $query->result_array();
		else:
			return false;
		endif;
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
	** Function name : SelectbookListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Subject List data
	** Date : 16 JANUARY 2018
	************************************************************************/
	function SelectbookListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('bk.*,lib_book_category.category_name');
		$this->db->from($tblName);
		 $this->db->join('lib_book_category','lib_book_category.encrypt_id=bk.book_category_id','LEFT');
		$this->db->join('admin as badm','bk.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','bk.school_id=sadm.encrypt_id','LEFT');
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
	** Function name : SelectbookListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Subject List data
	** Date : 16 JANUARY 2018
	************************************************************************/
	function SelectRoomTypeListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('hostel_room_type.*');
		$this->db->from($tblName);
		$this->db->join('admin as badm','branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','school_id=sadm.encrypt_id','LEFT');
                $this->db->join('hostel as hst','hostel_room_type.hostel_id=hst.encrypt_id','LEFT');
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
	** Function name : SelectbookListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Subject List data
	** Date : 16 JANUARY 2018
	************************************************************************/
	function SelectroomListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('r.*,rt.room_type_name,rt.room_capacity,rt.room_capacity,COUNT(if(ra.status="Y",1,NULL)) as total,(rt.room_capacity-COUNT(if(ra.status="Y",1,NULL))) as available_bed');
		$this->db->from($tblName);
		 $this->db->join('hostel_room_type as rt','rt.encrypt_id=r.room_type_id','LEFT');
		$this->db->join('admin as badm','r.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','r.school_id=sadm.encrypt_id','LEFT');
                $this->db->join('hostel as hst','r.hostel_id=hst.encrypt_id','LEFT');
                	 $this->db->join('hostel_room_allocate as ra','r.encrypt_id=ra.room_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
                  $this->db->group_by('r.encrypt_id'); 
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
	** Function name : SelectbookListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Subject List data
	** Date : 16 JANUARY 2018
	************************************************************************/
	function SelectroomviewData($id='')
	{  
            
            
            $whereCon['where'] = "r.franchise_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_FRANCHISE_ID') . "'
												 AND r.school_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_SCHOOL_ID') . "' 
												 AND r.branch_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_BRANCH_ID') . "'
                                                                                                      AND r.hostel_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_HOSTEL_ID') . "'
                                                                                                           AND ra.room_id = '" .$id . "'
												 ";
		$this->db->select('r.*,rt.room_type_name,rt.room_capacity,rt.room_capacity,COUNT(if(ra.status="Y",1,NULL)) as total,(rt.room_capacity-COUNT(if(ra.status="Y",1,NULL))) as available_bed');
		$this->db->from('hostel_room as r');
		 $this->db->join('hostel_room_type as rt','rt.encrypt_id=r.room_type_id','LEFT');
		$this->db->join('admin as badm','r.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','r.school_id=sadm.encrypt_id','LEFT');
                $this->db->join('hostel as hst','r.hostel_id=hst.encrypt_id','LEFT');
                	 $this->db->join('hostel_room_allocate as ra','r.encrypt_id=ra.room_id','LEFT');
		if($whereCon['where']):	$this->db->where($whereCon['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
                  $this->db->group_by('r.encrypt_id'); 
		
		$query = $this->db->get();
		
			if($query->num_rows() > 0):
       
				return $query->row_array();
			else:
				return false;
			endif;
	
		
	}	// END OF FUNCTION
        
        
        
        /***********************************************************************
	** Function name : get_all_student_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get all parent data
	** Date : 28 Nov 2016
	************************************************************************/
	function get_all_staff_data($searchtx='',$type='')
	{
		$wherequery		=	"u.franchise_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_FRANCHISE_ID') . "'
												 AND u.school_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_SCHOOL_ID') . "' 
												 AND u.branch_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_BRANCH_ID') . "'
                                                                                                       AND ( u.user_f_name LIKE '%".$searchtx."%' OR u.user_m_name LIKE '%".$searchtx."%' OR u.user_l_name LIKE '%".$searchtx."%' OR u.employee_id LIKE '%".$searchtx."%' and u.user_type != 'Parent'  AND status = 'A') ";
		$this->db->select('u.encrypt_id,u.user_f_name,u.user_m_name,u.user_l_name,u.employee_id,u.user_type');
		$this->db->from('users as u');
          
		$this->db->where($wherequery);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0):
			  return $query->result();
		else:
			  return false;
		endif;
	}
        
        
         /***********************************************************************
	** Function name : get_all_staff_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get all parent data
	** Date : 28 Nov 2016
	************************************************************************/
	function get_all_student_data($searchtx='',$type='')
	{
		$wherequery		=	"sb.franchise_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_FRANCHISE_ID') . "'
												 AND sb.school_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_SCHOOL_ID') . "' 
												 AND sb.branch_id = '" . $this->session->userdata('SMS_HOSTEL_ADMIN_BRANCH_ID') . "'
                                                                                                       AND ( s.student_f_name LIKE '%".$searchtx."%' OR s.student_m_name LIKE '%".$searchtx."%' OR s.student_l_name LIKE '%".$searchtx."%' OR s.student_qunique_id LIKE '%".$searchtx."%' OR sb.student_registration_no LIKE '%".$searchtx."%' ) ";
		$this->db->select('s.encrypt_id,s.student_f_name,s.student_m_name,s.student_l_name,s.student_qunique_id ,sb.student_registration_no');
		$this->db->from('students as s');
                $this->db->join('student_branch as sb','sb.student_qunique_id=s.student_qunique_id','LEFT');
		$this->db->where($wherequery);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0):
			  return $query->result();
		else:
			  return false;
		endif;
	}
        
        
        
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
              
              
            endif;
        endif;
       
        return $viewData;
        
        
        
       
    }
    
         /* * *********************************************************************
     * * Function name : student_details
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for get view data.
     * * Date : 01 FEBRUARY 2018
     * ********************************************************************** */
    function StaffDetails($edit_id = '') 
	{
        $html = '';
        if ($edit_id):
          
            $stuQuery = "SELECT * FROM sms_users WHERE encrypt_id = '" . $edit_id . "'";
            $viewData = $this->common_model->get_data_by_query('single', $stuQuery);
            if ($viewData <> ""):

                $stubrQuery   = "SELECT user_pic
			                             	FROM sms_user_detils WHERE user_id = '" . $edit_id . "'";
                $stubrDetails = $this->common_model->get_data_by_query('single', $stubrQuery);
                if ($stubrDetails <> ""):
                    $viewData = array_merge($viewData, $stubrDetails);
                endif;

               
              
            endif;
        endif;
       
        return $viewData;
        
        
        
       
    }
}	


?>