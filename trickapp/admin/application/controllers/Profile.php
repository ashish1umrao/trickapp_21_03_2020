<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

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
	 * * Function name : profile
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for profile
	 * * Date : 12 JANUARY 2018 
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin');
		$data['error'] 						= 	'';
		
		$whereCon['where']			 		= 	'adm.encrypt_id ="'.$this->session->userdata('SMS_ADMIN_ID').'"';		
		$shortField 						= 	'adm.admin_id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$tblName 							= 	'admin as adm';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectAdminData('count',$tblName,$whereCon,$shortField,'0','0');
		$config['per_page']	 				= 	10;
		$config['uri_segment'] = getUrlSegment();
       $this->pagination->initialize($config);

       if ($this->uri->segment(getUrlSegment())):
           $page = $this->uri->segment(getUrlSegment());
       else:
           $page = 0;
       endif;
		
		$data['ADMINDATA'] 					= 	$this->admin_model->SelectAdminData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Profile');
		$this->layouts->admin_view('profile/index',array(),$data);
	}
	
	/* * *********************************************************************
	 * * Function name : editmyaccount
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for edit myaccount
	 * * Date : 12 JANUARY 2018
	 * * **********************************************************************/
	public function editprofile($editid='')
	{
		$this->admin_model->authCheck('admin');
		$data['error'] 						= 	'';
		
		$data['profileuserdata']			=	$this->common_model->get_data_by_encryptId('admin',$editid); 
		if($data['profileuserdata'] == ''):
			redirect($this->session->userdata('PROSHOP_USER_PATH').'dashboard');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error							=	'NO';
			$this->form_validation->set_rules('admin_name', 'Name', 'trim|required');
			$this->form_validation->set_rules('admin_display_name', 'Display name', 'trim|required');
			if($this->session->userdata('SMS_ADMIN_TYPE') != 'Sub admin'):
				$this->form_validation->set_rules('admin_slug', 'Slug url', 'trim|required|is_unique[admin.admin_slug]');
			endif;
			$this->form_validation->set_rules('admin_email_id', 'E-Mail', 'trim|required|valid_email|is_unique[admin.admin_email_id]');
			if($this->input->post('new_password')!= ''):
				$this->form_validation->set_rules('new_password', 'New password', 'trim|required|min_length[6]|max_length[25]');
				$this->form_validation->set_rules('conf_password', 'Confirm password', 'trim|required|min_length[6]|matches[new_password]');
			endif;
			
			$this->form_validation->set_rules('admin_mobile_number', 'Mobile number', 'trim|required|min_length[10]|max_length[15]|is_unique[admin.admin_mobile_number]');
			$testmobile		=	str_replace(' ','',$this->input->post('admin_mobile_number'));
			if($this->input->post('admin_mobile_number') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testmobile)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testmobile)):
					$error						=	'YES';
					$data['mobileerror'] 		= 	'Please eneter correct number';
				endif;
			endif;
			
			$this->form_validation->set_rules('admin_address', 'Address', 'trim|required');
			$this->form_validation->set_rules('admin_locality', 'Locality', 'trim|required');
			$this->form_validation->set_rules('admin_city', 'City', 'trim|required');
			$this->form_validation->set_rules('admin_state', 'State', 'trim|required');
			$this->form_validation->set_rules('admin_zipcode', 'Zipcode', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):
			 
				$param['admin_name']				= 	addslashes($this->input->post('admin_name'));
				$param['admin_display_name']		= 	addslashes($this->input->post('admin_display_name'));
				if($this->session->userdata('SMS_ADMIN_TYPE') != 'Sub admin'):
					$param['admin_slug']			= 	url_title(addslashes($this->input->post('admin_slug')));
				endif;
				$param['admin_email_id']			= 	addslashes($this->input->post('admin_email_id'));
				$param['admin_mobile_number']		= 	addslashes($this->input->post('admin_mobile_number'));
				$param['admin_address']				= 	addslashes($this->input->post('admin_address'));
				$param['admin_locality']			= 	addslashes($this->input->post('admin_locality'));
				$param['admin_city']				= 	addslashes($this->input->post('admin_city'));
				$param['admin_state']				= 	addslashes($this->input->post('admin_state'));
				$param['admin_zipcode']				= 	addslashes($this->input->post('admin_zipcode'));
				
				if($this->input->post('new_password')):
					$NewPassword					=	$this->input->post('new_password');
					$param['admin_password']		= 	$this->admin_model->encript_password($NewPassword);
					if(get_cookie('SMSADMINRememberMe') == 'YES'):
						setcookie("SMSADMINUserEmail",$param['admin_email_id'],time()+60*60*24*100,'/');
						setcookie("SMSADMINUserPass",$NewPassword,time()+60*60*24*100,'/');
						setcookie("SMSADMINRememberMe",'YES',time()+60*60*24*100,'/');
					endif;
				else:
					if(get_cookie('SMSADMINRememberMe') == 'YES'):
						setcookie("SMSADMINUserEmail",$param['admin_email_id'],time()+60*60*24*100,'/');
						setcookie("SMSADMINRememberMe",'YES',time()+60*60*24*100,'/');
					endif;
				endif;
				
				$param['update_date']				=	currentDateTime();
				$param['updated_by']				=	$this->session->userdata('SMS_ADMIN_ID');
				$this->common_model->edit_data('admin',$param,$this->input->post('CurrentDataID'));
				
				$result		=	$this->admin_model->Authenticate($param['admin_email_id']);
				if($result):
					if($result['admin_type'] == 'Super admin'):
						$userPath		=	base_url().$result['admin_slug'].'/';
					elseif($result['admin_type'] == 'Franchising'):
						$userPath		=	base_url().$result['admin_slug'].'/';
					elseif($result['admin_type'] == 'School'):
						$userPath		=	base_url().$result['admin_slug'].'/';
					elseif($result['admin_type'] == 'Branch'):
						$userPath		=	base_url().$result['school_slug'].'/'.$result['admin_slug'].'/';
					elseif($result['admin_type'] == 'Sub admin'):
						$userPath		=	base_url().$result['school_slug'].'/'.$result['branch_slug'].'/';
					endif;
					$this->session->set_userdata(array(
											'SMS_ADMIN_NAME'=>$result['admin_name'],
											'SMS_ADMIN_DISLPLAY_NAME'=>$result['admin_display_name'],
											'SMS_ADMIN_SLUG'=>$result['admin_slug'],
											'SMS_ADMIN_EMAIL'=>$result['admin_email_id'],
											'SMS_ADMIN_MOBILE'=>$result['admin_mobile_number'],
											'SMS_ADMIN_PATH'=>$userPath,
											'SMS_ADMIN_ADDRESS'=>$result['admin_address'],
											'SMS_ADMIN_LOCALITY'=>$result['admin_locality'],
											'SMS_ADMIN_CITY'=>$result['admin_city'],
											'SMS_ADMIN_STATE'=>$result['admin_state'],
											'SMS_ADMIN_ZIPCODE'=>$result['admin_zipcode']));
				endif;
				
				$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				redirect($this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index');
			endif;
		endif;
		
		$this->layouts->set_title('Edit profile');
		$this->layouts->admin_view('profile/editprofile',array(),$data);
	}	// END OF FUNCTION	
}
