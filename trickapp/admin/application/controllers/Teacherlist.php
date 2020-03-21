<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacherlist extends CI_Controller {

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
	 * * Function name : Teacherlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for Teacherlist
	 * * Date : 18 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(user.user_f_name LIKE '%".$sValue."%' 
			                   					  OR user.user_m_name LIKE '%".$sValue."%' 
												  OR user.user_l_name LIKE '%".$sValue."%' 
												  OR user.user_email LIKE '%".$sValue."%' 
												  OR user.user_phone LIKE '%".$sValue."%' 
												  OR user.user_emer_phone LIKE '%".$sValue."%' 
												  OR user.user_mobile LIKE '%".$sValue."%' 
												  OR user.user_emer_mobile LIKE '%".$sValue."%' 
												  OR userd.user_gender LIKE '%".$sValue."%' 
												  OR userd.user_marital_status LIKE '%".$sValue."%' 
												  OR userd.user_religion LIKE '%".$sValue."%' 
												  OR userd.user_blood_group LIKE '%".$sValue."%' 
												  OR userd.user_nationality LIKE '%".$sValue."%' 
												  OR usera.user_c_address LIKE '%".$sValue."%' 
												  OR usera.user_c_locality LIKE '%".$sValue."%' 
												  OR usera.user_c_city LIKE '%".$sValue."%' 
												  OR usera.user_c_state LIKE '%".$sValue."%' 
												  OR usera.user_c_zipcode LIKE '%".$sValue."%'
												  OR usera.user_p_address LIKE '%".$sValue."%' 
												  OR usera.user_p_locality LIKE '%".$sValue."%' 
												  OR usera.user_p_city LIKE '%".$sValue."%' 
												  OR usera.user_p_state LIKE '%".$sValue."%' 
												  OR usera.user_p_zipcode LIKE '%".$sValue."%' 
												  OR userk.user_adhar_no LIKE '%".$sValue."%' 
												  OR userk.user_pan_no LIKE '%".$sValue."%' 
												  OR userk.user_account_no LIKE '%".$sValue."%' 
												  OR userk.user_bank_name LIKE '%".$sValue."%' 
												  OR subj.subject_name LIKE '%".$sValue."%'
												  OR subj.subject_short_name LIKE '%".$sValue."%'
												  OR user.status LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"user.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."'
												 AND user.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
												 AND user.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' AND user.user_type = 'Teacher'";		
		$shortField 						= 	'user.user_f_name ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('teacherListAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'users as user';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectTeacherListData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectTeacherListData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage teacher details');
		$this->layouts->admin_view('teacherlist/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 19 JANUARY 2018
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{	$data['userId'] =  	$editid ;			
		$data['error'] 				= 	'';
		
		$subQuery					=	"SELECT sub.encrypt_id, sub.subject_name, bord.board_name 
										FROM sms_subject as sub
										LEFT JOIN sms_boards as bord ON sub.board_id=bord.encrypt_id
									 	WHERE sub.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
										AND sub.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
										AND sub.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'"; 
		$data['SUBJECTDATA']		=	$this->common_model->get_data_by_query('multiple',$subQuery);  
		$genQuery					=	"SELECT user_gender_name, user_gender_short_name FROM sms_user_gender WHERE status = 'Y'";
		$data['GENDERDATA']			=	$this->common_model->get_data_by_query('multiple',$genQuery);
		$mstaQuery					=	"SELECT user_merital_name, user_merital_short_name FROM sms_user_merital_status WHERE status = 'Y'";
		$data['MERITALDATA']		=	$this->common_model->get_data_by_query('multiple',$mstaQuery); 
		$RERIQuery					=	"SELECT user_religion_name FROM sms_user_religion WHERE status = 'Y'";
		$data['RERIGIONDATA']		=	$this->common_model->get_data_by_query('multiple',$RERIQuery); 
		$StateQuery					=	"SELECT state FROM sms_country WHERE county = 'India' GROUP BY state";
		$data['STATEDATA']			=	$this->common_model->get_data_by_query('multiple',$StateQuery); 
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('users',$editid);
			$subSeQuery				=	"SELECT subject_id FROM sms_teacher_subject WHERE teacher_id = '".$editid."'";
			$data['SUBJECTIDS']		=	$this->common_model->get_ids_in_array('subject_id',$subSeQuery);
			
			$uDeQuery				=	"SELECT user_gender,user_gender_short,user_marital_status,user_marital_short,user_blood_group,user_religion,user_nationality,
			                             user_dob,user_joining_date,user_pickup,user_drop,user_pic 
			                             FROM sms_user_detils WHERE user_id = '".$editid."'";
			$userDetails			=	$this->common_model->get_data_by_query('single',$uDeQuery);
			if($userDetails <> ""):		
				$data['EDITDATA']	=	array_merge($data['EDITDATA'],$userDetails);	
			endif;
			
			$uAddQuery				=	"SELECT user_c_address,user_c_locality,user_c_city,user_c_state,user_c_zipcode,same_address,
			                             user_p_address,user_p_locality,user_p_city,user_p_state,user_p_zipcode 
			                             FROM sms_user_address WHERE user_id = '".$editid."'";
			$userAddress			=	$this->common_model->get_data_by_query('single',$uAddQuery);
			if($userAddress <> ""):		
				$data['EDITDATA']	=	array_merge($data['EDITDATA'],$userAddress);	
			endif;
			
			$uKycQuery				=	"SELECT user_adhar_no,user_pan_no,user_account_no,user_name_on_account,user_bank_name,user_ifsc_code 
									     FROM sms_user_kyc WHERE user_id = '".$editid."'";
			$userKyc				=	$this->common_model->get_data_by_query('single',$uKycQuery);
			if($userKyc <> ""):		
				$data['EDITDATA']	=	array_merge($data['EDITDATA'],$userKyc);	
			endif;
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			
			//////////////	Teacher Details
			$this->form_validation->set_rules('subject_id[]', 'Subject', 'trim|required');
			$this->form_validation->set_rules('employee_id', 'Employee id', 'trim|is_unique[users.u_employee_id]');
			$this->form_validation->set_rules('user_f_name', 'First name', 'trim|required');
			$this->form_validation->set_rules('user_m_name', 'Middle name', 'trim');
			$this->form_validation->set_rules('user_l_name', 'last name', 'trim');
			$this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|is_unique[users.u_user_email]');
			if($this->input->post('new_password')!= ''):
				$this->form_validation->set_rules('new_password', 'New password', 'trim|required|min_length[6]|max_length[25]');
				$this->form_validation->set_rules('conf_password', 'Confirm password', 'trim|required|min_length[6]|matches[new_password]');
			endif;
			
			$this->form_validation->set_rules('user_phone', 'Phone1', 'trim|min_length[10]|max_length[15]|is_unique[users.u_user_phone]');
			$testphone1		=	str_replace(' ','',$this->input->post('user_phone'));
			if($this->input->post('user_phone') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testphone1)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testphone1)):
					$error						=	'YES';
					$data['phone1error'] 		= 	'Please eneter correct number.';
				endif;
			endif;
			
			$this->form_validation->set_rules('user_emer_phone', 'Phone2', 'trim|min_length[10]|max_length[15]|is_unique[users.u_user_emer_phone]');
			$testphone2		=	str_replace(' ','',$this->input->post('user_emer_phone'));
			if($this->input->post('user_emer_phone') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testphone2)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testphone2)):
					$error						=	'YES';
					$data['phone2error'] 		= 	'Please eneter correct number.';
				endif;
			endif;
			
			$this->form_validation->set_rules('user_mobile', 'Mobile1', 'trim|required|min_length[10]|max_length[15]|is_unique[users.u_user_mobile]');
			$testmobile1		=	str_replace(' ','',$this->input->post('user_mobile'));
			if($this->input->post('user_mobile') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testmobile1)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testmobile1)):
					$error						=	'YES';
					$data['mobile1error'] 		= 	'Please eneter correct number.';
				endif;
			endif;
			
			$this->form_validation->set_rules('user_emer_mobile', 'Mobile2', 'trim|min_length[10]|max_length[15]|is_unique[users.u_user_emer_mobile]');
			$testmobile2		=	str_replace(' ','',$this->input->post('user_emer_mobile'));
			if($this->input->post('user_emer_mobile') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testmobile2)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testmobile2)):
					$error						=	'YES';
					$data['mobile2error'] 		= 	'Please eneter correct number.';
				endif;
			endif;
			
			$this->form_validation->set_rules('user_gender', 'Gender', 'trim|required');
			$this->form_validation->set_rules('user_marital_status', 'Marital status', 'trim|required');
			$this->form_validation->set_rules('user_blood_group', 'Blood group', 'trim');
			$this->form_validation->set_rules('user_religion', 'Religion', 'trim');
			$this->form_validation->set_rules('user_nationality', 'Nationality', 'trim');
			
			//////////////	Teacher's Correspondence Address
			$this->form_validation->set_rules('user_c_state', 'State', 'trim');
			$this->form_validation->set_rules('user_c_city', 'City', 'trim');
			$this->form_validation->set_rules('user_c_locality', 'Locality', 'trim');
			$this->form_validation->set_rules('user_c_address', 'Address', 'trim');
			$this->form_validation->set_rules('user_c_zipcode', 'Zip code', 'trim');
			
			//////////////	Permanent Address
			$this->form_validation->set_rules('same_address', 'Same address', 'trim');
			$this->form_validation->set_rules('user_p_state', 'State', 'trim');
			$this->form_validation->set_rules('user_p_city', 'City', 'trim');
			$this->form_validation->set_rules('user_p_locality', 'Locality', 'trim');
			$this->form_validation->set_rules('user_p_address', 'Address', 'trim');
			$this->form_validation->set_rules('user_p_zipcode', 'Zip code', 'trim');
			
			//////////////	KYC details
			$this->form_validation->set_rules('user_adhar_no', 'Adhar number', 'trim');
			$this->form_validation->set_rules('user_pan_no', 'Pan number', 'trim');
			$this->form_validation->set_rules('user_account_no', 'Account number', 'trim');
			$this->form_validation->set_rules('user_name_on_account', 'Name on account', 'trim');
			$this->form_validation->set_rules('user_bank_name', 'Bank name', 'trim');
			$this->form_validation->set_rules('user_ifsc_code', 'IFSC code', 'trim');
			
			//////////////	Other Details
			$this->form_validation->set_rules('user_dob', 'Date of birth', 'trim');
			$this->form_validation->set_rules('user_joining_date', 'Date of joining', 'trim');
			$this->form_validation->set_rules('user_pickup', 'Pickup location', 'trim');
			$this->form_validation->set_rules('user_drop', 'Drop location', 'trim');
			$this->form_validation->set_rules('uploadimage0', 'Profile picture', 'trim');
			 //echo '<pre>'; print_r($_POST); die;
			if($this->form_validation->run() && $error == 'NO'):  //echo '<pre>'; print_r($_POST); die;
			
				//////////////	Teacher table
				$Tparam['employee_id']			= 	addslashes($this->input->post('employee_id'));
				$Tparam['user_f_name']			= 	addslashes($this->input->post('user_f_name'));
				$Tparam['user_m_name']			= 	addslashes($this->input->post('user_m_name'));
				$Tparam['user_l_name']			= 	addslashes($this->input->post('user_l_name'));
				$Tparam['user_email']			= 	addslashes($this->input->post('user_email'));
				
				if($this->input->post('new_password')):
					$NewPassword				=	$this->input->post('new_password');
					$Tparam['user_password']	= 	$this->admin_model->encript_password($NewPassword);
				endif;
				
				$Tparam['user_phone']			= 	addslashes($this->input->post('user_phone'));
				$Tparam['user_emer_phone']		= 	addslashes($this->input->post('user_emer_phone'));
				$Tparam['user_mobile']			= 	addslashes($this->input->post('user_mobile'));
				$Tparam['user_emer_mobile']		= 	addslashes($this->input->post('user_emer_mobile'));
				
				//////////////	Teacher Details table
				$user_gender					=	$this->input->post('user_gender')?explode('___',$this->input->post('user_gender')):array();
				$TDparam['user_gender']			= 	$user_gender[0]?$user_gender[0]:'';
				$TDparam['user_gender_short']	= 	$user_gender[1]?$user_gender[1]:'';
				$user_marital					=	$this->input->post('user_marital_status')?explode('___',$this->input->post('user_marital_status')):array();
				$TDparam['user_marital_status']	= 	$user_marital[0]?$user_marital[0]:'';
				$TDparam['user_marital_short']	= 	$user_marital[1]?$user_marital[1]:'';
				$TDparam['user_blood_group']	= 	addslashes($this->input->post('user_blood_group'));
				$TDparam['user_religion']		= 	addslashes($this->input->post('user_religion'));
				$TDparam['user_nationality']	= 	addslashes($this->input->post('user_nationality'));
				$TDparam['user_dob']			= 	DDMMYYtoYYMMDD($this->input->post('user_dob'));
				$TDparam['user_joining_date']	= 	DDMMYYtoYYMMDD($this->input->post('user_joining_date'));
				$TDparam['user_pickup']			= 	addslashes($this->input->post('user_pickup'));
				$TDparam['user_drop']			= 	addslashes($this->input->post('user_drop'));
				$TDparam['user_pic']			= 	addslashes($this->input->post('uploadimage0'));
				
				//////////////	Teacher Address table
				$TAparam['user_c_state']		= 	addslashes($this->input->post('user_c_state'));
				$TAparam['user_c_city']			= 	addslashes($this->input->post('user_c_city'));
				$TAparam['user_c_locality']		= 	addslashes($this->input->post('user_c_locality'));
				$TAparam['user_c_address']		= 	addslashes($this->input->post('user_c_address'));
				$TAparam['user_c_zipcode']		= 	addslashes($this->input->post('user_c_zipcode'));
				$TAparam['same_address']		= 	$this->input->post('same_address')?'Y':'N';
				$TAparam['user_p_state']		= 	addslashes($this->input->post('user_p_state'));
				$TAparam['user_p_city']			= 	addslashes($this->input->post('user_p_city'));
				$TAparam['user_p_locality']		= 	addslashes($this->input->post('user_p_locality'));
				$TAparam['user_p_address']		= 	addslashes($this->input->post('user_p_address'));
				$TAparam['user_p_zipcode']		= 	addslashes($this->input->post('user_p_zipcode'));
				
				//////////////	Teacher Kyc table
				$TKparam['user_adhar_no']		= 	addslashes($this->input->post('user_adhar_no'));
				$TKparam['user_pan_no']			= 	addslashes($this->input->post('user_pan_no'));
				$TKparam['user_account_no']		= 	addslashes($this->input->post('user_account_no'));
				$TKparam['user_name_on_account']= 	addslashes($this->input->post('user_name_on_account'));
				$TKparam['user_bank_name']		= 	addslashes($this->input->post('user_bank_name'));
				$TKparam['user_ifsc_code']		= 	addslashes($this->input->post('user_ifsc_code'));
				
				if($this->input->post('CurrentDataID') ==''):
					//////////////	Teacher table
					$Tparam['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$Tparam['school_id']		=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$Tparam['branch_id']		=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$Tparam['user_type']		=	'Teacher';
					$Tparam['creation_date']	=	currentDateTime();
					$Tparam['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$Tparam['status']			=	'A';
                                            $Tparam['session_year']			=	CURRENT_SESSION;
					$ulastInsertId				=	$this->common_model->add_data('users',$Tparam);
					
					$TUparam['encrypt_id']		=	manojEncript($ulastInsertId);
					$TUwhere['user_id']			=	$ulastInsertId;
					$this->common_model->edit_data_by_multiple_cond('users',$TUparam,$TUwhere);
					
					$userId						=	$TUparam['encrypt_id'];
					
					//////////////	Teacher Details table
					$TDparam['user_id']			=	$userId;
					$TDparam['creation_date']	=	currentDateTime();
					$TDparam['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$TDparam['status']			=	'Y';
                                            $TDparam['session_year']			=	CURRENT_SESSION;
					$udlastInsertId				=	$this->common_model->add_data('user_detils',$TDparam);
					
					$TDUparam['encrypt_id']		=	manojEncript($udlastInsertId);
					$TDUwhere['user_detail_id']	=	$udlastInsertId;
					$this->common_model->edit_data_by_multiple_cond('user_detils',$TDUparam,$TDUwhere);
					
					//////////////	Teacher Address table
					$TAparam['user_id']			=	$userId;
					$TAparam['creation_date']	=	currentDateTime();
					$TAparam['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$TAparam['status']			=	'Y';
                                                        $TAparam['session_year']			=	CURRENT_SESSION;
					$ualastInsertId				=	$this->common_model->add_data('user_address',$TAparam);
					
					$TAUparam['encrypt_id']		=	manojEncript($ualastInsertId);
					$TAUwhere['user_address_id']=	$ualastInsertId;
					$this->common_model->edit_data_by_multiple_cond('user_address',$TAUparam,$TAUwhere);
					
					//////////////	Teacher Teacher Kyc table
					$TKparam['user_id']			=	$userId;
					$TKparam['creation_date']	=	currentDateTime();
					$TKparam['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$TKparam['status']			=	'Y';
                                           $TKparam['session_year']			=	CURRENT_SESSION;
					$uklastInsertId				=	$this->common_model->add_data('user_kyc',$TKparam);
					
					$TKUparam['encrypt_id']		=	manojEncript($uklastInsertId);
					$TKUwhere['user_kyc_id']	=	$uklastInsertId;
					$this->common_model->edit_data_by_multiple_cond('user_kyc',$TKUparam,$TKUwhere);
					
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$userId						=	$this->input->post('CurrentDataID');
					
					//////////////	Teacher table
					$Tparam['update_date']		=	currentDateTime();
					$Tparam['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('users',$Tparam,$userId);
					
					//////////////	Teacher Details table
					$TDparam['creation_date']	=	currentDateTime();
					$TDparam['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					
					$TDUwhere['user_id']		=	$userId;
					$this->common_model->edit_data_by_multiple_cond('user_detils',$TDparam,$TDUwhere);
					
					//////////////	Teacher Address table
					$TAparam['creation_date']	=	currentDateTime();
					$TAparam['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					
					$TAUwhere['user_id']		=	$userId;
					$this->common_model->edit_data_by_multiple_cond('user_address',$TAparam,$TAUwhere);
					
					//////////////	Teacher Teacher Kyc table
					$TKparam['creation_date']	=	currentDateTime();
					$TKparam['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					
					$TKUwhere['user_id']		=	$userId;
					$this->common_model->edit_data_by_multiple_cond('user_kyc',$TKparam,$TKUwhere);
					
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				if($this->input->post('subject_id') && $userId):
					$subjectid					=	$this->input->post('subject_id');
					$this->common_model->delete_particular_data('teacher_subject','teacher_id',$userId);
					foreach($subjectid as $subids):
						$TSparam['teacher_id']		=	$userId;
						$TSparam['subject_id']		=	$subids;
						$TSparam['creation_date']	=	currentDateTime();
						$TSparam['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
						$TSparam['status']			=	'Y';
                                                 $TSparam['session_year']			=	CURRENT_SESSION;
						$uslastInsertId				=	$this->common_model->add_data('teacher_subject',$TSparam);
						
						$TSUparam['encrypt_id']		=	manojEncript($uslastInsertId);
						$TSUwhere['teacher_subject_id']	=	$uslastInsertId;
						$this->common_model->edit_data_by_multiple_cond('teacher_subject',$TSUparam,$TSUwhere);
					endforeach;
				endif;				
				
				 redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditdata/' . $userId);
			endif;
		endif;
		
		$this->layouts->set_title('Edit teacher details');
		$this->layouts->admin_view('teacherlist/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 19 JANUARY 2018
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('users',$param,$changeStatusId);
		
		if($statusType == "A"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		elseif($statusType == "I"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		elseif($statusType == "B"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		elseif($statusType == "D"):
			$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		endif;
		
		redirect(correctLink('teacherListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
	
	/***********************************************************************
	** Function name : get_view_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get view data.
	** Date : 18 JANUARY 2018
	************************************************************************/
	function get_view_data()
	{
		$html					=	'';
		if($this->input->post('viewid')): 
			$viewId				=	$this->input->post('viewid'); 
			$viewData			=	$this->common_model->get_data_by_encryptId('users',$viewId);
			if($viewData <> ""):
				$subSeQuery		=	"SELECT s.subject_name FROM sms_teacher_subject as ts LEFT JOIN sms_subject as s ON ts.subject_id=s.encrypt_id WHERE teacher_id = '".$viewId."'";
				$SUBJECTIDS		=	$this->common_model->get_data_by_query('multiple',$subSeQuery);
				$subjectName	=	'';
				if($SUBJECTIDS <> ""): $i=0;
					foreach($SUBJECTIDS as $SUBJECTdataS):
						$subjectName	.=	$i>0?', ':'';
						$subjectName	.=	$SUBJECTdataS['subject_name'];
						$i++;
					endforeach;
				endif;
				
				$uDeQuery				=	"SELECT user_gender,user_gender_short,user_marital_status,user_marital_short,user_blood_group,user_religion,user_nationality,
			                             	user_dob,user_joining_date,user_pickup,user_drop,user_pic 
			                             	FROM sms_user_detils WHERE user_id = '".$viewId."'";
				$userDetails			=	$this->common_model->get_data_by_query('single',$uDeQuery);
				if($userDetails <> ""):		
					$viewData			=	array_merge($viewData,$userDetails);	
				endif;
				
				$uAddQuery				=	"SELECT user_c_address,user_c_locality,user_c_city,user_c_state,user_c_zipcode,same_address,
											 user_p_address,user_p_locality,user_p_city,user_p_state,user_p_zipcode 
											 FROM sms_user_address WHERE user_id = '".$viewId."'";
				$userAddress			=	$this->common_model->get_data_by_query('single',$uAddQuery);
				if($userAddress <> ""):		
					$viewData			=	array_merge($viewData,$userAddress);	
				endif;
				
				$uKycQuery				=	"SELECT user_adhar_no,user_pan_no,user_account_no,user_name_on_account,user_bank_name,user_ifsc_code 
											 FROM sms_user_kyc WHERE user_id = '".$viewId."'";
				$userKyc				=	$this->common_model->get_data_by_query('single',$uKycQuery);
				if($userKyc <> ""):		
					$viewData			=	array_merge($viewData,$userKyc);	
				endif;
				
				$html			.=	'<table class="table border-none">
								  <tbody>
								    <tr>
									  <td align="right" colspan="4">
									    <a href="'.base_url().$this->router->fetch_class().'/print_in_pdf/'.$viewId.'" id="downloadlink"><img src="'.base_url().'assets/images/print-icone.png" title="Print Teacher details" alt="Print Teacher details" width="30" /></a>
									  </td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Subject</strong></td>
									  <td align="left" width="30%">'.stripslashes($subjectName).'</td>
									  <td align="left" width="20%"><strong>Employee id</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['employee_id']).'</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>First name</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_f_name']).'</td>
									  <td align="left" width="20%"><strong>Middle name</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_m_name']).'</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Last name</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_l_name']).'</td>
									  <td align="left" width="20%"><strong>Email</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_email']).'</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Phone1</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_phone']).'</td>
									  <td align="left" width="20%"><strong>Phone2</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_emer_phone']).'</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Mobile1</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_mobile']).'</td>
									  <td align="left" width="20%"><strong>Mobile2</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_emer_mobile']).'</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Gender</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_gender']).'</td>
									  <td align="left" width="20%"><strong>Marital status</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_marital_status']).'</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Blood group</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_blood_group']).'</td>
									  <td align="left" width="20%"><strong>Religion</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_religion']).'</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Nationality</strong></td>
									  <td align="left" width="30%">'.stripslashes($viewData['user_nationality']).'</td>
									  <td align="left" width="20%">&nbsp;</td>
									  <td align="left" width="30%">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="4">
										  <table class="table border-none">
											<thead>
											<tr>
											  <th align="left" colspan="4"><strong>Teacher\'s correspondence address</strong></th>
											</tr>
											</thead>
											<tbody>
												<tr>
												  <td align="left" width="20%"><strong>State</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_c_state']).'</td>
												  <td align="left" width="20%"><strong>City</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_c_city']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Locality</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_c_locality']).'</td>
												  <td align="left" width="20%"><strong>Address</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_c_address']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Zip code</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_c_zipcode']).'</td>
												  <td align="left" width="20%">&nbsp;</td>
												  <td align="left" width="30%">&nbsp;</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan="4">
										  <table class="table border-none">
											<thead>
											<tr>
											  <th align="left" colspan="4"><strong>Permanent address</strong></th>
											</tr>
											</thead>
											<tbody>
												<tr>
												  <td align="left" width="20%"><strong>State</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_p_state']).'</td>
												  <td align="left" width="20%"><strong>City</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_p_city']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Locality</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_p_locality']).'</td>
												  <td align="left" width="20%"><strong>Address</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_p_address']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Zip code</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_p_zipcode']).'</td>
												  <td align="left" width="20%">&nbsp;</td>
												  <td align="left" width="30%">&nbsp;</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan="4">
										  <table class="table border-none">
											<thead>
											<tr>
											  <th align="left" colspan="4"><strong>KYC details</strong></th>
											</tr>
											</thead>
											<tbody>
												<tr>
												  <td align="left" width="20%"><strong>Adhar number</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_adhar_no']).'</td>
												  <td align="left" width="20%"><strong>Pan number</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_pan_no']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Account number</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_account_no']).'</td>
												  <td align="left" width="20%"><strong>Name on account</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_name_on_account']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Bank name</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_bank_name']).'</td>
												  <td align="left" width="20%"><strong>IFSC code</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_ifsc_code']).'</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan="4">
										  <table class="table border-none">
											<thead>
											<tr>
											  <th align="left" colspan="4"><strong>Other Details</strong></th>
											</tr>
											</thead>
											<tbody>
												<tr>
												  <td align="left" width="20%"><strong>Date of birth</strong></td>
												  <td align="left" width="30%">'.YYMMDDtoDDMMYY($viewData['user_dob']).'</td>
												  <td align="left" width="20%"><strong>Date of joining</strong></td>
												  <td align="left" width="30%">'.YYMMDDtoDDMMYY($viewData['user_joining_date']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Pickup location</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_pickup']).'</td>
												  <td align="left" width="20%"><strong>Drop location</strong></td>
												  <td align="left" width="30%">'.stripslashes($viewData['user_drop']).'</td>
												</tr>
												<tr>
												  <td align="left" width="20%"><strong>Profile picture</strong></td>
												  <td align="left" width="30%"><img src="'.stripslashes($viewData['user_pic']).'" width="100" border="0" alt="" /></td>
												  <td align="left" width="20%">&nbsp;</td>
									  			  <td align="left" width="30%">&nbsp;</td>
												</tr>
											</tbody>
											</table>
										</td>
									</tr>
									<tr>
									  <td align="left" width="20%"><strong>Status</strong></td>
									  <td align="left" width="30%">'.showStatus($viewData['status']).'</td>
									  <td align="left" width="20%">&nbsp;</td>
									  <td align="left" width="30%">&nbsp;</td>
									</tr>';
				$html			.=	'</tbody>
								</table>';
			endif;
		endif;
		echo $html; die;
	}
	
	/***********************************************************************
	** Function name : uplode_image
	** Developed By : Manoj Kumar
	** Purpose  : This function used uplode image
	** Date : 27 JANUARY 2018
	************************************************************************/
	function uplode_image()
	{ 
		$file_name					= 	$_FILES['uploadfile']['name'];
		if($file_name): 
			$tmp_name				= 	$_FILES['uploadfile']['tmp_name'];		
					
			$imagefolder			=	'S_'.manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')).'/B_'.manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID'));
			$ext 					= 	pathinfo($file_name);
			$newfilename			=	time().'.'.$ext['extension'];
			$this->load->library("upload_crop_img");
			$return_file_name	=	$this->upload_crop_img->_upload_image($file_name,$tmp_name,'teacherImage',$newfilename,$imagefolder);
			echo $return_file_name; die;
		else:
			echo 'UPLODEERROR'; die;
		endif;
	}
	
	/***********************************************************************
	** Function name : DeleteImage
 	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete image by ajax.
	** Date : 27 JANUARY 2018
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
	** Function name: print_in_pdf
	** Developed By: Manoj Kumar
	** Purpose: This function used for print in pdf
	** Date: 30 JANUARY 2018
	************************************************************************/
	public function print_in_pdf($printpdfId=''){	
	
		$this->load->library('Mpdf');
		if($printpdfId == ''): redirect(correctLink('teacherListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index')); endif;
		$data['printpdfId']		=	manojDecript($printpdfId);
		$viewId					=	$printpdfId;
		
		$viewData				=	$this->common_model->get_data_by_encryptId('users',$viewId);
		if($viewData <> ""):
			$subSeQuery			=	"SELECT s.subject_name FROM sms_teacher_subject as ts LEFT JOIN sms_subject as s ON ts.subject_id=s.encrypt_id WHERE teacher_id = '".$viewId."'";
			$SUBJECTIDS			=	$this->common_model->get_data_by_query('multiple',$subSeQuery);
			$subjectName		=	'';
			if($SUBJECTIDS <> ""): $i=0;
				foreach($SUBJECTIDS as $SUBJECTdataS):
					$subjectName	.=	$i>0?', ':'';
					$subjectName	.=	$SUBJECTdataS['subject_name'];
					$i++;
				endforeach;
			endif;
			
			$uDeQuery				=	"SELECT user_gender,user_gender_short,user_marital_status,user_marital_short,user_blood_group,user_religion,user_nationality,
										user_dob,user_joining_date,user_pickup,user_drop,user_pic 
										FROM sms_user_detils WHERE user_id = '".$viewId."'";
			$userDetails			=	$this->common_model->get_data_by_query('single',$uDeQuery);
			if($userDetails <> ""):		
				$viewData			=	array_merge($viewData,$userDetails);	
			endif;
			
			$uAddQuery				=	"SELECT user_c_address,user_c_locality,user_c_city,user_c_state,user_c_zipcode,same_address,
										 user_p_address,user_p_locality,user_p_city,user_p_state,user_p_zipcode 
										 FROM sms_user_address WHERE user_id = '".$viewId."'";
			$userAddress			=	$this->common_model->get_data_by_query('single',$uAddQuery);
			if($userAddress <> ""):		
				$viewData			=	array_merge($viewData,$userAddress);	
			endif;
			
			$uKycQuery				=	"SELECT user_adhar_no,user_pan_no,user_account_no,user_name_on_account,user_bank_name,user_ifsc_code 
										 FROM sms_user_kyc WHERE user_id = '".$viewId."'";
			$userKyc				=	$this->common_model->get_data_by_query('single',$uKycQuery);
			if($userKyc <> ""):		
				$viewData			=	array_merge($viewData,$userKyc);	
			endif;
		endif;
		
		$data['viewData']			=	$viewData;
		$data['subjectName']		=	$subjectName;
		
		$this->load->view('teacherlist/printinpdf',$data);
		$this->download_pdf('teacher_details_'.$data['printpdfId'].'.pdf');
	}	
	
	/***********************************************************************
	** Function name: download_pdf
	** Developed By: Manoj Kumar
	** Purpose: This function used for download pdf
	** Date: 30 JANUARY 2018
	************************************************************************/
	public function download_pdf($file='')
	{
		header('Content-Description: File Transfer');
		// We'll be outputting a PDF
		header('Content-type: application/pdf');
		// It will be called downloaded.pdf
		header('Content-Disposition: attachment; filename="'.$file.'"');
		// The PDF source is in original.pdf
		readfile($this->config->item("root_path")."assets/downloadpdf/".$file);
		
		@unlink($this->config->item("root_path")."assets/downloadpdf/".$file);
		exit;
	}	
        
        /*     * *********************************************************************
     * * Function name : addeditdoc
     * * Developed By : Manoj Kumar
     * * Purpose  : This function used for add edit document data data
     * * Date : 01 FEBRUARY 2018
     * * ********************************************************************* */

    public function addeditdoc($editId = '') {

        $data['error']     = '';
        $data['userid'] = $editId;
        if (!$editId):
           redirect( correctLink('teacherListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
        endif;
        $docQuery         = "SELECT * FROM `sms_teacher_doc_type` ";
        $data['DOCDATA']  = $this->common_model->get_data_by_query('multiple', $docQuery);
        $docSelectedQuery         = "SELECT * FROM `sms_teacher_doc_type` where encrypt_id = '".$this->input->post('doc_type_id')."' ";
        $DOCSELECTDATA  = $this->common_model->get_data_by_query('single', $docSelectedQuery);
        $tecQuery         = "SELECT * FROM sms_users WHERE encrypt_id = '" . $editId . "'";
        $TECDATA          = $this->common_model->get_data_by_query('single', $tecQuery);
        $addQuery         = "SELECT *
										FROM sms_teacher_document 
									 	WHERE 
										 user_id = '" .$editId. "'";
        $data['EDITDATA'] = $this->common_model->get_data_by_query('multiple', $addQuery);
         $alldataQuery         = "SELECT doc.doc_type , tec.* FROM sms_teacher_document AS tec  
LEFT  JOIN  `sms_teacher_doc_type` AS doc  ON doc.encrypt_id =  tec.doc_type_id  WHERE user_id = '" . $editId . "'";
        
        $data['ALLDATA']         = $this->common_model->get_data_by_query('multiple', $alldataQuery);
        if ($data['EDITDATA'] <> ""):
            $this->admin_model->authCheck('admin', 'edit_data');
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;
        if ($this->input->post('SaveChanges')):
            $error = 'NO';
            $this->form_validation->set_rules('doc_type_id', 'Doc Type', 'trim|required');
            //upload  document   
          
            $file_name = $_FILES['uploadfile']['name'];
            if ($file_name):
                $tmp_name = $_FILES['uploadfile']['tmp_name'];

                $imagefolder = 'S_' . manojDecript($this->session->userdata('SMS_ADMIN_SCHOOL_ID')) . '/B_' . manojDecript($this->session->userdata('SMS_ADMIN_BRANCH_ID'));
                $ext         = pathinfo($file_name);
                $newfilename =     $TECDATA['user_id'].'_'.str_replace(' ', '_', $DOCSELECTDATA['doc_type']) .'_'. time() . '.' . $ext['extension'];
                 
                $this->load->library("upload_crop_img");
                    //add student image to studentimage folder
                    $return_file_name = $this->upload_crop_img->_upload_image($file_name, $tmp_name, 'teacherDocument', $newfilename, $imagefolder);
            endif;
            //add edit to database

            if ($this->form_validation->run() && $error == 'NO'):
                 
                $Param['document']       = $return_file_name;
                $Param['doc_type_id']            = addslashes($this->input->post('doc_type_id'));
               
                //add or delete condition
          $edit = '' ;  
     
 foreach ( $data['ALLDATA'] as $INFO):
    
  if($Param['doc_type_id']  == $INFO['doc_type_id'] ) :
      $edit = 'YES'; 
 
      
          endif;
     
     
 endforeach;
                

                if (($this->input->post('CurrentDataID') == '') and  ($edit == '')):

                    $Param['user_id'] = $data['userid'];

                    $Param['creation_date'] = currentDateTime();
                    $Param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $Param['status']        = 'Y';
                                    $Param['session_year']			=	CURRENT_SESSION;
                    $teclastInsertId     = $this->common_model->add_data('teacher_document', $Param);

                    $Sparam['encrypt_id']        = manojEncript($teclastInsertId);
                    $Swhere['teacher_document_id'] = $teclastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('teacher_document', $Sparam, $Swhere);

                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                   
                    $teclastInsertId = $this->input->post('CurrentDataID');

                    $Param['update_date'] = currentDateTime();
                    $Param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                            $Where['doc_type_id']  = $Param['doc_type_id'];
                    $Where['user_id']  = $editId;
             
                    $this->common_model->edit_data_by_multiple_cond('teacher_document', $Param, $Where);

                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditdoc/' . $editId);
            endif;
        endif;

        $this->layouts->set_title('Add/edit teacher documents details');
        $this->layouts->admin_view('teacherlist/addeditdoc', array(), $data);
    }
        
        
}
