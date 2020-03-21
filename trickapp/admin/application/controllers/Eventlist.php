<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventlist extends CI_Controller {

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
	 * * Function name : eventlist
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for eventlist
	 * * Date : 23 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(ev.purpose LIKE '%".$sValue."%' or ev.venue LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"ev.franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
												 AND ev.school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
												 AND ev.branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";		
		$shortField 						= 	'ev.event_id DESC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('EventlistAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'event as ev';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectEventData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectEventData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Event details');
		$this->layouts->admin_view('eventlist/index',array(),$data);
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
		
		 $classQuery        = "SELECT encrypt_id,class_name FROM sms_classes 
										 WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "'
										 AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "'
										 AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "'
										 AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'
										 AND status = 'Y'";
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $classQuery);
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('event',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
                    $error					=	'NO'; 
			
			$this->form_validation->set_rules('class_id', 'lang:Class Name', 'trim');
			$this->form_validation->set_rules('section_id', 'lang:Section Name', 'trim');
			$this->form_validation->set_rules('purpose', 'lang:Event Name', 'trim|required');
			$this->form_validation->set_rules('visibility[]', 'lang:Visibility', 'trim|required');
			$this->form_validation->set_rules('venue', 'lang:venue', 'trim');
			$this->form_validation->set_rules('from_date', 'lang:Event From Date', 'trim|required');
			$this->form_validation->set_rules('to_date', 'lang:Event To Date', 'trim|required');
			$this->form_validation->set_rules('time', 'lang:Event Time', 'trim|required');
			$this->form_validation->set_rules('message', 'lang:Message', 'trim');
			$this->form_validation->set_rules('about_event', 'lang:About Event', 'trim');
			
		
			
			if($this->form_validation->run() && $error == 'NO'): 
			
			    
				$params['class_id']						=	addslashes($this->input->post('class_id'));
				$params['section_id']					=	addslashes($this->input->post('section_id'));
				$params['visibility']					=	implode(',',$this->input->post('visibility'));
				
				$params['purpose']						=	addslashes($this->input->post('purpose'));
				$params['venue']						=	addslashes($this->input->post('venue'));
				$params['from_date']					=	DDMMYYtoYYMMDD($this->input->post('from_date'));   
				$params['to_date']					    =	DDMMYYtoYYMMDD($this->input->post('to_date'));   
				$params['time']							=	addslashes($this->input->post('time'));
				$params['message']						=	addslashes($this->input->post('message'));
				$params['about_event']					=	addslashes($this->input->post('about_event'));

				if($params['from_date'] != $params['to_date']):
					$mesgString = $params['message'].' from '.$params['from_date'].' to '.$params['to_date'].' time '.$params['time'].' venue- '.$params['venue'];
				else:
					$mesgString = $params['message'].' on '.$params['from_date'].' time '.$params['time'].' venue- '.$params['venue'];
				endif;
                                
                                //smsm code
                                
                                
                                
				if($this->input->post('CurrentDataID') ==''):
					$params['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$params['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$params['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
                                        $params['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
					$params['creation_date']		=	currentDateTime();
					$params['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$params['status']			=	'Y';
                                                        $params['session_year']			=	CURRENT_SESSION;
					$alastInsertId				=	$this->common_model->add_data('event',$params);
					
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['event_id']=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('event',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$TDPROGId					=	$this->input->post('CurrentDataID');
					$params['update_date']		=	currentDateTime();
					$params['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('event',$params,$TDPROGId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('EventlistAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit Event Details');
		$this->layouts->admin_view('eventlist/addeditdata',array(),$data);
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
		$this->common_model->edit_data('event',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('EventlistAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
        
        
        /***********************************************************************
	** Function name : get_section_name
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get_section_name.
	** Date : 29  Nov 2016
	************************************************************************/
	function get_section_name()
	{
		$html		 =	'<option value="">Select Section</option>';
		if($this->input->post('sectionid')=='All'): $select1 = 'selected="selected"'; else: $select1 = ''; endif;
		$html		.=	'<option value="All" '.$select1.'>All</option>';
		if($this->input->post('classid')):
                  
			$classid			=	$this->input->post('classid'); 
			$sectionid			=	$this->input->post('sectionid');
                       $sectionQuery	=	"SELECT encrypt_id,class_section_name FROM sms_class_section 
							     WHERE franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
								 AND school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' 
								 AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' 
								 AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."'
								 AND class_id = '".$classid."'   AND status = 'Y'";
			$secdata	=	$this->common_model->get_data_by_query('multiple',$sectionQuery); 
			
			if($secdata <> ""):
				foreach($secdata as $secinfo):
					if($sectionid == $secinfo['encrypt_id']): $select	=	'selected="selected"';	else:	$select	=	''; endif;
					$html		.=	'<option value="'.$secinfo['encrypt_id'].'" '.$select.'>'.stripslashes($secinfo['class_section_name']).'</option>';
				endforeach;
			endif; 
		endif;
		echo $html; die;
	}

        
}
