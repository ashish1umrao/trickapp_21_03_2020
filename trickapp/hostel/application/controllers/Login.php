<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
	 * * Function name : login
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for login
	 * * Date : 11 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{	
        
		if($this->session->userdata('SMS_HOSTEL_ADMIN_ID')) redirect($this->session->userdata('SMS_HOSTEL_ADMIN_PATH').'dashboard');
		$data['error'] 						= 	'';
		
		/*-----------------------------------Login ---------------*/
		if($this->input->post('loginFormSubmit')):	
                  
			//Set rules
			$this->form_validation->set_rules('userEmail', 'email', 'trim|required');
			$this->form_validation->set_rules('userPassword', 'password', 'trim|required');
			
			if($this->form_validation->run()):	
				$result		=	$this->admin_model->Authenticate($this->input->post('userEmail'));
                              
                        
				if($result): //echo $this->admin_model->decrypts_password($result['user_password']); die;
          
					if($this->admin_model->decrypts_password($result['user_password']) != $this->input->post('userPassword')):
						$data['error'] = lang('invalidpassword');	
                                        
                              
					elseif($result['status'] != 'A'):	
						$data['error'] = lang('accountblock');
					
                             
					elseif(($result['hostel_status'] != 'Y' && $result['franchise_status'] != 'A' && $result['school_status'] != 'A' && $result['branch_status'] != 'A')):	
						$data['error'] = lang('accountblock');	
					else:	
					
							$franchiseId	=	$result['franchise_id'];
                                                    
							$franchiseName	=	$result['franchise_name'];
                                                       
							$franchiseDisName=	$result['franchise_display_name'];
                                                        
							$franchiseSlug	=	$result['franchise_slug'];
							  
							$schoolId		=	$result['school_id'];
                                                       
							$schoolName		=	$result['school_name'];
                                                        
							$schoolDisName	=	$result['school_display_name'];
                                                        
							$schoolSlug		=	$result['school_slug'];
							
							$branchId		=	$result['branch_id'];
                                                       
							$branchName		=	$result['branch_name'];
                                                       
							$branchDisName	=	$result['branch_display_name'];
							$branchSlug		=	$result['branch_slug'];
                                                        
                                                        $hostelId		=	$result['hostel_id'];
                                                       
							$hostelName		=	$result['hostel_name'];
                                                       
							$hostelDisName	=	$result['hostel_display_name'];
							$hostelSlug		=	$result['hostel_slug'];
							 
							
							$userPath		=	base_url().$result['school_slug'].'/'.$result['branch_slug'].'/'.$result['hostel_slug'].'/';
							$userName		=	$result['user_f_name'];
						
						$this->session->set_userdata(array(
												'SMS_HOSTEL_ADMIN_LOGGED_IN'=>true,
												'SMS_HOSTEL_ADMIN_ID'=>$result['encrypt_id'],
												'SMS_HOSTEL_ADMIN_NAME'=>$result['user_f_name'],
												'SMS_HOSTEL_ADMIN_DISLPLAY_NAME'=>$result['user_f_name'],
												'SMS_HOSTEL_ADMIN_SLUG'=>$result['hostel_slug'],
												'SMS_HOSTEL_ADMIN_EMAIL'=>$result['user_email'],
												'SMS_HOSTEL_ADMIN_MOBILE'=>$result['user_mobile'],
												'SMS_HOSTEL_ADMIN_TYPE'=>'WARDEN',
												'SMS_HOSTEL_ADMIN_EMPLOYEE_ID'=>$result['employee_id'],
												'SMS_HOSTEL_ADMIN_DESIGNATION'=>$result['department_name'],
												'SMS_HOSTEL_ADMIN_FRANCHISE_ID'=>$franchiseId,
												'SMS_HOSTEL_ADMIN_FRANCHISE_NAME'=>$franchiseName,
												'SMS_HOSTEL_ADMIN_FRANCHISE_DIS_NAME'=>$franchiseDisName,
												'SMS_HOSTEL_ADMIN_FRANCHISE_SLUG'=>$franchiseSlug,
												'SMS_HOSTEL_ADMIN_SCHOOL_ID'=>$schoolId,
												'SMS_HOSTEL_ADMIN_SCHOOL_NAME'=>$schoolName,
												'SMS_HOSTEL_ADMIN_SCHOOL_DIS_NAME'=>$schoolDisName,
												'SMS_HOSTEL_ADMIN_SCHOOL_SLUG'=>$schoolSlug,
												'SMS_HOSTEL_ADMIN_BRANCH_ID'=>$branchId,
												'SMS_HOSTEL_ADMIN_BRANCH_NAME'=>$branchName,
												'SMS_HOSTEL_ADMIN_BRANCH_DIS_NAME'=>$branchDisName,
												'SMS_HOSTEL_ADMIN_BRANCH_SLUG'=>$branchSlug,
												'SMS_HOSTEL_ADMIN_HOSTEL_ID'=>$hostelId,
												'SMS_HOSTEL_ADMIN_HOSTEL_NAME'=>$hostelName,
												'SMS_HOSTEL_ADMIN_HOSTEL_DIS_NAME'=>$hostelDisName,
												'SMS_HOSTEL_ADMIN_HOSTEL_SLUG'=>$hostelSlug,
												'SMS_HOSTEL_ADMIN_PATH'=>$userPath,
												'SMS_HOSTEL_ADMIN_NAME'=>$userName,
												'SMS_HOSTEL_ADMIN_LOGO'=>$result['admin_image'],
												'SMS_HOSTEL_ADMIN_ADDRESS'=>$result['user_c_address'],
												'SMS_HOSTEL_ADMIN_LOCALITY'=>$result['user_c_locality'],
												'SMS_HOSTEL_ADMIN_CITY'=>$result['user_c_city'],
												'SMS_HOSTEL_ADMIN_STATE'=>$result['user_c_state'],
												'SMS_HOSTEL_ADMIN_ZIPCODE'=>$result['user_c_zipcode'],
												'SMS_HOSTEL_ADMIN_LAST_LOGIN'=>$result['user_last_login'].' ('.$result['user_last_login_ip'].')'));
						
						$param['user_last_login']		=	currentDateTime();
						$param['user_last_login_ip']	=	currentIp();
				
						$this->common_model->edit_data('users',$param,$result['encrypt_id']);
						
						if($this->input->post('rememberMe') == 'YES'):	
							 setcookie("SMSHOSTELADMINUserEmail",$this->input->post('userEmail'),time()+60*60*24*100,'/');
							 setcookie("SMSHOSTELADMINUserPass",$this->input->post('userPassword'),time()+60*60*24*100,'/');
							 setcookie("SMSHOSTELADMINRememberMe",'YES',time()+60*60*24*100,'/');
						else:
							 setcookie("SMSHOSTELADMINUserEmail",$this->input->post('userEmail'),time()-60*60*24*100,'/');
							 setcookie("SMSHOSTELADMINUserPass",$this->input->post('userPassword'),time()-60*60*24*100,'/');
							 setcookie("SMSHOSTELADMINRememberMe",'YES',time()-60*60*24*100,'/');
						endif;
						
						if($_COOKIE['SMS_HOSTEL_ADMIN_REFERENCEPAGES']):
							redirect(base_url().$_COOKIE['SMS_HOSTEL_ADMIN_REFERENCEPAGES']);
						else:
                                                    
                                                    
							redirect($userPath.'dashboard');
						endif;
					endif;
				else:
					$data['error'] = lang('invalidlogindetails');
				endif;			
			endif;
		endif;
		
		$this->layouts->set_title('Login');
		$this->layouts->admin_view('login',array(),$data,'login');
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : logout
	** Developed By : Manoj Kumar
	** Purpose  : This function used for logout
	** Date : 11 JANUARY 2018
	************************************************************************/
	function logout()
	{
		setcookie('SMS_HOSTEL_ADMIN_REFERENCEPAGES', '', time() - 60*60*24*100, '/');
		$this->session->unset_userdata(array('SMS_HOSTEL_ADMIN_LOGGED_IN','SMS_HOSTEL_ADMIN_ID','SMS_HOSTEL_ADMIN_NAME','SMS_HOSTEL_ADMIN_DISLPLAY_NAME','SMS_HOSTEL_ADMIN_SLUG','SMS_HOSTEL_ADMIN_EMAIL','SMS_HOSTEL_ADMIN_MOBILE','SMS_HOSTEL_ADMIN_TYPE','SMS_HOSTEL_ADMIN_EMPLOYEE_ID','SMS_HOSTEL_ADMIN_DESIGNATION','SMS_HOSTEL_ADMIN_FRANCHISE_ID','SMS_HOSTEL_ADMIN_FRANCHISE_NAME','SMS_HOSTEL_ADMIN_FRANCHISE_DIS_NAME','SMS_HOSTEL_ADMIN_FRANCHISE_SLUG','SMS_HOSTEL_ADMIN_SCHOOL_ID','SMS_HOSTEL_ADMIN_SCHOOL_NAME','SMS_HOSTEL_ADMIN_SCHOOL_DIS_NAME','SMS_HOSTEL_ADMIN_SCHOOL_SLUG','SMS_HOSTEL_ADMIN_BRANCH_ID','SMS_HOSTEL_ADMIN_BRANCH_NAME','SMS_HOSTEL_ADMIN_BRANCH_DIS_NAME','SMS_HOSTEL_ADMIN_BRANCH_SLUG','SMS_HOSTEL_ADMIN_HOSTEL_ID','SMS_HOSTEL_ADMIN_HOSTEL_NAME','SMS_HOSTEL_ADMIN_HOSTEL_DIS_NAME','SMS_HOSTEL_ADMIN_HOSTEL_SLUG','SMS_HOSTEL_ADMIN_BOARD_ID','SMS_HOSTEL_ADMIN_BOARD_NAME','SMS_HOSTEL_ADMIN_PATH','SMS_HOSTEL_ADMIN_NAME','SMS_HOSTEL_ADMIN_LOGO','SMS_HOSTEL_ADMIN_ADDRESS','SMS_HOSTEL_ADMIN_LOCALITY','SMS_HOSTEL_ADMIN_CITY','SMS_HOSTEL_ADMIN_STATE','SMS_HOSTEL_ADMIN_ZIPCODE','SMS_HOSTEL_ADMIN_LAST_LOGIN'));
		redirect(base_url());
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : error_404
	** Developed By : Manoj Kumar
	** Purpose  : This function used for error 404
	** Date : 12 JANUARY 2018
	************************************************************************/
	function error_404()
	{	
		$data['error'] 						= 	'';

		$this->layouts->set_title('404 Page Not Found');
		$this->layouts->admin_view('error_404',array(),$data);
		
	}	// END OF FUNCTION
	
	
	
	
}
