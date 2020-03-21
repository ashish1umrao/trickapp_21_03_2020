<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessionmonth extends CI_Controller {

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
	 * * Function name : Sessionmonth
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for Session month
	 * * Date : 24 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(amon.session_month_name LIKE '%".$sValue."%' OR amon.session_month_short_name LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"amon.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
												 AND amon.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
												 AND amon.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
												 AND amon.board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."'";		
		$shortField 						= 	'amon.session_month_id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('sessionMonthAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'session_month as amon';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectSessionMonthData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectSessionMonthData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage session start month details');
		$this->layouts->admin_view('sessionmonth/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 24 JANUARY 2018
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{		
		$data['error'] 				= 	'';
		
		$monthQuery					=	"SELECT month_name,month_short_name FROM sms_months WHERE status = 'Y'";
		$data['MONTHDATA']			=	$this->common_model->get_data_by_query('multiple',$monthQuery);
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$monQuery				=	"SELECT session_month_name FROM sms_session_month 
										 WHERE franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
										 AND school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
										 AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
										 AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."'";
			$data['MONDATA']		=	$this->common_model->get_ids_in_array('session_month_name',$monQuery);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';  
			$this->form_validation->set_rules('session_month_name[]', 'Month', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'): 
			
				if(!$data['MONDATA']):
					$monthname							=	$this->input->post('session_month_name'); 
					foreach($monthname as $value):
						$daysdata						=	explode('_____',$value);
						$param['session_month_name']	=	$daysdata[0];
						$param['session_month_short_name']=	$daysdata[1];
						
						$param['franchise_id']			=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
						$param['school_id']				=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
						$param['branch_id']				=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
						$param['board_id']				=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
						$param['creation_date']			=	currentDateTime();
						$param['created_by']			=	$this->session->userdata('SMS_ADMIN_ID');
                                                 $param['session_year']			=	CURRENT_SESSION;
						$param['status']				=	'Y';
						$alastInsertId					=	$this->common_model->add_data('session_month',$param);
						
						$Uparam['encrypt_id']			=	manojEncript($alastInsertId);
						$Uwhere['session_month_id']		=	$alastInsertId;
						$this->common_model->edit_data_by_multiple_cond('session_month',$Uparam,$Uwhere);
					endforeach;
					
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$monthsdel							=	array();
					$monthname							=	$this->input->post('session_month_name'); 
					foreach($monthname as $value):
						$daysdata						=	explode('_____',$value);
						if(in_array($daysdata[0],$data['MONDATA'])):
							array_push($monthsdel,$daysdata[0]);
							$Uaparam['session_month_name']	=	$daysdata[0];
							$Uaparam['session_month_short_name']=	$daysdata[1];
							
							$Uaparam['update_date']			=	currentDateTime();
							$Uaparam['updated_by']			=	$this->session->userdata('SMS_ADMIN_ID');
							
							$Uawhere['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
							$Uawhere['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
							$Uawhere['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
							$Uawhere['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
							$Uawhere['session_month_name']	=	$daysdata[0];
							$this->common_model->edit_data_by_multiple_cond('session_month',$Uaparam,$Uawhere);
						else:
							$param['session_month_name']	=	$daysdata[0];
							$param['session_month_short_name']=	$daysdata[1];
							
							$param['franchise_id']			=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
							$param['school_id']				=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
							$param['branch_id']				=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
							$param['board_id']				=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
							$param['creation_date']			=	currentDateTime();
							$param['created_by']			=	$this->session->userdata('SMS_ADMIN_ID');
							$param['status']				=	'Y';
                                                         $param['session_year']			=	CURRENT_SESSION;
							$alastInsertId					=	$this->common_model->add_data('session_month',$param);
							
							$Uparam['encrypt_id']			=	manojEncript($alastInsertId);
							$Uwhere['session_month_id']		=	$alastInsertId;
							$this->common_model->edit_data_by_multiple_cond('session_month',$Uparam,$Uwhere);
						endif;
					endforeach;
					foreach($data['MONDATA'] as $MONDATA):
						if(!in_array($MONDATA,$monthsdel)):
							$Dwhere['franchise_id']			=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
							$Dwhere['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
							$Dwhere['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
							$Dwhere['board_id']				=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
							$Dwhere['session_month_name']	=	$MONDATA;
							$this->common_model->delete_by_multiple_cond('session_month',$Dwhere);
						endif;
					endforeach;
				
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('sessionMonthAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit session start month details');
		$this->layouts->admin_view('sessionmonth/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 24 JANUARY 2018
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('session_month',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('sessionMonthAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
}
