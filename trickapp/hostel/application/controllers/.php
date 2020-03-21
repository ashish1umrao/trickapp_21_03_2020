<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classlist extends CI_Controller {

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
	 * * Function name : classlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for classlist
	 * * Date : 23 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{		
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(cls.class_name LIKE '%".$sValue."%' OR cls.class_short_name LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"cls.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
												 AND cls.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
												 AND cls.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
												 AND cls.board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."'";		
		$shortField 						= 	'cls.class_id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index/';
		$this->session->set_userdata('classListAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'classes as cls';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectClassListData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$config['uri_segment']				= 	4;
		$this->pagination->initialize($config);
		
		if($this->uri->segment(4)):
			$page = $this->uri->segment(4);
		else:
			$page =	0;
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectClassListData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage class details');
		$this->layouts->admin_view('classlist/index',array(),$data);
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
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('classes',$editid);
			
			$periodQuery			=	"SELECT encrypt_id,class_period_name as period_name,class_period_short_name,class_period_start_time as start_time,class_period_end_time as end_time,class_period_duration as duration
										 FROM sms_class_period WHERE class_id = '".$editid."'";  
			$data['SDETAILDATA']	=	$this->common_model->get_data_by_query('multiple',$periodQuery);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';  
			$this->form_validation->set_rules('class_name', 'Class name', 'trim|required');
			$this->form_validation->set_rules('class_short_name', 'Class short name', 'trim|required');
			
			$serror					=	0;
			$TotalPeriodCount 		= 	$this->input->post('TotalPeriodCount');
			if($TotalPeriodCount):  
				for($i=1; $i <= $TotalPeriodCount; $i++):
					$this->form_validation->set_rules('period_id_'.$i, 'lang:Period Id', 'trim');
					$this->form_validation->set_rules('period_name_'.$i, 'lang:Period Name', 'trim');
					$this->form_validation->set_rules('duration_'.$i, 'lang:Duration', 'trim');
					$this->form_validation->set_rules('start_time_'.$i, 'lang:Start Time', 'trim');
					$this->form_validation->set_rules('end_time_'.$i, 'lang:End Time', 'trim');
					if($this->input->post('period_name_'.$i) && $this->input->post('start_time_'.$i) && $this->input->post('end_time_'.$i)):
						$serror		=	'1';
					endif;
					$data['period_id_'.$i]		=	$this->input->post('period_id_'.$i);
					$data['period_name_'.$i]	=	$this->input->post('period_name_'.$i);
					$data['duration_'.$i]		=	$this->input->post('duration_'.$i);
					$data['start_time_'.$i]		=	$this->input->post('start_time_'.$i);
					$data['end_time_'.$i]		=	$this->input->post('end_time_'.$i);
				endfor;
				$this->form_validation->set_rules('TotalPeriodCount', 'lang:Total Period', 'trim');
			endif;
			$data['suberror']		=	'Please fill atleast one period details.';
			if($serror == 0):
				$error				=	'YES';
			endif;
			
			if($this->form_validation->run() && $error == 'NO'):   
			
				$param['class_name']			= 	addslashes($this->input->post('class_name'));
				$param['class_short_name']		= 	addslashes($this->input->post('class_short_name'));
				$param['no_of_period']			= 	addslashes($this->input->post('TotalPeriod'));
				
				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
					$alastInsertId				=	$this->common_model->add_data('classes',$param);
					
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['class_id']			=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('classes',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
					
					$classid					=	$Uparam['encrypt_id'];
					
					if($TotalPeriodCount):  
						for($i=1; $i <= $TotalPeriodCount; $i++):
							if($this->input->post('period_name_'.$i) && $this->input->post('start_time_'.$i) && $this->input->post('end_time_'.$i)):
								$CPparams['class_id']				=	$classid;
								$CPparams['class_period_name']		=	addslashes($this->input->post('period_name_'.$i));
								$CPparams['class_period_duration']	=	$this->input->post('duration_'.$i)?addslashes($this->input->post('duration_'.$i)):(((strtotime(addslashes($this->input->post('end_time_'.$i)))-strtotime(addslashes($this->input->post('start_time_'.$i))))/60));
								$CPparams['class_period_start_time']=	addslashes($this->input->post('start_time_'.$i));
								$CPparams['class_period_end_time']	=	addslashes($this->input->post('end_time_'.$i));
								$CPlastInsertId						=	$this->common_model->add_data('class_period',$CPparams);
								
								$CPUparam['encrypt_id']				=	manojEncript($CPlastInsertId);
								$CPUwhere['class_period_id']		=	$CPlastInsertId;
								$this->common_model->edit_data_by_multiple_cond('class_period',$CPUparam,$CPUwhere);
							endif;
						endfor;
					endif;
					
				else:
					$classid					=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('classes',$param,$classid);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
					
					$perioddataarray			=	array();
					if($TotalPeriodCount):  
						for($i=1; $i <= $TotalPeriodCount; $i++):
							if($this->input->post('period_name_'.$i) && $this->input->post('start_time_'.$i) && $this->input->post('end_time_'.$i)):
								if($this->input->post('period_id_'.$i)):
									$periodid							=	$this->input->post('period_id_'.$i);
									
									$CPUparam['class_period_name']		=	addslashes($this->input->post('period_name_'.$i));
									$CPUparam['class_period_duration']	=	$this->input->post('duration_'.$i)?addslashes($this->input->post('duration_'.$i)):(((strtotime(addslashes($this->input->post('end_time_'.$i)))-strtotime(addslashes($this->input->post('start_time_'.$i))))/60));
									$CPUparam['class_period_start_time']=	addslashes($this->input->post('start_time_'.$i));
									$CPUparam['class_period_end_time']	=	addslashes($this->input->post('end_time_'.$i));
									
									$CPUwhere['encrypt_id']				=	$periodid;
									$CPUwhere['class_id']				=	$classid;
									$this->common_model->edit_data_by_multiple_cond('class_period',$CPUparam,$CPUwhere);
									
									array_push($perioddataarray,$periodid);
								else:
									$UCPparams['class_id']				=	$classid;
									$UCPparams['class_period_name']		=	addslashes($this->input->post('period_name_'.$i));
									$UCPparams['class_period_duration']	=	$this->input->post('duration_'.$i)?addslashes($this->input->post('duration_'.$i)):(((strtotime(addslashes($this->input->post('end_time_'.$i)))-strtotime(addslashes($this->input->post('start_time_'.$i))))/60));
									$UCPparams['class_period_start_time']=	addslashes($this->input->post('start_time_'.$i));
									$UCPparams['class_period_end_time']	=	addslashes($this->input->post('end_time_'.$i));
									$UCPlastInsertId					=	$this->common_model->add_data('class_period',$UCPparams);
									
									$UCPUparam['encrypt_id']			=	manojEncript($UCPlastInsertId);
									$UCPUwhere['class_period_id']		=	$UCPlastInsertId;
									$this->common_model->edit_data_by_multiple_cond('class_period',$UCPUparam,$UCPUwhere);
								endif;
							endif;
						endfor;
						if($data['SDETAILDATA'] <> "" && count($perioddataarray)>0):
							foreach($data['SDETAILDATA'] as $SDETAILINFO):
								if(!in_array($SDETAILINFO['encrypt_id'],$perioddataarray)):
									$this->common_model->delete_data('class_period',$SDETAILINFO['encrypt_id']);
								endif;
							endforeach;
						endif;						
					endif;
					
				endif;
				
				redirect(correctLink('classListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit class details');
		$this->layouts->admin_view('classlist/addeditdata',array(),$data);
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
		$this->common_model->edit_data('classes',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('classListAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
}
