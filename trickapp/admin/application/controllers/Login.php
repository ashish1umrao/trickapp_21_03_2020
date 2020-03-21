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
		if($this->session->userdata('SMS_ADMIN_ID')) redirect($this->session->userdata('SMS_ADMIN_PATH').'dashboard');
		$data['error'] 						= 	'';
		
		/*-----------------------------------Login ---------------*/
		if($this->input->post('loginFormSubmit')):	
			//Set rules
			$this->form_validation->set_rules('userEmail', 'email', 'trim|required');
			$this->form_validation->set_rules('userPassword', 'password', 'trim|required');
			
			if($this->form_validation->run()):	
				$result		=	$this->admin_model->Authenticate($this->input->post('userEmail'));
				if($result): 
                                //echo $this->admin_model->decrypts_password($result['admin_password']); die;
					if($this->admin_model->decrypts_password($result['admin_password']) != $this->input->post('userPassword')):
						$data['error'] = lang('invalidpassword');	
					elseif($result['status'] != 'A'):	
						$data['error'] = lang('accountblock');
					elseif($result['admin_type'] == 'School' && $result['franchise_status'] != 'A'):	
						$data['error'] = lang('accountblock');
					elseif($result['admin_type'] == 'Branch' && $result['franchise_status'] != 'A' && $result['school_status'] != 'A'):	
						$data['error'] = lang('accountblock');
					elseif($result['admin_type'] == 'Sub admin' && $result['franchise_status'] != 'A' && ($result['school_status'] != 'A' || $result['branch_status'] != 'A')):	
						$data['error'] = lang('accountblock');	
					else:	
						if($result['admin_type'] == 'Super admin'):
							$fFQuery		=	"SELECT encrypt_id as franchise_id, admin_name as franchise_name, admin_display_name as franchise_display_name, admin_slug as franchise_slug
												 FROM sms_admin WHERE admin_type = 'Franchising' AND status = 'A' ORDER BY admin_id ASC LIMIT 0,1";  
							$fFchoolData	=	$this->common_model->get_data_by_query('single',$fFQuery); 
			
							$franchiseId	=	$fFchoolData['franchise_id'];
							$franchiseName	=	$fFchoolData['franchise_name'];
							$franchiseDisName=	$fFchoolData['franchise_display_name'];
							$franchiseSlug	=	$fFchoolData['franchise_slug'];
						
							$fSQuery		=	"SELECT encrypt_id as school_id, admin_name as school_name, admin_display_name as school_display_name, admin_slug as school_slug
												 FROM sms_admin WHERE admin_type = 'School' AND admin_franchise_id = '".$fFchoolData['franchise_id']."' AND status = 'A' ORDER BY admin_id ASC LIMIT 0,1";  
							$fSchoolData	=	$this->common_model->get_data_by_query('single',$fSQuery); 
			
							$schoolId		=	$fSchoolData['school_id'];
							$schoolName		=	$fSchoolData['school_name'];
							$schoolDisName	=	$fSchoolData['school_display_name'];
							$schoolSlug		=	$fSchoolData['school_slug'];
							
							$fBQuery		=	"SELECT encrypt_id as branch_id, admin_name as branch_name, admin_display_name as branch_display_name, admin_slug as branch_slug
												 FROM sms_admin WHERE admin_type = 'Branch' AND admin_franchise_id = '".$fFchoolData['franchise_id']."' AND admin_school_id = '".$fSchoolData['school_id']."' AND status = 'A' ORDER BY admin_id ASC LIMIT 0,1";  
							$fBranchData	=	$this->common_model->get_data_by_query('single',$fBQuery); 
							
							$branchId		=	$fBranchData['branch_id'];
							$branchName		=	$fBranchData['branch_name'];
							$branchDisName	=	$fBranchData['branch_display_name'];
							$branchSlug		=	$fBranchData['branch_slug'];
							
							$fBOQuery		=	"SELECT board_id, branch_board_name as board_name
												 FROM sms_branch_boards WHERE franchise_id = '".$fFchoolData['franchise_id']."' AND school_id = '".$fSchoolData['school_id']."' AND branch_id = '".$fBranchData['branch_id']."' AND status = 'Y' ORDER BY branch_board_name ASC LIMIT 0,1";  
							$fBoardData		=	$this->common_model->get_data_by_query('single',$fBOQuery); 
							
							$boardId		=	$fBoardData['board_id'];
							$boardName		=	$fBoardData['board_name'];
							
							$userPath		=	base_url().$result['admin_slug'].'/';
							$userName		=	$result['admin_display_name'];
						elseif($result['admin_type'] == 'Franchising'):
							$franchiseId	=	$result['encrypt_id'];
							$franchiseName	=	$result['admin_name'];
							$franchiseDisName=	$result['admin_display_name'];
							$franchiseSlug	=	$result['admin_slug'];
						
							$fSQuery		=	"SELECT encrypt_id as school_id, admin_name as school_name, admin_display_name as school_display_name, admin_slug as school_slug
												 FROM sms_admin WHERE admin_type = 'School' AND admin_franchise_id = '".$result['encrypt_id']."' AND status = 'A' ORDER BY admin_id ASC LIMIT 0,1";  
							$fSchoolData	=	$this->common_model->get_data_by_query('single',$fSQuery); 
			
							$schoolId		=	$fSchoolData['school_id'];
							$schoolName		=	$fSchoolData['school_name'];
							$schoolDisName	=	$fSchoolData['school_display_name'];
							$schoolSlug		=	$fSchoolData['school_slug'];
							
							$fBQuery		=	"SELECT encrypt_id as branch_id, admin_name as branch_name, admin_display_name as branch_display_name, admin_slug as branch_slug
												 FROM sms_admin WHERE admin_type = 'Branch' AND admin_franchise_id = '".$result['encrypt_id']."' AND admin_school_id = '".$fSchoolData['school_id']."' AND status = 'A' ORDER BY admin_id ASC LIMIT 0,1";  
							$fBranchData	=	$this->common_model->get_data_by_query('single',$fBQuery); 
							
							$branchId		=	$fBranchData['branch_id'];
							$branchName		=	$fBranchData['branch_name'];
							$branchDisName	=	$fBranchData['branch_display_name'];
							$branchSlug		=	$fBranchData['branch_slug'];
							
							$fBOQuery		=	"SELECT board_id, branch_board_name as board_name
												 FROM sms_branch_boards WHERE franchise_id = '".$result['encrypt_id']."' AND school_id = '".$fSchoolData['school_id']."' AND branch_id = '".$fBranchData['branch_id']."' AND status = 'Y' ORDER BY branch_board_name ASC LIMIT 0,1";  
							$fBoardData		=	$this->common_model->get_data_by_query('single',$fBOQuery); 
							
							$boardId		=	$fBoardData['board_id'];
							$boardName		=	$fBoardData['board_name'];
							
							$userPath		=	base_url().$result['admin_slug'].'/';
							$userName		=	$result['admin_display_name'];
						elseif($result['admin_type'] == 'School'):
							$franchiseId	=	$result['franchise_id'];
							$franchiseName	=	$result['franchise_name'];
							$franchiseDisName=	$result['franchise_display_name'];
							$franchiseSlug	=	$result['franchise_slug'];
							
							$schoolId		=	$result['encrypt_id'];
							$schoolName		=	$result['admin_name'];
							$schoolDisName	=	$result['admin_display_name'];
							$schoolSlug		=	$result['admin_slug'];
							
							$fBQuery		=	"SELECT encrypt_id as branch_id, admin_name as branch_name, admin_display_name as branch_display_name, admin_slug as branch_slug
												 FROM sms_admin WHERE admin_type = 'Branch' AND admin_school_id = '".$result['encrypt_id']."' AND status = 'A' ORDER BY admin_id ASC LIMIT 0,1";  
							$fBranchData	=	$this->common_model->get_data_by_query('single',$fBQuery); 
							
							$branchId		=	$fBranchData['branch_id'];
							$branchName		=	$fBranchData['branch_name'];
							$branchDisName	=	$fBranchData['branch_display_name'];
							$branchSlug		=	$fBranchData['branch_slug'];
							
							$fBOQuery		=	"SELECT board_id, branch_board_name as board_name
												 FROM sms_branch_boards WHERE franchise_id = '".$result['franchise_id']."' AND school_id = '".$result['encrypt_id']."' AND branch_id = '".$fBranchData['branch_id']."' AND status = 'Y' ORDER BY branch_board_name ASC LIMIT 0,1";  
							$fBoardData		=	$this->common_model->get_data_by_query('single',$fBOQuery); 
							
							$boardId		=	$fBoardData['board_id'];
							$boardName		=	$fBoardData['board_name'];
							
							$userPath		=	base_url().$result['admin_slug'].'/';
							$userName		=	$result['admin_display_name'];
						elseif($result['admin_type'] == 'Branch'):
							$franchiseId	=	$result['franchise_id'];
							$franchiseName	=	$result['franchise_name'];
							$franchiseDisName=	$result['franchise_display_name'];
							$franchiseSlug	=	$result['franchise_slug'];
							
							$schoolId		=	$result['school_id'];
							$schoolName		=	$result['school_name'];
							$schoolDisName	=	$result['school_display_name'];
							$schoolSlug		=	$result['school_slug'];
							
							$branchId		=	$result['encrypt_id'];
							$branchName		=	$result['admin_name'];
							$branchDisName	=	$result['admin_display_name'];
							$branchSlug		=	$result['admin_slug'];
							
							$fBOQuery		=	"SELECT board_id, branch_board_name as board_name
												 FROM sms_branch_boards WHERE franchise_id = '".$result['franchise_id']."' AND school_id = '".$result['school_id']."' AND branch_id = '".$result['encrypt_id']."' AND status = 'Y' ORDER BY branch_board_name ASC LIMIT 0,1";  
							$fBoardData		=	$this->common_model->get_data_by_query('single',$fBOQuery); 
							
							$boardId		=	$fBoardData['board_id'];
							$boardName		=	$fBoardData['board_name'];
							
							$userPath		=	base_url().$result['school_slug'].'/'.$result['admin_slug'].'/';
							$userName		=	$result['admin_display_name'];
						elseif($result['admin_type'] == 'Sub admin'):
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
							
							$fBOQuery		=	"SELECT board_id, branch_board_name as board_name
												 FROM sms_branch_boards WHERE franchise_id = '".$result['franchise_id']."' AND school_id = '".$result['school_id']."' AND branch_id = '".$result['branch_id']."' AND status = 'Y' ORDER BY branch_board_name ASC LIMIT 0,1";  
							$fBoardData		=	$this->common_model->get_data_by_query('single',$fBOQuery); 
							
							$boardId		=	$fBoardData['board_id'];
							$boardName		=	$fBoardData['board_name'];
							
							$userPath		=	base_url().$result['school_slug'].'/'.$result['branch_slug'].'/';
							$userName		=	$result['branch_display_name'];
						endif;	
					
						$this->session->set_userdata(array(
												'SMS_ADMIN_LOGGED_IN'=>true,
												'SMS_ADMIN_ID'=>$result['encrypt_id'],
												'SMS_ADMIN_NAME'=>$result['admin_name'],
												'SMS_ADMIN_DISLPLAY_NAME'=>$result['admin_display_name'],
												'SMS_ADMIN_SLUG'=>$result['admin_slug'],
												'SMS_ADMIN_EMAIL'=>$result['admin_email_id'],
												'SMS_ADMIN_MOBILE'=>$result['admin_mobile_number'],
												'SMS_ADMIN_TYPE'=>$result['admin_type'],
												'SMS_ADMIN_EMPLOYEE_ID'=>$result['admin_employee_id'],
												'SMS_ADMIN_DESIGNATION'=>$result['department_name'],
												'SMS_ADMIN_FRANCHISE_ID'=>$franchiseId,
												'SMS_ADMIN_FRANCHISE_NAME'=>$franchiseName,
												'SMS_ADMIN_FRANCHISE_DIS_NAME'=>$franchiseDisName,
												'SMS_ADMIN_FRANCHISE_SLUG'=>$franchiseSlug,
												'SMS_ADMIN_SCHOOL_ID'=>$schoolId,
												'SMS_ADMIN_SCHOOL_NAME'=>$schoolName,
												'SMS_ADMIN_SCHOOL_DIS_NAME'=>$schoolDisName,
												'SMS_ADMIN_SCHOOL_SLUG'=>$schoolSlug,
												'SMS_ADMIN_BRANCH_ID'=>$branchId,
												'SMS_ADMIN_BRANCH_NAME'=>$branchName,
												'SMS_ADMIN_BRANCH_DIS_NAME'=>$branchDisName,
												'SMS_ADMIN_BRANCH_SLUG'=>$branchSlug,
												'SMS_ADMIN_BOARD_ID'=>$boardId,
												'SMS_ADMIN_BOARD_NAME'=>$boardName,
												'SMS_ADMIN_PATH'=>$userPath,
												'SMS_ADMIN_NAME'=>$userName,
												'SMS_ADMIN_LOGO'=>$result['admin_image'],
												'SMS_ADMIN_ADDRESS'=>$result['admin_address'],
												'SMS_ADMIN_LOCALITY'=>$result['admin_locality'],
												'SMS_ADMIN_CITY'=>$result['admin_city'],
												'SMS_ADMIN_STATE'=>$result['admin_state'],
												'SMS_ADMIN_ZIPCODE'=>$result['admin_zipcode'],
												'SMS_ADMIN_LAST_LOGIN'=>$result['admin_last_login'].' ('.$result['admin_last_login_ip'].')'));
						
						$param['admin_last_login']		=	currentDateTime();
						$param['admin_last_login_ip']	=	currentIp();
				
						$this->common_model->edit_data('admin',$param,$result['encrypt_id']);
						
						if($this->input->post('rememberMe') == 'YES'):	
							 setcookie("SMSADMINUserEmail",$this->input->post('userEmail'),time()+60*60*24*100,'/');
							 setcookie("SMSADMINUserPass",$this->input->post('userPassword'),time()+60*60*24*100,'/');
							 setcookie("SMSADMINRememberMe",'YES',time()+60*60*24*100,'/');
						else:
							 setcookie("SMSADMINUserEmail",$this->input->post('userEmail'),time()-60*60*24*100,'/');
							 setcookie("SMSADMINUserPass",$this->input->post('userPassword'),time()-60*60*24*100,'/');
							 setcookie("SMSADMINRememberMe",'YES',time()-60*60*24*100,'/');
						endif;
						
						if($_COOKIE['SMS_ADMIN_REFERENCEPAGES']):
							redirect(base_url().$_COOKIE['SMS_ADMIN_REFERENCEPAGES']);
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
		setcookie('SMS_ADMIN_REFERENCEPAGES', '', time() - 60*60*24*100, '/');
		$this->session->unset_userdata(array('SMS_ADMIN_LOGGED_IN','SMS_ADMIN_ID','SMS_ADMIN_NAME','SMS_ADMIN_DISLPLAY_NAME','SMS_ADMIN_SLUG','SMS_ADMIN_EMAIL','SMS_ADMIN_MOBILE','SMS_ADMIN_TYPE','SMS_ADMIN_EMPLOYEE_ID','SMS_ADMIN_DESIGNATION','SMS_ADMIN_DEPARTMENT','SMS_ADMIN_FRANCHISE_ID','SMS_ADMIN_FRANCHISE_NAME','SMS_ADMIN_FRANCHISE_DIS_NAME','SMS_ADMIN_FRANCHISE_SLUG','SMS_ADMIN_SCHOOL_ID','SMS_ADMIN_SCHOOL_NAME','SMS_ADMIN_SCHOOL_DIS_NAME','SMS_ADMIN_SCHOOL_SLUG','SMS_ADMIN_BRANCH_ID','SMS_ADMIN_BRANCH_NAME','SMS_ADMIN_BRANCH_DIS_NAME','SMS_ADMIN_BRANCH_SLUG','SMS_ADMIN_BOARD_ID','SMS_ADMIN_BOARD_NAME','SMS_ADMIN_PATH','SMS_ADMIN_NAME','SMS_ADMIN_LOGO','SMS_ADMIN_ADDRESS','SMS_ADMIN_LOCALITY','SMS_ADMIN_CITY','SMS_ADMIN_STATE','SMS_ADMIN_ZIPCODE','SMS_ADMIN_LAST_LOGIN'));
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
	
	/***********************************************************************
	** Function name : set_cureent_franchise
	** Developed By : Manoj Kumar
	** Purpose  : This function used for set cureent franchise
	** Date : 25 JANUARY 2018
	************************************************************************/
	function set_cureent_franchise()
	{	
		if($this->input->post('franchiseId')):
			$franchiseId	=	$this->input->post('franchiseId');
			
			$fFQuery		=	"SELECT encrypt_id as franchise_id, admin_name as franchise_name, admin_display_name as franchise_display_name, admin_slug as franchise_slug
								 FROM sms_admin WHERE encrypt_id = '".$franchiseId."' AND admin_type = 'Franchising' AND status = 'A'";  
			$fFchoolData	=	$this->common_model->get_data_by_query('single',$fFQuery); 

			$franchiseId	=	$fFchoolData['franchise_id'];
			$franchiseName	=	$fFchoolData['franchise_name'];
			$franchiseDisName=	$fFchoolData['franchise_display_name'];
			$franchiseSlug	=	$fFchoolData['franchise_slug'];
			
			$this->session->set_userdata(array(
												'SMS_ADMIN_FRANCHISE_ID'=>$franchiseId,
												'SMS_ADMIN_FRANCHISE_NAME'=>$franchiseName,
												'SMS_ADMIN_FRANCHISE_DIS_NAME'=>$franchiseDisName,
												'SMS_ADMIN_FRANCHISE_SLUG'=>$franchiseSlug));
			$this->session->unset_userdata(array(
												'SMS_ADMIN_SCHOOL_ID',
												'SMS_ADMIN_SCHOOL_NAME',
												'SMS_ADMIN_SCHOOL_DIS_NAME',
												'SMS_ADMIN_SCHOOL_SLUG',
												'SMS_ADMIN_BRANCH_ID',
												'SMS_ADMIN_BRANCH_NAME',
												'SMS_ADMIN_BRANCH_DIS_NAME',
												'SMS_ADMIN_BRANCH_SLUG',
												'SMS_ADMIN_BOARD_ID',
												'SMS_ADMIN_BOARD_NAME'));
			echo 'SUCCESS';
		endif;
		
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : set_cureent_school_branch
	** Developed By : Manoj Kumar
	** Purpose  : This function used for set cureent school branch
	** Date : 15 JANUARY 2018
	************************************************************************/
	function set_cureent_school_branch()
	{	
		if($this->input->post('currentSBId')):
			$currentSBId	=	explode('_____',$this->input->post('currentSBId'));
			
			$fSQuery		=	"SELECT encrypt_id as school_id, admin_name as school_name, admin_display_name as school_display_name, admin_slug as school_slug
								 FROM sms_admin WHERE encrypt_id = '".$currentSBId[0]."' AND admin_type = 'School' AND status = 'A'";  
			$fSchoolData	=	$this->common_model->get_data_by_query('single',$fSQuery); 

			$schoolId		=	$fSchoolData['school_id'];
			$schoolName		=	$fSchoolData['school_name'];
			$schoolDisName	=	$fSchoolData['school_display_name'];
			$schoolSlug		=	$fSchoolData['school_slug'];
			
			$fBQuery		=	"SELECT encrypt_id as branch_id, admin_name as branch_name, admin_display_name as branch_display_name, admin_slug as branch_slug
								 FROM sms_admin WHERE encrypt_id = '".$currentSBId[1]."' AND admin_school_id = '".$currentSBId[0]."' AND admin_type = 'Branch' AND status = 'A'";  
			$fBranchData	=	$this->common_model->get_data_by_query('single',$fBQuery); 
			
			$branchId		=	$fBranchData['branch_id'];
			$branchName		=	$fBranchData['branch_name'];
			$branchDisName	=	$fBranchData['branch_display_name'];
			$branchSlug		=	$fBranchData['branch_slug'];
			
			$this->session->set_userdata(array(
												'SMS_ADMIN_SCHOOL_ID'=>$schoolId,
												'SMS_ADMIN_SCHOOL_NAME'=>$schoolName,
												'SMS_ADMIN_SCHOOL_DIS_NAME'=>$schoolDisName,
												'SMS_ADMIN_SCHOOL_SLUG'=>$schoolSlug,
												'SMS_ADMIN_BRANCH_ID'=>$branchId,
												'SMS_ADMIN_BRANCH_NAME'=>$branchName,
												'SMS_ADMIN_BRANCH_DIS_NAME'=>$branchDisName,
												'SMS_ADMIN_BRANCH_SLUG'=>$branchSlug));
			$this->session->unset_userdata(array(
												'SMS_ADMIN_BOARD_ID',
												'SMS_ADMIN_BOARD_NAME')); 
			echo 'SUCCESS';
		endif;
		
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : set_cureent_board
	** Developed By : Manoj Kumar
	** Purpose  : This function used for set cureent board
	** Date : 30 JANUARY 2018
	************************************************************************/
	function set_cureent_board()
	{	
		if($this->input->post('currentBoardId')):
			$currentBoardId	=	$this->input->post('currentBoardId');
			
			$fBQuery		=	"SELECT board_id, branch_board_name as board_name
								 FROM sms_branch_boards WHERE board_id = '".$currentBoardId."' AND status = 'Y'";  
			$fBoardData	=	$this->common_model->get_data_by_query('single',$fBQuery); 

			$fboardId	=	$fBoardData['board_id'];
			$fboardName	=	$fBoardData['board_name'];
			
			$this->session->set_userdata(array(
												'SMS_ADMIN_BOARD_ID'=>$fboardId,
												'SMS_ADMIN_BOARD_NAME'=>$fboardName));
			echo 'SUCCESS';
		endif;
		
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : get_city_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get city data.
	** Date : 19 JANUARY 2018
	************************************************************************/
	function get_city_data()
	{
		$html				=	'';
		if($this->input->post('state')):
			$state			=	$this->input->post('state'); 
			$city			=	$this->input->post('city'); 
			$cityQuery		=	"SELECT city FROM sms_country WHERE county = 'India' AND state = '".$state."' AND city !='' GROUP BY city";
			$cityData		=	$this->common_model->get_data_by_query('multiple',$cityQuery); 
			$html			=	'ERROR';
			if($cityData <> ""):
				$html		=	'<option value="">Select city</option>';
				foreach($cityData as $cityInfo):
					if($city == stripslashes($cityInfo['city'])): $select = 'selected="selected"'; else: $select = ''; endif;
					$html	.=	'<option value="'.stripslashes($cityInfo['city']).'" '.$select.'>'.stripslashes($cityInfo['city']).'</option>';
				endforeach;
			endif;
		endif;
		echo $html; die;			
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : get_locality_data_by_state
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get locality data by state.
	** Date : 19 JANUARY 2018
	************************************************************************/
	function get_locality_data_by_state()
	{
		$html				=	'';
		if($this->input->post('state')):
			$state			=	$this->input->post('state'); 
			$locality		=	$this->input->post('locality'); 
			$localityQuery	=	"SELECT locality FROM sms_country WHERE county = 'India' AND state = '".$state."' AND locality !='' AND city ='' GROUP BY locality";
			$localityData	=	$this->common_model->get_data_by_query('multiple',$localityQuery); 
			$html			=	'ERROR';
			if($localityData <> ""): 
				$html		=	'<option value="">Select locality</option>';
				foreach($localityData as $localityInfo):
					if($locality == stripslashes($localityInfo['locality'])): $select = 'selected="selected"'; else: $select = ''; endif;
					$html	.=	'<option value="'.stripslashes($localityInfo['locality']).'" '.$select.'>'.stripslashes($localityInfo['locality']).'</option>';
				endforeach;
			endif;
		endif;
		echo $html; die;			
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : get_locality_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get locality data.
	** Date : 19 JANUARY 2018
	************************************************************************/
	function get_locality_data()
	{
		$html				=	'';
		if($this->input->post('state') && $this->input->post('city')):
			$state			=	$this->input->post('state'); 
			$city			=	$this->input->post('city'); 
			$locality		=	$this->input->post('locality'); 
			$localityQuery	=	"SELECT locality FROM sms_country WHERE county = 'India' AND state = '".$state."' AND city ='".$city."' AND locality !='' GROUP BY locality";
			$localityData	=	$this->common_model->get_data_by_query('multiple',$localityQuery); 
			$html			=	'ERROR';
			if($localityData <> ""):
				$html		=	'<option value="">Select locality</option>';
				foreach($localityData as $localityInfo):
					if($locality == stripslashes($localityInfo['locality'])): $select = 'selected="selected"'; else: $select = ''; endif;
					$html	.=	'<option value="'.stripslashes($localityInfo['locality']).'" '.$select.'>'.stripslashes($localityInfo['locality']).'</option>';
				endforeach;
			endif;
		endif;
		echo $html; die;			
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : get_zipcode_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get zipcode data.
	** Date : 19 JANUARY 2018
	************************************************************************/
	function get_zipcode_data()
	{
		$html				=	'';
		if($this->input->post('state') && $this->input->post('locality')):
			$state			=	$this->input->post('state'); 
			$city			=	$this->input->post('city'); 
			$locality		=	$this->input->post('locality');
			$zip			=	$this->input->post('zip'); 
			if($city):	$cityq	=	" AND city ='".$city."'";	else:	$cityq	=	""; endif;
			$zipQuery		=	"SELECT zip_code FROM sms_country WHERE county = 'India' AND state = '".$state."' ".$cityq." AND locality ='".$locality."' GROUP BY zip_code";
			$zipData		=	$this->common_model->get_data_by_query('single',$zipQuery); 
			$html			=	'ERROR';
			if($zipData <> ""):
				$html		=	$zipData['zip_code'];
			endif;
		endif;
		echo $html; die;			
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name : get_section_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get section data.
	** Date : 01 FEBRUARY 2018
	************************************************************************/
	function get_section_data()
	{
		$html				=	'';
		if($this->input->post('classid')):
			$classid		=	$this->input->post('classid'); 
			$sectionid		=	$this->input->post('sectionid'); 

			$sectionQuery	=	"SELECT encrypt_id,class_section_name FROM sms_class_section 
							     WHERE franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
								 AND school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
								 AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
								 AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."'
								 AND class_id = '".$classid."' 
								 AND status = 'Y'";
			$sectionData	=	$this->common_model->get_data_by_query('multiple',$sectionQuery); 
			$html			=	'ERROR';
			if($sectionData <> ""):
				$html		=	'<option value="">Select section name</option>';
				foreach($sectionData as $sectionInfo):
					if($sectionid == stripslashes($sectionInfo['encrypt_id'])): $select = 'selected="selected"'; else: $select = ''; endif;
					$html	.=	'<option value="'.stripslashes($sectionInfo['encrypt_id']).'" '.$select.'>'.stripslashes($sectionInfo['class_section_name']).'</option>';
				endforeach;
			endif;
		endif;
		echo $html; die;			
	}	// END OF FUNCTION
        
        
	/***********************************************************************
	** Function name : get_section_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get section data.
	** Date : 01 FEBRUARY 2018
	************************************************************************/
	function get_student_data()
	{
		$html				=	'';
		if(($this->input->post('classid')) && ($this->input->post('sectionid') )):
			$classid		=	$this->input->post('classid'); 
			$sectionid		=	$this->input->post('sectionid'); 
            $studentid		=	$this->input->post('studentid'); 

			$studentQuery	=	"SELECT stud.student_f_name ,stud.student_m_name ,stud.student_l_name  ,CONCAT(stud.student_f_name,' ',stud.student_m_name,' ',stud.student_l_name) AS name, sc.student_qunique_id FROM `sms_student_class` AS sc LEFT JOIN `sms_students` AS stud ON stud.student_qunique_id =  sc.student_qunique_id
							     WHERE sc.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
								 AND sc.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
								 AND sc.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
								 AND sc.board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."'
                                 AND sc.section_id = '".$sectionid."' AND stud.status = 'A'
								 AND sc.class_id = '".$classid."'";
                        
			$studentData	=	$this->common_model->get_data_by_query('multiple',$studentQuery); 
			$html			=	'ERROR'; 
			if($studentData <> ""):
				$html		=	'<option value="">Select student </option>';
                            if($studentid == 'All'): $select = 'selected="selected"'; else: $select = ''; endif;
					$html	.=	'<option value="All" '.$select.'>All</option>';
				foreach($studentData as $studentInfo):
					if($studentid == stripslashes($studentInfo['student_qunique_id'])): $select = 'selected="selected"'; else: $select = ''; endif;
					$html	.=	'<option value="'.stripslashes($studentInfo['student_qunique_id']).'" '.$select.'>'.stripslashes($studentInfo['name']).'</option>';
				endforeach;
			endif;
		endif;
		echo $html; die;			
	}
	/***********************************************************************
	** Function name : get_section_wise_teacher_data
	** Developed By : Ashish UMrao
	** Purpose  : This function used for get section data.
	** Date : 30 OCTOBER 2019
	************************************************************************/
	function get_teacher_data()
	{ //echo "AAA"; die;
		$html				=	'';
		//echo $this->input->post('classid'); die;
		if(($this->input->post('classid')) && ($this->input->post('sectionid') )):
			

			$classid		=	$this->input->post('classid'); 
			$sectionid		=	$this->input->post('sectionid');
			//echo "<pre>"; echo $classid.'-----'.$sectionid; die; 
            $teacherid		=	$this->input->post('teacherid'); 
			$teacherQuery	=	"SELECT u.user_f_name ,u.user_m_name ,u.user_l_name,CONCAT(u.user_f_name,' ',u.user_m_name,' ',u.user_l_name) AS teacherName,u.encrypt_id as teacherID FROM `sms_class_section` AS steacher 
								LEFT JOIN `sms_users` AS u ON steacher.class_teacher_id =  u.encrypt_id
							     WHERE steacher.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
								 AND steacher.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
								 AND steacher.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
								 AND steacher.board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' 
                                 AND u.status = 'A' AND u.user_type = 'Teacher' AND steacher.encrypt_id = '".$sectionid."' 
								 AND steacher.class_id = '".$classid."'";
            //echo $studentQuery; die;      
			$teacherData	=	$this->common_model->get_data_by_query('multiple',$teacherQuery); 
			//echo "<pre>"; print_r($teacherData); die;
			$html			=	'ERROR'; 
			if($teacherData <> ""):
				$html		=	'<option value="">Select Teacher </option>';
				foreach($teacherData as $teacherInfo):
					if($teacherid == stripslashes($teacherInfo['teacherID'])): $select = 'selected="selected"'; else: $select = ''; endif;
					$html	.=	'<option value="'.stripslashes($teacherInfo['teacherID']).'" '.$select.'>'.stripslashes($teacherInfo['teacherName']).'</option>';
				endforeach;
			endif;
		endif;
		echo $html; die;			
	}

	/***********************************************************************
	** Function name : get_school_data
	** Developed By : Ashish UMrao
	** Purpose  : This function used for get school data.
	** Date : 18 MARCH 2020
	************************************************************************/
	function get_school_data()
	{
		$html				=	'';
		if($this->input->post('franchiesid')): //echo $this->input->post('franchiesid'); die;
			$franchiesid	=	$this->input->post('franchiesid'); 
			$schoolid		=	$this->input->post('schoolid'); 

			$schoolQuery	=	"SELECT admin_id,encrypt_id,admin_name,admin_display_name FROM sms_admin WHERE admin_franchise_id = $franchiesid 
								 AND admin_type = 'School' AND status = 'A'";
			$schoolData	=	$this->common_model->get_data_by_query('multiple',$schoolQuery); 
			//print_r($schoolData); die;
			$html			=	'ERROR';
			if($schoolData <> ""):
				$html		=	'<option value="">Select School</option>';
				foreach($schoolData as $schoolDataInfo): //echo "<pre>"; print_r($schoolData); die;
					if($schoolid == stripslashes($schoolDataInfo['encrypt_id'])): $select = 'selected="selected"'; else: $select = ''; endif;
					$html	.=	'<option value="'.stripslashes($schoolDataInfo['encrypt_id']).'" '.$select.'>'.stripslashes($schoolDataInfo['admin_name']).'</option>';
				endforeach;
			endif;
		endif;
		echo $html; die;			
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name : get_branch_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get section data.
	** Date : 01 FEBRUARY 2018
	************************************************************************/
	function get_branch_data()
	{  //echo $this->input->post('schoolid'); die;
		$html				=	'';
		if(($this->input->post('franchiesid')) && ($this->input->post('schoolid') )):
			$franchiesid		=	$this->input->post('franchiesid'); 
			$schoolid		=	$this->input->post('schoolid'); 
            $branchid		=	$this->input->post('branchid'); 
			//echo $franchiesid.'---'.$schoolId.'----'.$branchId; die;
			$branchQuery	=	"SELECT admin_id,encrypt_id,admin_name,admin_display_name FROM sms_admin WHERE admin_franchise_id = $franchiesid 
								AND admin_school_id = $schoolid AND admin_type = 'Branch' AND status = 'A'";
			$branchData	=	$this->common_model->get_data_by_query('multiple',$branchQuery); 
			//echo "<pre>"; print_r($branchData); die;
			$html			=	'ERROR'; 
			if($branchData <> ""):
				$html		=	'<option value="">Select Branch </option>';
					
				foreach($branchData as $branchInfo):
					if($branchid == stripslashes($branchInfo['encrypt_id'])): $select = 'selected="selected"'; else: $select = ''; endif;
					$html	.=	'<option value="'.stripslashes($branchInfo['encrypt_id']).'" '.$select.'>'.stripslashes($branchInfo['admin_name']).'</option>';
				endforeach;
			endif;
		endif;
		echo $html; die;			
	}

}
