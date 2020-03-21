<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subjectlist extends CI_Controller {

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
	 * * Function name : subjectlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for subjectlist
	 * * Date : 16 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(sub.subject_name LIKE '%".$sValue."%' OR sub.subject_short_name LIKE '%".$sValue."%' 
			                                      OR subh.subject_head_name LIKE '%".$sValue."%' OR subh.subject_head_short_name LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"sub.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."'
												 AND sub.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
												 AND sub.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
												 AND sub.board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."'";		
		$shortField 						= 	'sub.subject_id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('subjectListAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'subject as sub';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectSubjectListData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectSubjectListData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage subject details');
		$this->layouts->admin_view('subjectlist/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 16 JANUARY 2018
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{		
		$data['error'] 				= 	'';
		
		$desgQuery					=	"SELECT encrypt_id, subject_head_name
									 	FROM sms_subject_head
									 	WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' AND status='Y'";
		$data['SHEADDATA']			=	$this->common_model->get_data_by_query('multiple',$desgQuery);
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('subject',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';  
			$this->form_validation->set_rules('subject_head_id', 'Subject head name', 'trim');
			$this->form_validation->set_rules('subject_name', 'Subject name', 'trim|required');
			$this->form_validation->set_rules('subject_short_name', 'Subject short name', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'): 
			
				$param['subject_head_id']		= 	addslashes($this->input->post('subject_head_id'));
				$param['subject_name']			= 	addslashes($this->input->post('subject_name'));
				$param['subject_short_name']	= 	addslashes($this->input->post('subject_short_name'));
				
				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
                                           $param['session_year']			=	CURRENT_SESSION;
					$alastInsertId				=	$this->common_model->add_data('subject',$param);
					
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['subject_id']		=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('subject',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$subjectId					=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('subject',$param,$subjectId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('subjectListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit subject details');
		$this->layouts->admin_view('subjectlist/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 16 JANUARY 2018
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('subject',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('subjectListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
}
