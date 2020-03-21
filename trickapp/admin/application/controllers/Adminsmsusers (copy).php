<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Adminsmsusers extends CI_Controller {
	public function  __construct()  
	
	{  
		parent:: __construct();
		$this->load->helper(array('form','url','html','path','form','cookie'));
		$this->load->library(array('email','session','form_validation','pagination','parser','encrypt'));
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->library("layouts");
		$this->load->model(array('admin_model','common_model','emailtemplate_model','sms_model','smsadmin_model'));
		$this->load->helper('language');
		$this->lang->load('statictext', 'admin');
	}
	
	/* * *********************************************************************
	 * * Function name : index
	 * * Developed By : Ashish UMrao
	 * * Purpose  : This function used for branch
	 * * Date : 14 march 2020
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			//$whereCon['like']		 		= 	"(sas.admin_name LIKE '%".$sValue."%' 
			                   					  //OR sas.admin_display_name LIKE '%".$sValue."%' 
												  //OR sas.status LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		//$whereCon['where']		 			= 	"sas.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' AND sas.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'";		
		//$shortField 						= 	'sas.admin_name ASC';

			////////// get all listed users

			$authkey				= 	RESSLER_AUTH_KEY;
			$slug_url			= 	'users.php';
			$sms_slug_url       = "$slug_url?authkey=".$authkey."";
			$returnuserid   	=   add_user($sms_slug_url);
			$returndata   		=  (json_decode($returnuserid)); 

			foreach($returndata as $data1): //echo "<pre>";print_r($returndata); die;
				$checkauthkey   = $this->smsadmin_model->checkauthkeyexist($data1->auth_key);
				if($checkauthkey == ''):
					$param['authkey']			= 	$data1->auth_key;
					$param['full_name']			= 	addslashes($data1->name);
					$param['username']		    = 	addslashes($data1->username);
					$param['email']				= 	(addslashes($data1->email_address));
					$param['mobile']		    = 	addslashes($data1->contact_number);
					$param['user_id']			= 	addslashes($data1->user_id);
					$param['expirydate']		= 	addslashes($data1->expiry_date);
					$param['status']			= 	addslashes($data1->user_status);
					$param['created_date']		= 	currentDateTime();
					$blastInsertId				=	$this->common_model->add_data('sms_admin_user_sms',$param);
				endif;
			endforeach;	
			//end

		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('smsAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'sms_admin_user_sms as sas';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->smsadmin_model->SelectsmsAdminData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->smsadmin_model->SelectsmsAdminData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 
		
		$this->layouts->set_title('Manage Sms Student Details');
		$this->layouts->admin_view('adminsmsusers/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Ashish UMrao
	 * * Purpose  : This function used for add edit data
	 * * Date : 14 march 2020
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{		//echo $editid; die;
		$data['error'] 				= 	'';
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_userId('sms_admin_user_sms',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
				$subQuery          = "SELECT admin_id,encrypt_id,admin_name,admin_display_name FROM sms_admin WHERE admin_type = 'Franchising'";
		//print $subQuery; die;
				$data['FRANCHISING'] = $this->common_model->get_data_by_query('multiple', $subQuery);

		if($this->input->post('SaveChanges')): //echo "<pre>"; print_r($_POST); die;
			$error					=	'NO';
			$this->form_validation->set_rules('fullname', 'Name', 'trim|required');
			$this->form_validation->set_rules('username', 'User name', 'trim|required');
			$this->form_validation->set_rules('email', 'E-Mail', 'trim|required');
			$this->form_validation->set_rules('mobile', 'Mobile number', 'trim|required|min_length[10]|max_length[15]');
			$testmobile		=	str_replace(' ','',$this->input->post('mobile'));
			if($this->input->post('mobile') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testmobile)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testmobile)):
					$error						=	'YES';
					$data['s_mobileerror'] 		= 	'Please eneter correct number.';
				endif;
			endif;
			
			$this->form_validation->set_rules('company', 'Company', 'trim|required');
			$this->form_validation->set_rules('industry', 'industry', 'trim|required');
			$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):   
			
			// New user Registration

				$authkey				= 	RESSLER_AUTH_KEY;
				$fullname			= 	addslashes($this->input->post('fullname'));
				$username	        = 	addslashes($this->input->post('username'));
				$email				= 	(addslashes($this->input->post('email')));
				$mobile		        = 	addslashes($this->input->post('mobile'));
				$company		    = 	addslashes($this->input->post('company'));
				$industry			= 	addslashes($this->input->post('industry'));
				$expiry_date		= 	YYMMDDtoDDMMYY(addslashes($this->input->post('expiry_date')));
				$slug_url			= 	'add_user.php';
				$sms_slug_url       = "$slug_url?authkey=".$authkey."&full_name=".$fullname."&username=".$username."&mobile=".$mobile."&email=".$email."&company=".$company."&industry=".$industry."&expiry=".$expiry_date."";
				$returnuserid   	=   add_user($sms_slug_url);
				$returndata   		=  json_decode($returnuserid); 
			//	

					$param['full_name']		= 	addslashes($this->input->post('fullname'));
					$param['username']		= 	addslashes($this->input->post('username'));
					$param['mobile']		= 	addslashes($this->input->post('mobile'));
					$param['email']			= 	addslashes($this->input->post('email'));
					$param['company']		= 	addslashes($this->input->post('company'));
					$param['industry']		= 	addslashes($this->input->post('industry'));
					$param['expirydate']	= 	addslashes($this->input->post('expiry_date'));

					echo "<pre>"; print_r($param); die;
				//  Get All listed Users
				if($this->input->post('CurrentDataID') ==''):
					// $param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					// $param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					// $param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					// $param['current_year']		=	date('Y');
					// $param['creation_date']		=	currentDateTime();
					// $param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					// $param['status']			=	'Y';
                    // $param['session_year']		=	CURRENT_SESSION;
					// $alastInsertId				=	$this->common_model->add_data('news',$param);					
					// $Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					// $Uwhere['news_id']			=	$alastInsertId;
					// $this->common_model->edit_data_by_multiple_cond('news',$Uparam,$Uwhere);
					// $this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$userId						=	$this->input->post('CurrentDataID');
					$params['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->smsadmin_model->edit_data_sms('sms_admin_user_sms',$param,$userId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
            /////////////////////////////////////////////////
			//	redirect(correctLink('smsAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit sms student details');
		$this->layouts->admin_view('adminsmsusers/addeditdata',array(),$data); 
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Ashish UMrao
	** Purpose  : This function used for change status
	** Date : 17 march 2020
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='') 
	{  
		$this->admin_model->authCheck('admin','edit_data');

				$params['status']		=	$statusType;

				$status					=	$statusType;
				$authkey				= 	RESSLER_AUTH_KEY;
				$user_id				= 	$changeStatusId;
				$slug_url				= 	'manage_user.php';
				$sms_slug_url       	=   "$slug_url?authkey=".$authkey."&user_id=".$changeStatusId."&status=".$status."";
				$returnuserid   		=   add_user($sms_slug_url);
				$returndata   			=  json_decode($returnuserid);
				//print_r($returndata->type); die; 
		if($returndata->type  == "success"):
			$this->smsadmin_model->edit_data_sms('sms_admin_user_sms',$params,$changeStatusId);
		if($statusType == "1"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		elseif($statusType == "2"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		elseif($statusType == "D"):
			$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		endif;
	endif;
		redirect(correctLink('smsAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
	
	/***********************************************************************
	** Function name : addeditUserBalence 
	** Developed By : Ashish UMrao
	** Purpose  : This function used for update User Balence
	** Date : 17 march 2020
	************************************************************************/
	function addeditUserBalence($userId = '')
	{  //echo $userId; die;
		$data['error']          = '';
        $data['studentId']      = $userId;
        if (!$userId):
            redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
        endif;
		if($userId):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_userId('sms_admin_user_sms',$userId);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
        if ($data['EDITDATA'] <> ""):
            $this->admin_model->authCheck('admin', 'edit_data');
        else:
            $this->admin_model->authCheck('admin', 'add_data');
        endif;
        if ($this->input->post('SaveChanges')): //echo "<pre>"; print_r($_POST); die;
            $error = 'NO';
            $this->form_validation->set_rules('sms', 'sms', 'trim|required');
            $this->form_validation->set_rules('account_type', 'Account Type', 'trim|required');
            $this->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->form_validation->set_rules('price', 'Price', 'trim|required');
            $this->form_validation->set_rules('Description', 'Description', 'trim|required');
            

            if ($this->form_validation->run() && $error == 'NO'):
               // New user Registration

				$authkey				= 	RESSLER_AUTH_KEY;
				$user_id				= 	$data['studentId'];
				$sms	        		= 	addslashes($this->input->post('sms'));
				$account_type			= 	addslashes($this->input->post('account_type'));
				$type		        	= 	addslashes($this->input->post('type'));
				$price		    		= 	addslashes($this->input->post('price'));
				$Description			= 	addslashes($this->input->post('Description'));
				$slug_url				= 	'transfer_balance.php';
				$sms_slug_url       	= "$slug_url?authkey=".$authkey."&user_id=".$userId."&sms=".$sms."&account_type=".$account_type."&type=".$type."&price=".$price."&description=".$Description."";
				$returnuserid   		=   add_user($sms_slug_url);
				$returndata   			=  json_decode($returnuserid); 

                if ($this->input->post('CurrentDataID') == ''):
                    $Param['franchise_id']       = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
                    $Param['school_id']          = $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
                    $Param['branch_id']          = $this->session->userdata('SMS_ADMIN_BRANCH_ID');
                    $Param['board_id']           = $this->session->userdata('SMS_ADMIN_BOARD_ID');
                    $Param['student_qunique_id'] = $data['studentId'];

                    $Param['creation_date'] = currentDateTime();
                    $Param['created_by']    = $this->session->userdata('SMS_ADMIN_ID');
                    $Param['status']        = 'Y';
                      $Param['session_year']			=	CURRENT_SESSION;
                    $stuaddlastInsertId     = $this->common_model->add_data('student_address', $Param);

                    $Sparam['encrypt_id']         = manojEncript($stuaddlastInsertId);
                    $Swhere['student_address_id'] = $stuaddlastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('student_address', $Sparam, $Swhere);

                    $this->session->set_flashdata('alert_success', lang('addsuccess'));
                else:
                    $stuaddlastInsertId = $this->input->post('CurrentDataID');

                    $Param['update_date'] = currentDateTime();
                    $Param['updated_by']  = $this->session->userdata('SMS_ADMIN_ID');
                    $Where['encrypt_id']  = $stuaddlastInsertId;
                    $this->common_model->edit_data_by_multiple_cond('student_address', $Param, $Where);

                    $this->session->set_flashdata('alert_success', lang('updatesuccess'));
                endif;
                redirect($this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/addeditaddress/' . $data['studentId']);
            endif;
        endif;

        $this->layouts->set_title('Add/edit Sms User details');
        $this->layouts->admin_view('adminsmsusers/addeditUserBalence', array(), $data);
	}
	/***********************************************************************
	** Function name : addedittransctionhistory 
	** Developed By : Ashish UMrao
	** Purpose  : This function used for add edit transction history
	** Date : 17 march 2020
	************************************************************************/
	function addedittransctionhistory($userId = '')
	{  //echo $userId; die;
		$data['error']          = '';
        $data['studentId']      = $userId;
        if (!$userId):
            redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
		endif;
		if($userId):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_userId('sms_admin_user_sms',$userId);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		$authkey			= 	RESSLER_AUTH_KEY;
		$slug_url			= 	'transactions.php';
		$sms_slug_url       = "$slug_url?authkey=".$authkey."&user_id=".$userId."";
		$returnuserid   	=   add_user($sms_slug_url);
		$returndata   		=  (json_decode($returnuserid)); 
		//echo "<pre>"; print_r($returndata); die; 
        $this->layouts->set_title('Add/edit add edit transction history');
        $this->layouts->admin_view('adminsmsusers/addedittransctionhistory', array(), $data);
	}
	/***********************************************************************
	** Function name : addedittransctionhistory 
	** Developed By : Ashish UMrao
	** Purpose  : This function used for add edit transction history
	** Date : 17 march 2020
	************************************************************************/
	function addeditbalancehistory($userId = '')
	{  //echo $userId; die;
		$data['error']          = '';
        $data['studentId']      = $userId;
        if (!$userId):
            redirect(correctLink('studentListAdminData', $this->session->userdata('SMS_ADMIN_PATH') . $this->router->fetch_class() . '/index'));
		endif;
		if($userId):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_userId('sms_admin_user_sms',$userId);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		$authkey			= 	RESSLER_AUTH_KEY;
		$slug_url			= 	'all_user_balance.php';
		$sms_slug_url       = "$slug_url?authkey=".$authkey."&user_id=".$userId."";
		$returnuserid   	=   add_user($sms_slug_url);
		$returndata   		=  (json_decode($returnuserid)); 
		//echo "<pre>"; print_r($returndata); die; 
        $this->layouts->set_title('Add/edit add edit Balance history');
        $this->layouts->admin_view('adminsmsusers/addeditbalancehistory', array(), $data);
    }
	
}
