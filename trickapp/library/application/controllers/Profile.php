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
		
		$whereCon['where']			 		= 	'usr.encrypt_id ="'.$this->session->userdata('SMS_LIBRARY_ADMIN_ID').'"';		
		$shortField 						= 	'usr.encrypt_id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_LIBRARY_ADMIN_PATH').$this->router->fetch_class().'/index/';
		$tblName 							= 	'users as usr';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectAdminData('count',$tblName,$whereCon,$shortField,'0','0');
		$config['per_page']	 				= 	10;
		$config['uri_segment']				= 	3;
		$this->pagination->initialize($config);
		
		if($this->uri->segment(3)):
			$page = $this->uri->segment(3);
		else:
			$page =0;
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
		
                         $subQuery           = "SELECT usr.*,ad.encrypt_id as address_id,ad.user_c_address ,ad.user_c_locality ,ad.user_c_city ,ad.user_c_state  ,ad.user_c_zipcode  FROM sms_users AS usr   LEFT JOIN `sms_user_address` AS ad ON ad.user_id = usr.encrypt_id where  usr.user_type='Librarian'and usr.encrypt_id='".$editid."'  and usr.franchise_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID') . "'
												 AND usr.school_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') . "' 
												 AND usr.branch_id = '" . $this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID') . "' " ;
        $data['profileuserdata'] = $this->common_model->get_data_by_query('single', $subQuery);
		
		if($data['profileuserdata'] == ''):
			redirect($this->session->userdata('SMS_LIBRARY_ADMIN_PATH').'dashboard');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error							=	'NO';
			$this->form_validation->set_rules('user_f_name', ' first Name', 'trim|required');
			
			
			
			if($this->input->post('new_password')!= ''):
				$this->form_validation->set_rules('new_password', 'New password', 'trim|required|min_length[6]|max_length[25]');
				$this->form_validation->set_rules('conf_password', 'Confirm password', 'trim|required|min_length[6]|matches[new_password]');
			endif;
			
			
			
			$this->form_validation->set_rules('user_c_address', 'Address', 'trim|required');
			$this->form_validation->set_rules('user_c_locality', 'Locality', 'trim|required');
			$this->form_validation->set_rules('user_c_city', 'City', 'trim|required');
			$this->form_validation->set_rules('user_c_state', 'State', 'trim|required');
			$this->form_validation->set_rules('user_c_zipcode', 'Zipcode', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):
			  
				$param['user_f_name']				= 	addslashes($this->input->post('user_f_name'));
                        	$param['user_m_name']				= 	addslashes($this->input->post('user_m_name'));
                                $param['user_l_name']				= 	addslashes($this->input->post('user_l_name'));
                                 $param['update_date']				=	currentDateTime();
				$param['updated_by']				=	$this->session->userdata('SMS_LIBRARY_ADMIN_ID');       
				
                                $Adparam['user_c_address']				= 	addslashes($this->input->post('user_c_address'));
				$Adparam['user_c_locality']			= 	addslashes($this->input->post('user_c_locality'));
				$Adparam['user_c_city']				= 	addslashes($this->input->post('user_c_city'));
				$Adparam['user_c_state']				= 	addslashes($this->input->post('user_c_state'));
				$Adparam['user_c_zipcode']				= 	addslashes($this->input->post('user_c_zipcode'));
				
				if($data['profileuserdata']['address_id']):
				
				$Adparam['update_date']				=	currentDateTime();
				$Adparam['updated_by']				=	$this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                                   $this->common_model->edit_data('user_address',$Adparam,$data['profileuserdata']['address_id']);
                               else:
                                  $Adparam['creation_date'] = currentDateTime();
                $Adparam['created_by']    = $this->session->userdata('SMS_LIBRARY_ADMIN_ID');
                $Adparam['status']        = 'Y';
                $alastInsertId          = $this->common_model->add_data('user_address', $Adparam);
                $uparam['encrypt_id']   = manojEncript($alastInsertId);
                $Uwhere['user_address_id']   = $alastInsertId;
               
                $this->common_model->edit_data_by_multiple_cond('user_address', $uparam, $Uwhere); 
                                   
                                   
                               endif;
                               
                               
                                
				if($this->input->post('new_password')):
					$NewPassword					=	$this->input->post('new_password');
					$param['user_password']		= 	$this->admin_model->encript_password($NewPassword);
					if(get_cookie('SMSLIBRARYADMINRememberMe') == 'YES'):
						setcookie("SMSLIBRARYADMINUserEmail",$data['profileuserdata']['user_email'],time()+60*60*24*100,'/');
						setcookie("SMSLIBRARYADMINUserPass",$NewPassword,time()+60*60*24*100,'/');
						setcookie("SMSLIBRARYADMINRememberMe",'YES',time()+60*60*24*100,'/');
					endif;
				else:
					if(get_cookie('SMSLIBRARYADMINRememberMe') == 'YES'):
						setcookie("SMSLIBRARYADMINUserEmail",$data['profileuserdata']['user_email'],time()+60*60*24*100,'/');
						setcookie("SMSLIBRARYADMINRememberMe",'YES',time()+60*60*24*100,'/');
					endif;
				endif;
				
				
                             
				$this->common_model->edit_data('users',$param,$this->input->post('CurrentDataID'));
                                
                                
				$result		=	$this->admin_model->Authenticate( $data['profileuserdata']['user_email'] );
				if($result):
					
					$this->session->set_userdata(array(
											'SMS_LIBRARY_ADMIN_NAME'=>$result['user_f_name'],
											'SMS_LIBRARY_ADMIN_DISLPLAY_NAME'=>$result['user_f_name'],
											'SMS_LIBRARY_ADMIN_SLUG'=>$result['branch_slug'],
											
											
											'SMS_LIBRARY_ADMIN_ADDRESS'=>$result['user_c_address'],
												'SMS_LIBRARY_ADMIN_LOCALITY'=>$result['user_c_locality'],
												'SMS_LIBRARY_ADMIN_CITY'=>$result['user_c_city'],
												'SMS_LIBRARY_ADMIN_STATE'=>$result['user_c_state'],
												'SMS_LIBRARY_ADMIN_ZIPCODE'=>$result['user_c_zipcode']));
				endif;
				
				$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				redirect($this->session->userdata('SMS_LIBRARY_ADMIN_PATH').$this->router->fetch_class().'/index');
			endif;
		endif;
		
		$this->layouts->set_title('Edit profile');
		$this->layouts->admin_view('profile/editprofile',array(),$data);
	}	// END OF FUNCTION	
}
