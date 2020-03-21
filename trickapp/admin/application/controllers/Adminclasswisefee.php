<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminclasswisefee extends CI_Controller {

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
	 * * Developed By : Ashish UMrao
	 * * Purpose  : This function used for Index
	 * * Date : 30 APRIL 2019
	 * * **********************************************************************/
	public function index()
	{		
		//echo manojEncript("48"); die;
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(cwfee.name LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"";		
		$shortField 						= 	'cwfee.id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('classwisefeeListAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'class_wise_fee as cwfee';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectClassWiseFee('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectClassWiseFee('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 
       // print "<pre>"; print_r($data['ALLDATA']); die;     
		$this->layouts->set_title('Set Class wise fee');
		$this->layouts->admin_view('classwisefee/index',array(),$data);
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
		
		$classQuery        = "SELECT encrypt_id,class_name FROM sms_classes 
							  WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
							  AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
							  AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
							  AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
							  AND status = 'Y'";
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $classQuery);


		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('sms_exam_term',$editid);
			$data['FEEHEADINGLIST']   =  $this->admin_model->get_all_fee_heading_list();

		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')): //echo "<pre>"; print_r($_POST); die;
			
			$error					=	'NO';
			$this->form_validation->set_rules('class_id', 'Class ID', 'trim|required');
			$this->form_validation->set_rules('per_month_fee', 'Per Month Fee', 'trim|required');
			$this->form_validation->set_rules('fee_head_name', 'Fees heading', 'trim|required');
			if($this->form_validation->run() && $error == 'NO'):   
				$param['class_id']		= 	addslashes($this->input->post('class_id'));				
				$param['per_month_fee']  = addslashes($this->input->post('per_month_fee'));
				$param['fee_head_id']         = addslashes($this->input->post('fee_head_name'));

				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
				   	$alastInsertId				=	$this->common_model->add_data('class_wise_fee',$param);					
										
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['id']	=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('class_wise_fee',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$subjectHeadId				=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('class_wise_fee',$param,$subjectHeadId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('classwisefeeListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit class Wise fee details');
		$this->layouts->admin_view('classwisefee/addeditdata',array(),$data);
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
		$this->common_model->edit_data('class_wise_fee',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('classwisefeeListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
}
