<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Techerdevprogram extends CI_Controller {

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
	 * * Function name : techerdevprogram
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for techerdevprogram
	 * * Date : 23 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(sdpro.develop_program_title LIKE '%".$sValue."%' OR sdpro.develop_program_speaker LIKE '%".$sValue."%' 
			                                      OR sdpro.develop_program_content LIKE '%".$sValue."%' OR sdpro.develop_program_date LIKE '%".$sValue."%'
												  OR sdpro.develop_program_time LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"sdpro.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
												 AND sdpro.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
												 AND sdpro.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";		
		$shortField 						= 	'sdpro.develop_program_id DESC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('techerDevProgramAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'user_develop_program as sdpro';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectTDevProgramData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectTDevProgramData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage devlopment program details');
		$this->layouts->admin_view('techerdevprogram/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 23 JANUARY 2018
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{		
		$data['error'] 				= 	'';
		
		$timeQuery					=	"SELECT time_name FROM sms_time WHERE status = 'Y'";
		$data['TIMEDATA']			=	$this->common_model->get_data_by_query('multiple',$timeQuery);
		
		$teacherQuery				=	"SELECT user_f_name,user_m_name,user_l_name,user_email FROM sms_users WHERE user_type = 'Teacher' AND school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' AND status = 'A'";
		$data['TEACHERDATA']		=	$this->common_model->get_data_by_query('multiple',$teacherQuery);  
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('user_develop_program',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';  
			$this->form_validation->set_rules('develop_program_title', 'Title', 'trim|required');
			$this->form_validation->set_rules('develop_program_date', 'Date', 'trim|required');
			$this->form_validation->set_rules('develop_program_time', 'Time', 'trim|required');
			$this->form_validation->set_rules('develop_program_speaker', 'Teacher', 'trim|required');
			$this->form_validation->set_rules('develop_program_content', 'Content', 'trim|required');
			
			if($this->input->post('develop_program_speaker') == 'Oteher'):
				$this->form_validation->set_rules('develop_program_other_speaker', 'Other teacher name', 'trim|required');
			endif;
			
			if($this->form_validation->run() && $error == 'NO'): 
			
				$param['develop_program_title']		= 	addslashes($this->input->post('develop_program_title'));
				$param['develop_program_date']		= 	DDMMYYtoYYMMDD($this->input->post('develop_program_date'));
				$param['develop_program_time']		= 	addslashes($this->input->post('develop_program_time'));
				if($this->input->post('develop_program_speaker') == 'Oteher'):
					$param['develop_program_speaker']= 	addslashes($this->input->post('develop_program_speaker'));
					$param['develop_program_other_speaker']= 	addslashes($this->input->post('develop_program_other_speaker'));
				else:
					$param['develop_program_speaker']= 	addslashes($this->input->post('develop_program_speaker'));
					$param['develop_program_other_speaker']= 	'';
				endif;
				$param['develop_program_content']	= 	addslashes($this->input->post('develop_program_content'));
				
				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
                                                        $param['session_year']			=	CURRENT_SESSION;
					$alastInsertId				=	$this->common_model->add_data('user_develop_program',$param);
					
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['develop_program_id']=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('user_develop_program',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$TDPROGId					=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('user_develop_program',$param,$TDPROGId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('techerDevProgramAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit devlopment program details');
		$this->layouts->admin_view('techerdevprogram/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 23 JANUARY 2018
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('user_develop_program',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('techerDevProgramAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
}
