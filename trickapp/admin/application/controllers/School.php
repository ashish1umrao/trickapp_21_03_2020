<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class School extends CI_Controller {

	public function  __construct()  
	{ 
		parent:: __construct();
		$this->load->helper(array('form','url','html','path','form','cookie'));
		$this->load->library(array('email','session','form_validation','pagination','parser','encrypt'));
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->library("layouts");
		$this->load->model(array('admin_model','common_model','emailtemplate_model','sms_model'));
		$this->load->helper('language');
		$this->lang->load('statictext', 'admin');
	} 
	
	/* * *********************************************************************
	 * * Function name : school
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for school
	 * * Date : 12 JANUARY 2018 
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(adm.admin_name LIKE '%".$sValue."%' 
			                   					  OR adm.admin_display_name LIKE '%".$sValue."%' 
												  OR adm.admin_slug LIKE '%".$sValue."%' 
												  OR adm.admin_email_id LIKE '%".$sValue."%' 
												  OR adm.admin_mobile_number LIKE '%".$sValue."%' 
												  OR adm.admin_address LIKE '%".$sValue."%' 
												  OR adm.admin_locality LIKE '%".$sValue."%' 
												  OR adm.admin_city LIKE '%".$sValue."%' 
												  OR adm.admin_state LIKE '%".$sValue."%' 
												  OR adm.admin_zipcode LIKE '%".$sValue."%' 
												  OR adm.status LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"adm.admin_franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' AND adm.admin_type = 'School'";		
		$shortField 						= 	'adm.admin_name ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('schoolAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'admin as adm';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectAdminData('count',$tblName,$whereCon,$shortField,'0','0');
		
		if($this->input->get('showLength') == 'All'):
			$config['per_page']	 			= 	$config['total_rows'];
			$data['perpage'] 				= 	$this->input->get('showLength');  
		elseif($this->input->get('showLength')):
			$config['per_page']	 			= 	$this->input->get('showLength'); 
			$data['perpage'] 				= 	$this->input->get('showLength'); 
		else:
			$config['per_page']	 			= 	SHOW_NO_OF_DATA;
			$data['perpage'] 				= 	SHOW_NO_OF_DATA; 
		endif;
		
		$config['uri_segment'] = getUrlSegment();
       $this->pagination->initialize($config);

       if ($this->uri->segment(getUrlSegment())):
           $page = $this->uri->segment(getUrlSegment());
       else:
           $page = 0;
       endif;
		
		$data['forAction'] 					= 	$config['base_url']; 
		if($config['total_rows']):
			$first							=	($page)+1;
			$data['first']					=	$first;
			$last							=	(($page)+$data['perpage'])>$config['total_rows']?$config['total_rows']:(($page)+$data['perpage']);
			$data['noOfContent']			=	'Showing '.$first.'-'.$last.' of '.$config['total_rows'].' items';
		else:
			$data['first']					=	1;
			$data['noOfContent']			=	'';
		endif;
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectAdminData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage school details');
		$this->layouts->admin_view('school/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 12 JANUARY 2018
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{		
		$data['error'] 				= 	'';
		
		$boardQuery					=	"SELECT * FROM sms_boards WHERE status = 'Y'";  
		$data['BOARDDATA']			=	$this->common_model->get_data_by_query('multiple',$boardQuery); 
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['S_EDITDATA']		=	$this->common_model->get_data_by_encryptId('admin',$editid);
			
			$nQuery					=	"SELECT * FROM sms_admin WHERE admin_school_id = '".$editid."' ORDER BY admin_id ASC LIMIT 0,1";  
			$data['B_EDITDATA']		=	$this->common_model->get_data_by_query('single',$nQuery); 
			
			$BbQuery				=	"SELECT board_id FROM sms_branch_boards WHERE franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' AND school_id = '".$editid."' AND branch_id = '".$data['B_EDITDATA']['encrypt_id']."'";  
			$data['B_BOARDDATA']	=	$this->common_model->get_ids_in_array('board_id',$BbQuery); 
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			
			//////////////		SCHOOL SECTION		//////////////
			$this->form_validation->set_rules('s_admin_name', 'Name', 'trim|required');
			$this->form_validation->set_rules('s_admin_display_name', 'Display name', 'trim|required');
			$this->form_validation->set_rules('s_admin_slug', 'Slug url', 'trim|required|is_unique[admin.s_admin_slug]');
			$this->form_validation->set_rules('s_admin_email_id', 'E-Mail', 'trim|required|valid_email|is_unique[admin.s_admin_email_id]');
			if($this->input->post('s_new_password')!= ''):
				$this->form_validation->set_rules('s_new_password', 'New password', 'trim|required|min_length[6]|max_length[25]');
				$this->form_validation->set_rules('s_conf_password', 'Confirm password', 'trim|required|min_length[6]|matches[s_new_password]');
			endif;
			
			$this->form_validation->set_rules('s_admin_mobile_number', 'Mobile number', 'trim|required|min_length[10]|max_length[15]|is_unique[admin.s_admin_mobile_number]');
			$testmobile		=	str_replace(' ','',$this->input->post('s_admin_mobile_number'));
			if($this->input->post('s_admin_mobile_number') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testmobile)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testmobile)):
					$error						=	'YES';
					$data['s_mobileerror'] 		= 	'Please eneter correct number.';
				endif;
			endif;
			
			$this->form_validation->set_rules('s_admin_address', 'Address', 'trim|required');
			$this->form_validation->set_rules('s_admin_locality', 'Locality', 'trim|required');
			$this->form_validation->set_rules('s_admin_city', 'City', 'trim|required');
			$this->form_validation->set_rules('s_admin_state', 'State', 'trim|required');
			$this->form_validation->set_rules('s_admin_zipcode', 'Zipcode', 'trim|required');
			$this->form_validation->set_rules('uploadimage0', 'School logo', 'trim|required');
			$this->form_validation->set_rules('uploadimage1', 'School logo for APP', 'trim|required');
			
			//////////////		BRANCH SECTION		//////////////
			$this->form_validation->set_rules('branch_code', 'Branch code', 'trim|required|min_length[6]|max_length[6]|is_unique[admin.b_branch_code]');
			$this->form_validation->set_rules('b_admin_name', 'Name', 'trim|required');
			$this->form_validation->set_rules('b_admin_display_name', 'Display name', 'trim|required');
			$this->form_validation->set_rules('b_admin_slug', 'Slug url', 'trim|required|is_unique[admin.b_admin_slug]');
			$this->form_validation->set_rules('b_admin_email_id', 'E-Mail', 'trim|required|valid_email|is_unique[admin.b_admin_email_id]');
			if($this->input->post('b_new_password')!= ''):
				$this->form_validation->set_rules('b_new_password', 'New password', 'trim|required|min_length[6]|max_length[25]');
				$this->form_validation->set_rules('b_conf_password', 'Confirm password', 'trim|required|min_length[6]|matches[b_new_password]');
			endif;
			
			$this->form_validation->set_rules('b_admin_mobile_number', 'Mobile number', 'trim|required|min_length[10]|max_length[15]|is_unique[admin.b_admin_mobile_number]');
			$testmobile		=	str_replace(' ','',$this->input->post('b_admin_mobile_number'));
			if($this->input->post('b_admin_mobile_number') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testmobile)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testmobile)):
					$error						=	'YES';
					$data['b_mobileerror'] 		= 	'Please eneter correct number.';
				endif;
			endif;
			
			$this->form_validation->set_rules('b_admin_address', 'Address', 'trim|required');
			$this->form_validation->set_rules('b_admin_locality', 'Locality', 'trim|required');
			$this->form_validation->set_rules('b_admin_city', 'City', 'trim|required');
			$this->form_validation->set_rules('b_admin_state', 'State', 'trim|required');
			$this->form_validation->set_rules('b_admin_zipcode', 'Zipcode', 'trim|required');
			
			$this->form_validation->set_rules('board_details[]', 'Board details', 'trim|required');
			
			if($this->form_validation->run()):
				if($this->input->post('s_admin_slug') == $this->input->post('b_admin_slug')):
					$error						=	'YES';
					$data['b_slugerror'] 		= 	'Please enter different slug url from upper slug url.';
				endif;
				if($this->input->post('s_admin_email_id') == $this->input->post('b_admin_email_id')):
					$error						=	'YES';
					$data['b_emailerror'] 		= 	'Please enter different email from upper email.';
				endif;
				if($this->input->post('s_admin_mobile_number') == $this->input->post('b_admin_mobile_number')):
					$error						=	'YES';
					$data['b_mobileerror'] 		= 	'Please enter different mobile from upper mobile.';
				endif;
			endif;
		
			if($this->form_validation->run() && $error == 'NO'): 	
			
			 	//////////////		SCHOOL SECTION		//////////////
				$param['admin_name']			= 	addslashes($this->input->post('s_admin_name'));
				$param['admin_display_name']	= 	addslashes($this->input->post('s_admin_display_name'));
				$param['admin_slug']			= 	url_title(addslashes($this->input->post('s_admin_slug')));
				$param['admin_email_id']		= 	addslashes($this->input->post('s_admin_email_id'));
				
				if($this->input->post('s_new_password')):
					$NewPassword				=	$this->input->post('s_new_password');
					$param['admin_password']	= 	$this->admin_model->encript_password($NewPassword);
				endif;
				
				$param['admin_mobile_number']	= 	addslashes($this->input->post('s_admin_mobile_number'));
				$param['admin_address']			= 	addslashes($this->input->post('s_admin_address'));
				$param['admin_locality']		= 	addslashes($this->input->post('s_admin_locality'));
				$param['admin_city']			= 	addslashes($this->input->post('s_admin_city'));
				$param['admin_state']			= 	addslashes($this->input->post('s_admin_state'));
				$param['admin_zipcode']			= 	addslashes($this->input->post('s_admin_zipcode'));
				$param['admin_image']			= 	addslashes($this->input->post('uploadimage0'));
				$param['admin_image_app']		= 	addslashes($this->input->post('uploadimage1'));
				
				if($this->input->post('CurrentDataID') ==''):
					$param['admin_type']		=	'School';
					$param['admin_level']		=	2;
					$param['admin_franchise_id']=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['creation_ip']		=	currentIp();
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'A';
					$lastInsertId				=	$this->common_model->add_data('admin',$param);
					
					$Uparam['encrypt_id']		=	manojEncript($lastInsertId);
					$Uwhere['admin_id']			=	$lastInsertId;
					$this->common_model->edit_data_by_multiple_cond('admin',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
					
					$schoolId					=	$Uparam['encrypt_id'];
				else:
					$schoolId					=	$this->input->post('CurrentDataID');
					$param['update_ip']			=	currentIp();
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('admin',$param,$schoolId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				//////////////		BRANCH SECTION		//////////////
				if($schoolId):
					$bparam['branch_code']			= 	addslashes($this->input->post('branch_code'));
					$bparam['admin_name']			= 	addslashes($this->input->post('b_admin_name'));
					$bparam['admin_display_name']	= 	addslashes($this->input->post('b_admin_display_name'));
					$bparam['admin_slug']			= 	url_title(addslashes($this->input->post('b_admin_slug')));
					$bparam['admin_email_id']		= 	addslashes($this->input->post('b_admin_email_id'));
					
					if($this->input->post('b_new_password')):
						$NewPassword				=	$this->input->post('b_new_password');
						$bparam['admin_password']	= 	$this->admin_model->encript_password($NewPassword);
					endif;
					
					$bparam['admin_mobile_number']	= 	addslashes($this->input->post('b_admin_mobile_number'));
					$bparam['admin_address']		= 	addslashes($this->input->post('b_admin_address'));
					$bparam['admin_locality']		= 	addslashes($this->input->post('b_admin_locality'));
					$bparam['admin_city']			= 	addslashes($this->input->post('b_admin_city'));
					$bparam['admin_state']			= 	addslashes($this->input->post('b_admin_state'));
					$bparam['admin_zipcode']		= 	addslashes($this->input->post('b_admin_zipcode'));
					
					if($this->input->post('user_id') ==''):
						$bparam['admin_type']		=	'Branch';
						$bparam['admin_level']		=	3;
						$bparam['admin_franchise_id']=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
						$bparam['admin_school_id']	=	$schoolId;
						$bparam['creation_ip']		=	currentIp();
						$bparam['creation_date']	=	currentDateTime();
						$bparam['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
						$bparam['status']			=	'A';
						$blastInsertId				=	$this->common_model->add_data('admin',$bparam);
						
						$bUparam['encrypt_id']		=	manojEncript($blastInsertId);
						$bUwhere['admin_id']		=	$blastInsertId;
						$this->common_model->edit_data_by_multiple_cond('admin',$bUparam,$bUwhere);
						
						$branchId					=	$bUparam['encrypt_id'];
					else:
						$branchId					=	$this->input->post('user_id');
						$bparam['update_ip']		=	currentIp();
						$bparam['update_date']		=	currentDateTime();
						$bparam['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
						$this->common_model->edit_data('admin',$bparam,$branchId);
					endif;
				endif;
				
				//////////////		BOARD SECTION		//////////////
				$firstboardId						=	'';
				$firstboardName						=	'';
				if($schoolId && $branchId):
					if($this->input->post('board_details')):
						$DbBUwhere['franchise_id']	=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
						$DbBUwhere['school_id']		=	$schoolId;
						$DbBUwhere['branch_id']		=	$branchId;
						$this->common_model->delete_by_multiple_cond('sms_branch_boards',$DbBUwhere);
					
						$boarddata					=	$this->input->post('board_details');
						foreach($boarddata as $boardinfo):
							if($boardinfo):
								$boarddatass		=	explode('_____',$boardinfo); 
								
								$bBparam['board_id']			= 	addslashes($boarddatass[0]);
								$bBparam['branch_board_name']	= 	addslashes($boarddatass[1]);
								$bBparam['branch_board_short_name']= 	addslashes($boarddatass[2]);

								$bBparam['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
								$bBparam['school_id']			=	$schoolId;
								$bBparam['branch_id']			=	$branchId;
								$bBparam['creation_date']		=	currentDateTime();
								$bBparam['created_by']			=	$this->session->userdata('SMS_ADMIN_ID');
								$bBparam['status']				=	'Y';
								$bBlastInsertId					=	$this->common_model->add_data('sms_branch_boards',$bBparam);
								
								$bBUparam['encrypt_id']			=	manojEncript($bBlastInsertId);
								$bBUwhere['branch_board_id']	=	$bBlastInsertId;
								$this->common_model->edit_data_by_multiple_cond('sms_branch_boards',$bBUparam,$bBUwhere);
								if($firstboardId == ''):
									$firstboardId				=	$bBparam['board_id'];
								endif;
								if($firstboardName == ''):
									$firstboardName				=	$bBparam['branch_board_name'];
								endif;
							endif;
						endforeach;
					endif;
				endif;
				
				if(!$this->session->userdata('SMS_ADMIN_SCHOOL_ID') || !$this->session->userdata('SMS_ADMIN_BRANCH_ID') || !$this->session->userdata('SMS_ADMIN_BOARD_ID')):
					$this->session->set_userdata(array(
												'SMS_ADMIN_SCHOOL_ID'=>$schoolId,
												'SMS_ADMIN_SCHOOL_NAME'=>$param['admin_name'],
												'SMS_ADMIN_SCHOOL_DIS_NAME'=>$param['admin_display_name'],
												'SMS_ADMIN_SCHOOL_SLUG'=>$param['admin_slug'],
												'SMS_ADMIN_BRANCH_ID'=>$branchId,
												'SMS_ADMIN_BRANCH_NAME'=>$bparam['admin_name'],
												'SMS_ADMIN_BRANCH_DIS_NAME'=>$bparam['admin_display_name'],
												'SMS_ADMIN_BRANCH_SLUG'=>$bparam['admin_slug'],
												'SMS_ADMIN_BOARD_ID'=>$firstboardId,
												'SMS_ADMIN_BOARD_NAME'=>$firstboardName));
				endif;
				
				redirect(correctLink('schoolAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit school details');
		$this->layouts->admin_view('school/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 13 JANUARY 2018
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('admin',$param,$changeStatusId);
		
		if($statusType == "A"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		elseif($statusType == "I"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		elseif($statusType == "B"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		elseif($statusType == "D"):
			$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		endif;
		
		redirect(correctLink('schoolAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
	
	/***********************************************************************
	** Function name : uplode_image
	** Developed By : Manoj Kumar
	** Purpose  : This function used uplode image
	** Date : 29 JANUARY 2018
	************************************************************************/
	function uplode_image()
	{ 
		$file_name					= 	$_FILES['uploadfile']['name'];
		if($file_name): 
			$tmp_name				= 	$_FILES['uploadfile']['tmp_name'];		
			$imageInfo 				= 	@getimagesize($_FILES['uploadfile']['tmp_name']);
			if(($imageInfo[0] >= 192 || $imageInfo[0] <= 250) && ($imageInfo[1] >= 192 || $imageInfo[1] <= 250)):
				$imagefolder			=	'S_'.manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID'));
				$ext 					= 	pathinfo($file_name);
				$newfilename			=	time().'.'.$ext['extension'];
				$this->load->library("upload_crop_img");
				$return_file_name	=	$this->upload_crop_img->_upload_image($file_name,$tmp_name,'schoolImage',$newfilename,$imagefolder);
				echo $return_file_name; die;
			else:
				echo 'ERROR_____Please follow image ratio.'; die;
			endif;
		else:
			echo 'UPLODEERROR'; die;
		endif;
	}
	
	/***********************************************************************
	** Function name : DeleteImage
 	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete image by ajax.
	** Date : 29 NOVEMBER 2017
	************************************************************************/
	function DeleteImage()
	{  
		$imagename	=	$this->input->post('imagename'); 
		if($imagename):
			$this->load->library("upload_crop_img");
			$return	=	$this->upload_crop_img->_delete_image($imagename); 
		endif;
		echo '1'; die;
	}	
	
	/***********************************************************************
	** Function name : get_view_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get view data.
	** Date : 13 JANUARY 2018
	************************************************************************/
	function get_view_data()
	{
		$html					=	'';
		if($this->input->post('viewid')):
			$viewId				=	$this->input->post('viewid'); 
			$viewQuery			=	"SELECT adm.*
									 FROM sms_admin as adm
									 WHERE adm.encrypt_id = '".$viewId."' AND adm.admin_type = 'School'";
			$viewData			= 	$this->common_model->get_data_by_query('single',$viewQuery);  
			if($viewData <> ""):
				$bQuery			=	"SELECT * FROM sms_admin WHERE admin_school_id = '".$viewId."' AND admin_type = 'Branch' ORDER BY admin_name ASC ";  
				$branchViewData	=	$this->common_model->get_data_by_query('multiple',$bQuery); 
			
				$html			.=	'<table class="table border-none">
								  <tbody>';
				$html			.=	'<tr>
									  <td align="left" width="30%"><strong>Name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_name']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Display name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_display_name']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Slug url</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_slug']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Email id</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_email_id']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Mobile number</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_mobile_number']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Address</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_address']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Locality</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_locality']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>City</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_city']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>State</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_state']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Zipcode</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_zipcode']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>School logo</strong></td>
									  <td align="left" width="70%"><img src="'.stripslashes($viewData['admin_image']).'" alt="School logo"></td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>School logo for APP</strong></td>
									  <td align="left" width="70%"><img src="'.stripslashes($viewData['admin_image_app']).'" alt="School logo for APP"></td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Status</strong></td>
									  <td align="left" width="70%">'.showStatus($viewData['status']).'</td>
									</tr>';
			if($branchViewData <> ""): 
				$html			.=	'<tr>
									  <td align="left" colspan="2">
									    <table class="table border-none">
								  		  <thead>
										    <tr>
											  <th align="left" width="40%">Branch name</th>
											  <th align="left" width="40%">Display name</th>
											  <th align="left" width="20%">--</th>
											</tr>
										  </thead>
										  <tbody>';
					foreach($branchViewData as $branchViewInfo):
						$html			.=	'<tr>
											  <td align="left"width="40%">'.$branchViewInfo['admin_name'].'</td>
											  <td align="left"width="40%">'.$branchViewInfo['admin_display_name'].'</td>
											  <td align="left"width="20%"><a href="javascript:void(0);" class="view-sub-details-data" title="Branch details" data-id="'.$branchViewInfo['encrypt_id'].'"><i class="fa fa-gear"></i>View details</a></td>
											</tr>';
					endforeach;
				$html			.=	'</tbody>
										</table>
									  </td>
									</tr>';
			endif;
				$html			.=	'</tbody>
								</table>';
			endif;
		endif;
		echo $html; die;
	}
	
	/***********************************************************************
	** Function name : get_view_sub_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get view sub data.
	** Date : 13 JANUARY 2018
	************************************************************************/
	function get_view_sub_data()
	{
		$html					=	'';
		if($this->input->post('viewid')):
			$viewId				=	$this->input->post('viewid'); 
			$viewQuery			=	"SELECT adm.*
									 FROM sms_admin as adm
									 WHERE adm.encrypt_id = '".$viewId."' AND adm.admin_type = 'Branch'";
			$viewData			= 	$this->common_model->get_data_by_query('single',$viewQuery);  
			if($viewData <> ""):
				
				$boardQuery		=	"SELECT *
									 FROM sms_branch_boards
									 WHERE franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' AND branch_id = '".$viewData['encrypt_id']."'";
				$boardData		= 	$this->common_model->get_data_by_query('multiple',$boardQuery);  
				
				$html			.=	'<table class="table border-none">
								  <tbody>';
				$html			.=	'<tr>
									  <td align="left" width="30%"><strong>Branch code</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['branch_code']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_name']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Display name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_display_name']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Slug url</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_slug']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Email id</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_email_id']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Mobile number</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_mobile_number']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Address</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_address']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Locality</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_locality']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>City</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_city']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>State</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_state']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Zipcode</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_zipcode']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Status</strong></td>
									  <td align="left" width="70%">'.showStatus($viewData['status']).'</td>
									</tr>';
				if($boardData <> ""): 
					$html			.=	'<tr>
										  <td align="left" colspan="2">
											<table class="table border-none">
											  <thead>
												<tr>
												  <th align="left">Board details</th>
												</tr>
											  </thead>
											  <tbody>';
						foreach($boardData as $boardInfo):
							$html			.=	'<tr>
												  <td align="left">'.$boardInfo['branch_board_name'].'</td>
												</tr>';
						endforeach;
					$html			.=	'</tbody>
											</table>
										  </td>
										</tr>';
				endif;
				$html			.=	'</tbody>
								</table>';
			endif;
		endif;
		echo $html; die;
	}
}
