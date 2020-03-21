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
						 ');
		$this->db->from('users as usr');
               $this->db->join('admin as adm','adm.encrypt_id=usr.branch_id','LEFT');
		$this->db->join('admin as fadm','adm.admin_franchise_id=fadm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','adm.admin_school_id=sadm.encrypt_id','LEFT');
                $this->db->join('user_detils as detail','detail.user_id =usr.encrypt_id','LEFT');
                  $this->db->join('user_address as address','address.user_id =usr.encrypt_id','LEFT');
		
		$this->db->where('usr.user_email',$userEmail);
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
		if($this->session->userdata('SMS_LIBRARY_ADMIN_ID') == ""):
			setcookie('SMS_LIBRARY_ADMIN_REFERENCEPAGES', uri_string(), time() + 60*60*24*5, '/');
			redirect(base_url());
		else:
              
			if($userType == "admin"):	
				if($showType == ''):
					return true;
				elseif($this->checkPermission($showType)):  
					return true;
				else:		
					$this->session->set_flashdata('alert_warning',lang('accessdenied'));
					redirect($this->session->userdata('SMS_LIBRARY_ADMIN_PATH').'dashboard');
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
		if($this->session->userdata('SMS_LIBRARY_ADMIN_TYPE') == 'Librarian'):
                   
			
			
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
		if($this->session->userdata('SMS_LIBRARY_ADMIN_TYPE') == 'Librarian'):
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
			$this->db->where('admin_id',$this->session->userdata('SMS_LIBRARY_ADMIN_ID'));
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
				$this->db->where('admin_id',$this->session->userdata('SMS_LIBRARY_ADMIN_ID'));
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
		if($this->session->userdata('SMS_LIBRARY_ADMIN_TYPE') == 'Librarian' ):
                    
			$this->db->select('encrypt_id,module_name,module_display_name,module_icone,child_data');
			$this->db->from('module');
			$this->db->where("show_type ='Library'");
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
			$this->db->where('admin_id',$this->session->userdata('SMS_LIBRARY_ADMIN_ID'));
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
		if($this->session->userdata('SMS_LIBRARY_ADMIN_TYPE') == 'Librarian' ):
			$this->db->select('encrypt_id,module_name,module_display_name');
			$this->db->from('child_module');
			$this->db->where("module_id",$miduleId);
			$this->db->where("show_type ='Library'");
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
			$this->db->where('admin_id',$this->session->userdata('SMS_LIBRARY_ADMIN_ID'));
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
		$this->db->where("show_type ='Library'");
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
		$this->db->where("show_type ='Library'");
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
	function SelectLibRecordListData($action='',$tblName='',$whereConUpdateDate='', $whereConCreateDate='' ,$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('bk.*','lib_barcode.barcode','lib_books.book_name','lib_books.book_author','students.student_f_name','students.student_m_name','students.student_l_name');
		$this->db->from($tblName);
		$this->db->join('lib_barcode  ','bk.barcode=lib_barcode.barcode','LEFT');
                $this->db->join('lib_books','lib_barcode.book_id=lib_books.encrypt_id','LEFT');
                $this->db->join('students','students.student_qunique_id=bk.reader_id','LEFT');
		$this->db->join('admin as badm','bk.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','bk.school_id=sadm.encrypt_id','LEFT');
		if($whereConUpdateDate['where']):	$this->db->where($whereConUpdateDate['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		//if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
                 $query1 = $this->db->get_compiled_select();
		//$query = $this->db->get();
                 
                 $this->db->select('bk.*','lib_barcode.barcode','lib_books.book_name','lib_books.book_author','students.student_f_name','students.student_m_name','students.student_l_name');
		$this->db->from($tblName);
		$this->db->join('lib_barcode  ','bk.barcode=lib_barcode.barcode','LEFT');
                $this->db->join('lib_books','lib_barcode.book_id=lib_books.encrypt_id','LEFT');
                $this->db->join('students','students.student_qunique_id=bk.reader_id','LEFT');
		$this->db->join('admin as badm','bk.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','bk.school_id=sadm.encrypt_id','LEFT');
  if($whereConCreateDate['where']):	$this->db->where($whereConCreateDate['where']);	 	endif;
		if($whereCon['like']):  $this->db->where($whereCon['like']); 		endif;
		//if($shortField):		$this->db->order_by($shortField);			endif;
		if($numPage):			$this->db->limit($numPage,$cnt);			endif;
                 $query2 = $this->db->get_compiled_select(); 
                 $query = $this->db->query($query1." UNION ALL ".$query2);
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
	** Function name : SelectfineListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Subject List data
	** Date : 16 JANUARY 2018
	************************************************************************/
	function SelectfineListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('f.* ,lib_fine_rule.fine_rule');
		$this->db->from($tblName);
		
			$this->db->join('lib_fine_rule','f.fine_rule_id=lib_fine_rule.encrypt_id','LEFT');
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
	function SelectReturnbookListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('Rb.* ,lib_shelf.shelf,lib_shelf_row.shelf_row ,lib_barcode.barcode ,lib_books.book_name ,  lib_books.encrypt_id as book_id ,lib_barcode.encrypt_id as barcode_encypt_id ,  lib_books.book_author , students.student_f_name , students.student_m_name , students.student_l_name');
		$this->db->from($tblName);
		$this->db->join('lib_barcode  ','Rb.barcode=lib_barcode.barcode','LEFT');
                $this->db->join('lib_shelf  ','lib_barcode.shelf_id=lib_shelf.encrypt_id','LEFT');
                  $this->db->join('lib_shelf_row  ','lib_barcode.shelf_row_id=lib_shelf_row.encrypt_id','LEFT');
                $this->db->join('lib_books','lib_barcode.book_id=lib_books.encrypt_id','LEFT');
                $this->db->join('students','students.student_qunique_id=Rb.reader_id','LEFT');
		$this->db->join('admin as badm','Rb.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','Rb.school_id=sadm.encrypt_id','LEFT');
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
	** Function name : SelectshelfListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select Subject List data
	** Date : 16 JANUARY 2018
	************************************************************************/
	function SelectshelfListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('sh.*, (SELECT COUNT(barcode_id) as count FROM `sms_lib_barcode`  WHERE shelf_id = sh.encrypt_id  AND STATUS = "Y") as total_books ,(SELECT COUNT(shelf_row)  FROM `sms_lib_shelf_row`  WHERE shelf_id = sh.encrypt_id  AND STATUS = "Y" ) AS total_rows');
		$this->db->from($tblName);
		
		$this->db->join('admin as badm','sh.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','sh.school_id=sadm.encrypt_id','LEFT');
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
	function SelectbookcopyListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('bk.*, shelf.shelf ,shelf_row.shelf_row ,bk.book_status  ');
		$this->db->from($tblName);
		
		$this->db->join('admin as badm','bk.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','bk.school_id=sadm.encrypt_id','LEFT');
                $this->db->join('lib_shelf AS shelf','bk.shelf_id = shelf.encrypt_id','LEFT');
                 $this->db->join('lib_shelf_row AS shelf_row','bk.shelf_row_id = shelf_row.encrypt_id','LEFT');
                
               
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
	** Function name : SelectbookcategoryListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select category List data
	** Date : 16 JANUARY 2018
	************************************************************************/
	function SelectbookcategoryListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('cat.*');
		$this->db->from($tblName);
		
		$this->db->join('admin as badm','cat.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','cat.school_id=sadm.encrypt_id','LEFT');
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
	** Function name : SelectshelfbookListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for select category List data
	** Date : 16 JANUARY 2018
	************************************************************************/
        function SelectshelfbookListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('bk.*, shelf.shelf ,shelf_row.shelf_row , book_status ,(SELECT book_name  FROM `sms_lib_books`  WHERE encrypt_id = bk.book_id     ) as book_name ,(SELECT book_author  FROM `sms_lib_books`  WHERE encrypt_id = bk.book_id     ) as book_author  ');
		$this->db->from($tblName);
		
		$this->db->join('admin as badm','bk.branch_id=badm.encrypt_id','LEFT');
		$this->db->join('admin as sadm','bk.school_id=sadm.encrypt_id','LEFT');
                $this->db->join('lib_shelf AS shelf','bk.shelf_id = shelf.encrypt_id','LEFT');
                 $this->db->join('lib_shelf_row AS shelf_row','bk.shelf_row_id = shelf_row.encrypt_id','LEFT');
                
               
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
	}
      
        
        
         /***********************************************************************
	** Function name : SelectissueLimitListData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for limit book issue for a reader at a time
	** Date : 16 JANUARY 2018
	************************************************************************/
	function SelectissueLimitListData($action='',$tblName='',$whereCon='',$shortField='',$numPage='',$cnt='')
	{ 
		$this->db->select('l.* ,lib_reader_type.reader_type');
		$this->db->from($tblName);
		
			$this->db->join('lib_reader_type','l.reader_type_id = lib_reader_type.encrypt_id','LEFT');
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
	** Function name : fineruledropdownData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for limit book issue for a reader at a time
	** Date : 16 JANUARY 2018
	************************************************************************/
	function fineruledropdownData($ruleID = '')
	{ 
		$subQuery             = "SELECT * FROM `sms_lib_fine_rule`  WHERE STATUS='Y'";
       $ALLFINERULE = $this->common_model->get_data_by_query('multiple', $subQuery);
       print_r($ALLFINERULE) ;
       
       $currentRuleQuery  =      "SELECT fine_rule_id FROM `sms_lib_fine`  WHERE  franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "'" ;
												 
       
      
        $currentRule = $this->common_model->get_data_by_query('multiple', $currentRuleQuery);
        print_r($currentRule);
        $key = array_search('TUFOT0oxS1VNQVI=', array_column($ALLFINERULE, 'encrypt_id'));
       
       
	}	// END OF FUNCTION
	
        
}	


?>