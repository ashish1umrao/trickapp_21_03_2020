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
		if($this->session->userdata('SMS_LIBRARY_ADMIN_ID')) redirect($this->session->userdata('SMS_LIBRARY_ADMIN_PATH').'dashboard');
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
					
					elseif($result['user_type'] == 'Librarian' && $result['franchise_status'] != 'A' && $result['school_status'] != 'A' && $result['branch_status'] != 'A'):	
						$data['error'] = lang('accountblock');	
					else:	
						
						if($result['user_type'] == 'Librarian'):
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
							 
							
							$userPath		=	base_url().$result['school_slug'].'/'.$result['branch_slug'].'/';
							$userName		=	$result['user_f_name'];
						endif;                                           
					
						$this->session->set_userdata(array(
												'SMS_LIBRARY_ADMIN_LOGGED_IN'=>true,
												'SMS_LIBRARY_ADMIN_ID'=>$result['encrypt_id'],
												'SMS_LIBRARY_ADMIN_NAME'=>$result['user_f_name'],
												'SMS_LIBRARY_ADMIN_DISLPLAY_NAME'=>$result['user_f_name'],
												'SMS_LIBRARY_ADMIN_SLUG'=>$result['branch_slug'],
												'SMS_LIBRARY_ADMIN_EMAIL'=>$result['user_email'],
												'SMS_LIBRARY_ADMIN_MOBILE'=>$result['user_mobile'],
												'SMS_LIBRARY_ADMIN_TYPE'=>$result['user_type'],
												'SMS_LIBRARY_ADMIN_EMPLOYEE_ID'=>$result['employee_id'],
												'SMS_LIBRARY_ADMIN_DESIGNATION'=>$result['department_name'],
												'SMS_LIBRARY_ADMIN_FRANCHISE_ID'=>$franchiseId,
												'SMS_LIBRARY_ADMIN_FRANCHISE_NAME'=>$franchiseName,
												'SMS_LIBRARY_ADMIN_FRANCHISE_DIS_NAME'=>$franchiseDisName,
												'SMS_LIBRARY_ADMIN_FRANCHISE_SLUG'=>$franchiseSlug,
												'SMS_LIBRARY_ADMIN_SCHOOL_ID'=>$schoolId,
												'SMS_LIBRARY_ADMIN_SCHOOL_NAME'=>$schoolName,
												'SMS_LIBRARY_ADMIN_SCHOOL_DIS_NAME'=>$schoolDisName,
												'SMS_LIBRARY_ADMIN_SCHOOL_SLUG'=>$schoolSlug,
												'SMS_LIBRARY_ADMIN_BRANCH_ID'=>$branchId,
												'SMS_LIBRARY_ADMIN_BRANCH_NAME'=>$branchName,
												'SMS_LIBRARY_ADMIN_BRANCH_DIS_NAME'=>$branchDisName,
												'SMS_LIBRARY_ADMIN_BRANCH_SLUG'=>$branchSlug,
												'SMS_LIBRARY_ADMIN_BOARD_ID'=>$boardId,
												'SMS_LIBRARY_ADMIN_BOARD_NAME'=>$boardName,
												'SMS_LIBRARY_ADMIN_PATH'=>$userPath,
												'SMS_LIBRARY_ADMIN_NAME'=>$userName,
												'SMS_LIBRARY_ADMIN_LOGO'=>$result['admin_image'],
												'SMS_LIBRARY_ADMIN_ADDRESS'=>$result['user_c_address'],
												'SMS_LIBRARY_ADMIN_LOCALITY'=>$result['user_c_locality'],
												'SMS_LIBRARY_ADMIN_CITY'=>$result['user_c_city'],
												'SMS_LIBRARY_ADMIN_STATE'=>$result['user_c_state'],
												'SMS_LIBRARY_ADMIN_ZIPCODE'=>$result['user_c_zipcode'],
												'SMS_LIBRARY_ADMIN_LAST_LOGIN'=>$result['user_last_login'].' ('.$result['user_last_login_ip'].')'));
						
						$param['user_last_login']		=	currentDateTime();
						$param['user_last_login_ip']	=	currentIp();
				
						$this->common_model->edit_data('users',$param,$result['encrypt_id']);
						
						if($this->input->post('rememberMe') == 'YES'):	
							 setcookie("SMSLIBRARYADMINUserEmail",$this->input->post('userEmail'),time()+60*60*24*100,'/');
							 setcookie("SMSLIBRARYADMINUserPass",$this->input->post('userPassword'),time()+60*60*24*100,'/');
							 setcookie("SMSLIBRARYADMINRememberMe",'YES',time()+60*60*24*100,'/');
						else:
							 setcookie("SMSLIBRARYADMINUserEmail",$this->input->post('userEmail'),time()-60*60*24*100,'/');
							 setcookie("SMSLIBRARYADMINUserPass",$this->input->post('userPassword'),time()-60*60*24*100,'/');
							 setcookie("SMSLIBRARYADMINRememberMe",'YES',time()-60*60*24*100,'/');
						endif;
						
						if($_COOKIE['SMS_LIBRARY_ADMIN_REFERENCEPAGES']):
							redirect(base_url().$_COOKIE['SMS_LIBRARY_ADMIN_REFERENCEPAGES']);
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
		setcookie('SMS_LIBRARY_ADMIN_REFERENCEPAGES', '', time() - 60*60*24*100, '/');
		$this->session->unset_userdata(array('SMS_LIBRARY_ADMIN_LOGGED_IN','SMS_LIBRARY_ADMIN_ID','SMS_LIBRARY_ADMIN_NAME','SMS_LIBRARY_ADMIN_DISLPLAY_NAME','SMS_LIBRARY_ADMIN_SLUG','SMS_LIBRARY_ADMIN_EMAIL','SMS_LIBRARY_ADMIN_MOBILE','SMS_LIBRARY_ADMIN_TYPE','SMS_LIBRARY_ADMIN_EMPLOYEE_ID','SMS_LIBRARY_ADMIN_DESIGNATION','SMS_LIBRARY_ADMIN_FRANCHISE_ID','SMS_LIBRARY_ADMIN_FRANCHISE_NAME','SMS_LIBRARY_ADMIN_FRANCHISE_DIS_NAME','SMS_LIBRARY_ADMIN_FRANCHISE_SLUG','SMS_LIBRARY_ADMIN_SCHOOL_ID','SMS_LIBRARY_ADMIN_SCHOOL_NAME','SMS_LIBRARY_ADMIN_SCHOOL_DIS_NAME','SMS_LIBRARY_ADMIN_SCHOOL_SLUG','SMS_LIBRARY_ADMIN_BRANCH_ID','SMS_LIBRARY_ADMIN_BRANCH_NAME','SMS_LIBRARY_ADMIN_BRANCH_DIS_NAME','SMS_LIBRARY_ADMIN_BRANCH_SLUG','SMS_LIBRARY_ADMIN_BOARD_ID','SMS_LIBRARY_ADMIN_BOARD_NAME','SMS_LIBRARY_ADMIN_PATH','SMS_LIBRARY_ADMIN_NAME','SMS_LIBRARY_ADMIN_LOGO','SMS_LIBRARY_ADMIN_ADDRESS','SMS_LIBRARY_ADMIN_LOCALITY','SMS_LIBRARY_ADMIN_CITY','SMS_LIBRARY_ADMIN_STATE','SMS_LIBRARY_ADMIN_ZIPCODE','SMS_LIBRARY_ADMIN_LAST_LOGIN'));
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
	** Function name : get_section_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get section data.
	** Date : 01 FEBRUARY 2018
	************************************************************************/
	function get_shelf_row_data()
	{
		$html				=	'';
		if($this->input->post('shelfid')):
			$shelfid		=	$this->input->post('shelfid'); 
			$shelfrowid		=	$this->input->post('shelfrowid'); 
                       
                       
			$rowQuery	=	"SELECT encrypt_id,shelf_row ,max_books FROM sms_lib_shelf_row
							     WHERE franchise_id = '".$this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID')."' 
								 AND school_id = '".$this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID')."' 
								 AND branch_id = '".$this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID')."' 
								
								 AND shelf_id = '".$shelfid."'";
			$rowData	=	$this->common_model->get_data_by_query('multiple',$rowQuery); 
                        
                        
                         
			$html			=	'ERROR';
                        
			if($rowData <> ""):
                           
				$html		=	'<option value="">Select shelf row  ( Available Book slots )  </option>';
                              
				foreach($rowData as $rowInfo):
                                      $countQuery             = "SELECT COUNT(*) as count FROM `sms_lib_barcode` WHERE (  shelf_row_id = '" .$rowInfo['encrypt_id']. "'  AND STATUS='Y')";
        $existingBookShelfArray = $this->common_model->get_data_by_query('single', $countQuery);
        $existingBookShelf      = $existingBookShelfArray['count'];
                     $av_books   =      $rowInfo['max_books'] -    $existingBookShelf;         
                                    
					if($shelfrowid == stripslashes($rowInfo['encrypt_id'])): $select = 'selected="selected"'; else: $select = ''; endif;
                                     
					$html	.=	'<option value="'.stripslashes($rowInfo['encrypt_id']).'" '.$select.'>'.stripslashes($rowInfo['shelf_row']).'(  ' .$av_books.'   books )</option>';
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
							     WHERE sc.franchise_id = '".$this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID')."' 
								 AND sc.school_id = '".$this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID')."' 
								 AND sc.branch_id = '".$this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID')."' 
								 AND sc.board_id = '".$this->session->userdata('SMS_LIBRARY_ADMIN_BOARD_ID')."'
                                                                     AND sc.section_id = '".$classid."'
                                                                            AND stud.status = 'A'
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
}
