<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class hostellist extends CI_Controller {

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
	 * * Function name : Franchising
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for Franchising
	 * * Date : 25 JANUARY 2018 
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
                $this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(h.hostel_name LIKE '%".$sValue."%' 
			                   					  OR h.hostel_display_name LIKE '%".$sValue."%' 
												  OR h.hostel_slug LIKE '%".$sValue."%' 
												 
												  OR h.hostel_mobile_number LIKE '%".$sValue."%' 
												  OR h.hostel_type LIKE '%".$sValue."%' 
												  OR h.hostel_total_room LIKE '%".$sValue."%' 
												  OR w.user_f_name LIKE '%".$sValue."%' 
                                                                                                        OR w.user_m_name LIKE '%".$sValue."%' 
                                                                                                              OR w.user_l_name LIKE '%".$sValue."%' 
												
												  OR h.status LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		
		$shortField 						= 	'h.hostel_name ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('hostelAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'hostel as h';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelecthostelListData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelecthostelListData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage hostel details');
		$this->layouts->admin_view('hostellist/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 25 JANUARY 2018
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{		
		$data['error'] 				= 	'';
	
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('hostel',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
                	$wardenQuery			=	"SELECT encrypt_id,user_f_name,user_m_name,user_l_name,user_email,user_type
										 FROM sms_users 
										 WHERE franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' 
										 AND school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."'
										 AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'
										 AND (user_type = 'Teacher' ) AND status = 'A'"; 
				if($wardenIds):							 
					$wardenQuery	.=	"AND encrypt_id NOT IN ('".implode("','",$wardenIds)."')";
			 	endif;							 
			$wardenQuery			.=	"ORDER BY user_f_name ASC";
			$data['WARDENDATA']	=	$this->common_model->get_data_by_query('multiple',$wardenQuery);
                       $typeQuery					=	"SELECT hostel_type FROM sms_hostel_type WHERE status = 'Y'";
		$data['TYPEDATA']		=	$this->common_model->get_data_by_query('multiple',$typeQuery); 
                
		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			
			//////////////		SCHOOL SECTION		//////////////
			$this->form_validation->set_rules('hostel_name', 'Name', 'trim|required');
			$this->form_validation->set_rules('hostel_display_name', 'Display name', 'trim|required');
			$this->form_validation->set_rules('hostel_slug', 'Slug url', 'trim|required|is_unique[hostel.hostel_slug]');
			
			
			$this->form_validation->set_rules('hostel_mobile_number', 'Mobile number', 'trim|min_length[10]|max_length[15]');
			$testmobile		=	str_replace(' ','',$this->input->post('hostel_mobile_number'));
			if($this->input->post('hostel_mobile_number') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testmobile)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testmobile)):
					$error						=	'YES';
					$data['s_mobileerror'] 		= 	'Please eneter correct number.';
				endif;
			endif;
			
			$this->form_validation->set_rules('hostel_address', 'Address', 'trim');
			$this->form_validation->set_rules('hostel_warden_id', 'Warden name', 'trim|required');
			$this->form_validation->set_rules('hostel_description', 'Description', 'trim');
			$this->form_validation->set_rules('hostel_total_room', 'Total Room', 'trim|required');
			$this->form_validation->set_rules('hostel_type', 'Type', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):   
			
			 	//////////////		SCHOOL SECTION		//////////////
				$param['hostel_name']				= 	addslashes(ucwords($this->input->post('hostel_name')));
				$param['hostel_display_name']		= 	addslashes($this->input->post('hostel_display_name'));
				$param['hostel_slug']				= 	url_title(addslashes($this->input->post('hostel_slug')));
				
				
				
				
				$param['hostel_mobile_number']		= 	addslashes($this->input->post('hostel_mobile_number'));
				$param['hostel_address']				= 	addslashes($this->input->post('hostel_address'));
				$param['hostel_warden_id']			= 	addslashes($this->input->post('hostel_warden_id'));
				$param['hostel_description']				= 	addslashes($this->input->post('hostel_description'));
				$param['hostel_total_room']				= 	addslashes($this->input->post('hostel_total_room'));
				$param['hostel_type']				= 	addslashes($this->input->post('hostel_type'));
				
				if($this->input->post('CurrentDataID') ==''):
						$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']		=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']		=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
					$lastInsertId				=	$this->common_model->add_data('hostel',$param);
					
					$Uparam['encrypt_id']		=	manojEncript($lastInsertId);
					$Uwhere['hostel_id']			=	$lastInsertId;
					$this->common_model->edit_data_by_multiple_cond('hostel',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$hostelId				=	$this->input->post('CurrentDataID');
					
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('hostel',$param,$hostelId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('hostelAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit hostel details');
		$this->layouts->admin_view('hostellist/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 25 JANUARY 2018
	************************************************************************/
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
		$this->common_model->edit_data('hostel',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
	
		redirect(correctLink('hostelAdminData',$this->session->userdata('SMS_HOSTEL_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
	
	/***********************************************************************
	** Function name : get_view_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get view data.
	** Date : 25 JANUARY 2018
	************************************************************************/
	function get_view_data()
	{
		$html					=	'';
		if($this->input->post('viewid')):
			$viewId				=	$this->input->post('viewid'); 
			$viewQuery			=	"SELECT h.*, s.user_f_name , s.user_m_name , s.user_l_name ,s.user_email ,s.user_type
									 FROM sms_hostel as h
                                                                         left join sms_users as s on  h.hostel_warden_id = s.encrypt_id
									 WHERE h.encrypt_id = '".$viewId."' ";
			$viewData			= 	$this->common_model->get_data_by_query('single',$viewQuery);  
			if($viewData <> ""):
			
				$html			.=	'<table class="table border-none">
								  <tbody>';
				$html			.=	'<tr>
									  <td align="left" width="30%"><strong>Name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['hostel_name']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Display name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['hostel_display_name']).'</td>
									</tr>
                                                                        <tr>
									  <td align="left" width="30%"><strong>Slug Url</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['hostel_slug']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Warden Name</strong></td>
									  <td align="left" width="70%">'.stripslashes(ucwords($viewData['user_f_name'])).' ' .stripslashes($viewData['user_m_name']).' ' .stripslashes($viewData['user_l_name']).' </td>
									</tr>
                                                                        <tr>
									  <td align="left" width="30%"><strong>hostel Type</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['hostel_type']).'</td>
									</tr>
									
									<tr>
									  <td align="left" width="30%"><strong>Mobile number</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['hostel_mobile_number']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Address</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['hostel_address']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>hostel Total Room</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['hostel_total_room']).'</td>
									</tr>
									';
				$html			.=	'</tbody>
								</table>';
			endif;
		endif;
		echo $html; die;
	}
}
