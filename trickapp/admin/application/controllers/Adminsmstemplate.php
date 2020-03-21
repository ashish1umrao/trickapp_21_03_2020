<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminsmstemplate extends CI_Controller {

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
	 * * Function name : index
	 * * Developed By : Jitendra Singh 
	 * * Purpose  : This function used for set term
	 * * Date : 30 APRIL 2019
	 * * **********************************************************************/
	public function index()
	{		
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(template.name LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"";		
		$shortField 						= 	'template.template_id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('templatesmsAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'sms_sms_template as template';
		//echo $tblName; die;
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectSMStemplateData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectSMStemplateData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 
        //print "<pre>"; print_r($data['ALLDATA']); die;     
		$this->layouts->set_title('Set Sms Template');
		$this->layouts->admin_view('adminsmstemplate/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Jitendra Singh 
	 * * Purpose  : This function used for add edit data
	 * * Date : 30 APRIL 2019
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{	
	
		$data['error'] 				= 	'';
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('sms_sms_template',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')): //echo "<pre>"; print_r($_POST); die;
			
			$error					=	'NO';
			$this->form_validation->set_rules('smstype', 'SMS Type', 'trim|required');
			$this->form_validation->set_rules('smstypedata', 'Sms Name', 'trim|required');
			$this->form_validation->set_rules('content', 'Content', 'trim|required');
			if($this->form_validation->run() && $error == 'NO'):   
				$param['sms_type']		= 	addslashes($this->input->post('smstype'));				
				$param['smstypename']  	= $this->input->post('smstypedata');
				$param['content']  		= $this->input->post('content');
				$param['franchise_id']  = $this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
				$param['school_id']  	= $this->session->userdata('SMS_ADMIN_SCHOOL_ID');
				$param['branch_id']  	= $this->session->userdata('SMS_ADMIN_BRANCH_ID') ;
				$param['board_id']  	= $this->session->userdata('SMS_ADMIN_BOARD_ID');
				if($this->input->post('CurrentDataID') ==''):
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'A';
				   	$alastInsertId				=	$this->common_model->add_data('sms_sms_template',$param);					
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['template_id']		=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('sms_sms_template',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$smstemplateID				=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					//print_r($param); die;
					$this->common_model->edit_data('sms_sms_template',$param,$smstemplateID);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				redirect(correctLink('templatesmsAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit Sms Template details');
		$this->layouts->admin_view('adminsmstemplate/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Jitendra Singh 
	** Purpose  : This function used for change status
	** Date : 30 APRIL 2019
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('sms_exam_term',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('templatesmsAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
}
