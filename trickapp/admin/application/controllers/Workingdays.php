<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Workingdays extends CI_Controller {

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
	 * * Function name : workingdays
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for Working days
	 * * Date : 23 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(wday.working_day_name LIKE '%".$sValue."%' OR wday.working_day_short_name LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"wday.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."'
												 AND wday.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
												 AND wday.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
												 AND wday.board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."'";		
		$shortField 						= 	'wday.working_day_id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('workingDaysAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'working_days as wday';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectWorkingDaysData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectWorkingDaysData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage working day details');
		$this->layouts->admin_view('workingdays/index',array(),$data);
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
		
		$timeQuery					=	"SELECT day_name,day_short_name FROM sms_days WHERE status = 'Y'";
		$data['WDAYSDATA']			=	$this->common_model->get_data_by_query('multiple',$timeQuery);
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$dayQuery				=	"SELECT working_day_name FROM sms_working_days 
										 WHERE franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
										 AND school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
										 AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
										 AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."'";
			$data['DAYSDATA']		=	$this->common_model->get_ids_in_array('working_day_name',$dayQuery);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';  
			$this->form_validation->set_rules('working_day_name[]', 'Day', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'): 
			
				if(!$data['DAYSDATA']):
					$workingdays						=	$this->input->post('working_day_name'); 
					foreach($workingdays as $value):
						$daysdata						=	explode('_____',$value);
						$param['working_day_name']		=	$daysdata[0];
						$param['working_day_short_name']=	$daysdata[1];
						
						$param['franchise_id']			=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
						$param['school_id']				=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
						$param['branch_id']				=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
						$param['board_id']				=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
						$param['creation_date']			=	currentDateTime();
						$param['created_by']			=	$this->session->userdata('SMS_ADMIN_ID');
						$param['status']				=	'Y';
                                                                $param['session_year']			=	CURRENT_SESSION;
						$alastInsertId					=	$this->common_model->add_data('working_days',$param);
						
						$Uparam['encrypt_id']			=	manojEncript($alastInsertId);
						$Uwhere['working_day_id']		=	$alastInsertId;
						$this->common_model->edit_data_by_multiple_cond('working_days',$Uparam,$Uwhere);
					endforeach;
					
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$workingdel							=	array();
					$workingdays						=	$this->input->post('working_day_name'); 
					foreach($workingdays as $value):
						$daysdata						=	explode('_____',$value);
						if(in_array($daysdata[0],$data['DAYSDATA'])):
							array_push($workingdel,$daysdata[0]);
							$Uaparam['working_day_name']	=	$daysdata[0];
							$Uaparam['working_day_short_name']=	$daysdata[1];
							
							$Uaparam['update_date']			=	currentDateTime();
							$Uaparam['updated_by']			=	$this->session->userdata('SMS_ADMIN_ID');
							
							$Uawhere['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
							$Uawhere['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
							$Uawhere['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
							$Uawhere['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
							$Uawhere['working_day_name']	=	$daysdata[0];
							$this->common_model->edit_data_by_multiple_cond('working_days',$Uaparam,$Uawhere);
						else:
							$param['working_day_name']		=	$daysdata[0];
							$param['working_day_short_name']=	$daysdata[1];
							
							$param['franchise_id']			=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
							$param['school_id']				=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
							$param['branch_id']				=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
							$param['board_id']				=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
							$param['creation_date']			=	currentDateTime();
							$param['created_by']			=	$this->session->userdata('SMS_ADMIN_ID');
							$param['status']				=	'Y';
                                                           $param['session_year']			=	CURRENT_SESSION;
							$alastInsertId					=	$this->common_model->add_data('working_days',$param);
							
							$Uparam['encrypt_id']			=	manojEncript($alastInsertId);
							$Uwhere['working_day_id']		=	$alastInsertId;
							$this->common_model->edit_data_by_multiple_cond('working_days',$Uparam,$Uwhere);
						endif;
					endforeach;
                                        
					foreach($data['DAYSDATA'] as $DAYSDATA):
						if(!in_array($DAYSDATA,$workingdel)):
							$Dwhere['franchise_id']			=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
							$Dwhere['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
							$Dwhere['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
							$Dwhere['board_id']				=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
							$Dwhere['working_day_name']		=	$DAYSDATA;
							$this->common_model->delete_by_multiple_cond('working_days',$Dwhere);
						endif;
					endforeach;
				
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('workingDaysAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit working day details');
		$this->layouts->admin_view('workingdays/addeditdata',array(),$data);
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
		$this->common_model->edit_data('working_days',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('workingDaysAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
}
