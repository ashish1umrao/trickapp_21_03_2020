<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subadmin extends CI_Controller {

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
	 * * Function name : subadmin
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for subadmin
	 * * Date : 15 JANUARY 2018
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('admin','view_data');
		$this->admin_model->get_permission_type($data);
		$data['error'] 						= 	'';
		
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(adm.admin_name LIKE '%".$sValue."%' 
			                   					  OR adm.admin_display_name LIKE '%".$sValue."%' 
												  OR adm.admin_slug LIKE '%".$sValue."%' 
												  OR adm.admin_email_id LIKE '%".$sValue."%' 
												  OR adm.admin_mobile_number LIKE '%".$sValue."%' 
												  OR adm.admin_address LIKE '%".$sValue."%' 
												  OR adm.admin_locality LIKE '%".$sValue."%' 
												  OR adm.admin_city LIKE '%".$sValue."%' 
												  OR adm.admin_state LIKE '%".$sValue."%' 
												  OR adm.admin_zipcode LIKE '%".$sValue."%' 
												  OR adm.status LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"adm.admin_franchise_id = '".$this->session->userdata('SMS_ADMIN_FRANCHISE_ID')."' AND adm.admin_school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' AND adm.admin_branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' AND adm.admin_type = 'Sub admin'";		
		$shortField 						= 	'adm.admin_name ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('subadminAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'admin as adm';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectAdminData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectAdminData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 

		$this->layouts->set_title('Manage subadmin details');
		$this->layouts->admin_view('subadmin/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 15 JANUARY 2018
	 * * **********************************************************************/
	public function addeditdata($editid='')
	{		
		$data['error'] 				= 	'';
		
		$depQuery					=	"SELECT encrypt_id, department_name
									 	FROM sms_department
									 	WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";
		$data['DPDATA']			=	$this->common_model->get_data_by_query('multiple',$depQuery);
                
                $dgQuery					=	"SELECT encrypt_id, designation_name
									 	FROM sms_designation
									 	WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."'";
		$data['DGDATA']			=	$this->common_model->get_data_by_query('multiple',$dgQuery);
                
		$data['Modirectory'] 		= 	$this->admin_model->get_module(); 
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('admin',$editid);
			$data['MODULEDATA'] 	= 	$this->admin_model->get_permission($editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			$error					=	'NO';
			$this->form_validation->set_rules('admin_department_id', 'Department', 'trim|required');
                        $this->form_validation->set_rules('admin_designation_id', 'Designation', 'trim|required');
			$this->form_validation->set_rules('admin_name', 'Name', 'trim|required');
			$this->form_validation->set_rules('admin_display_name', 'Display name', 'trim|required');
			$this->form_validation->set_rules('admin_email_id', 'E-Mail', 'trim|required|valid_email|is_unique[admin.admin_email_id]');
			if($this->input->post('new_password')!= ''):
				$this->form_validation->set_rules('new_password', 'New password', 'trim|required|min_length[6]|max_length[25]');
				$this->form_validation->set_rules('conf_password', 'Confirm password', 'trim|required|min_length[6]|matches[new_password]');
			endif;
			
			$this->form_validation->set_rules('admin_mobile_number', 'Mobile number', 'trim|required|min_length[10]|max_length[15]|is_unique[admin.admin_mobile_number]');
			$testmobile		=	str_replace(' ','',$this->input->post('admin_mobile_number'));
			if($this->input->post('admin_mobile_number') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testmobile)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testmobile)):
					$error						=	'YES';
					$data['mobileerror'] 		= 	'Please eneter correct number.';
				endif;
			endif;
			
			$this->form_validation->set_rules('admin_address', 'Address', 'trim|required');
			$this->form_validation->set_rules('admin_locality', 'Locality', 'trim|required');
			$this->form_validation->set_rules('admin_city', 'City', 'trim|required');
			$this->form_validation->set_rules('admin_state', 'State', 'trim|required');
			$this->form_validation->set_rules('admin_zipcode', 'Zipcode', 'trim|required');
			
			$pererror							=	'YES';
			if($data['Modirectory'] <> ""): 
			 	foreach($data['Modirectory'] as $MODinfo): 
					if($this->input->post('mainmodule'.$MODinfo['encrypt_id'])):
						$pererror				=	'NO';
					endif;
				endforeach;
				if($pererror == 'YES'):
					$error						=	'YES';
					$data['PERERROR'] 			= 	lang('PERERROR');
				endif;
			endif;
			
			if($this->form_validation->run() && $error == 'NO'):  
			        $param['admin_designation_id']		= 	addslashes($this->input->post('admin_designation_id'));
				$param['admin_department_id']		= 	addslashes($this->input->post('admin_department_id'));
				$param['admin_name']				= 	addslashes($this->input->post('admin_name'));
				$param['admin_display_name']		= 	addslashes($this->input->post('admin_display_name'));
				$param['admin_slug']				= 	url_title(addslashes($this->input->post('admin_slug')));
				$param['admin_email_id']			= 	addslashes($this->input->post('admin_email_id'));
				
				if($this->input->post('new_password')):
					$NewPassword					=	$this->input->post('new_password');
					$param['admin_password']		= 	$this->admin_model->encript_password($NewPassword);
				endif;
				
				$param['admin_mobile_number']		= 	addslashes($this->input->post('admin_mobile_number'));
				$param['admin_address']				= 	addslashes($this->input->post('admin_address'));
				$param['admin_locality']			= 	addslashes($this->input->post('admin_locality'));
				$param['admin_city']				= 	addslashes($this->input->post('admin_city'));
				$param['admin_state']				= 	addslashes($this->input->post('admin_state'));
				$param['admin_zipcode']				= 	addslashes($this->input->post('admin_zipcode'));
				
				if($this->input->post('CurrentDataID') ==''):
					$param['admin_type']		=	'Sub admin';
					$param['admin_level']		=	4;
					$param['admin_franchise_id']=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['admin_school_id']	=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['admin_branch_id']	=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['creation_ip']		=	currentIp();
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'A';
					$salastInsertId				=	$this->common_model->add_data('admin',$param);
					
					$Uparam['encrypt_id']		=	manojEncript($salastInsertId);
					$Uwhere['admin_id']			=	$salastInsertId;
					$this->common_model->edit_data_by_multiple_cond('admin',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
					
					$sunAdminId					=	$Uparam['encrypt_id'];
				else:
					$sunAdminId					=	$this->input->post('CurrentDataID');
					$param['update_ip']			=	currentIp();
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('admin',$param,$sunAdminId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				if($data['Modirectory'] <> "" && $sunAdminId): 
					$this->admin_model->delete_permission_data($sunAdminId);
					foreach($data['Modirectory'] as $MODinfo):
						$mainmodperids		=	'';
						$mmc 				= 	$MODinfo['encrypt_id']; 
						if($this->input->post('mainmodule'.$mmc)):
							$MMParams['module_id']			=	$MODinfo['encrypt_id'];
							$MMParams['module_name']		=	$MODinfo['module_name'];
							$MMParams['module_display_name']=	$MODinfo['module_display_name'];
							$MMParams['module_orders']		=	$MODinfo['module_orders'];
							$MMParams['module_icone']		=	$MODinfo['module_icone'];
							$MMParams['child_data']			=	$MODinfo['child_data'];
							$MMParams['admin_id']			=	$sunAdminId;
							
							if($this->input->post('mainmodule_view_data'.$mmc)):
								$MMParams['view_data']		=	'Y';
							else:
								$MMParams['view_data']		=	'N';
							endif;
							if($this->input->post('mainmodule_add_data'.$mmc)):
								$MMParams['add_data']		=	'Y';
							else:
								$MMParams['add_data']		=	'N';
							endif;
							if($this->input->post('mainmodule_edit_data'.$mmc)):
								$MMParams['edit_data']		=	'Y';
							else:
								$MMParams['edit_data']		=	'N';
							endif;
							if($this->input->post('mainmodule_delete_data'.$mmc)):
								$MMParams['delete_data']	=	'Y';
							else:
								$MMParams['delete_data']	=	'N';
							endif;
							
							$MMlastInsertId					=	$this->common_model->add_data('module_permission',$MMParams);
							
							$UMMparam['encrypt_id']			=	manojEncript($MMlastInsertId);
							$UMMwhere['permission_id']		=	$MMlastInsertId;
							$this->common_model->edit_data_by_multiple_cond('module_permission',$UMMparam,$UMMwhere);
							
							$mainModuleId					=	$UMMparam['encrypt_id'];
						endif;
						
						if($MODinfo['child_data'] == 'Y' && $mainModuleId):
							$childdata 						= 	$this->admin_model->get_child_module($MODinfo['encrypt_id']);
							if($childdata <> ""): 
								foreach($childdata as $CDinfo):	
									 $cmc 					= 	$CDinfo['encrypt_id'];
									if($this->input->post('childmodule'.$mmc.'_'.$cmc)):
										$UMMUparam['child_data']		=	'Y';
										$UMMUwhere['encrypt_id']		=	$mainModuleId;
										$this->common_model->edit_data_by_multiple_cond('module_permission',$UMMUparam,$UMMUwhere);
									
										$CMParams['permission_id']		=	$mainModuleId;
										$CMParams['module_id']			=	$CDinfo['encrypt_id'];
										$CMParams['module_name']		=	$CDinfo['module_name'];
										$CMParams['module_display_name']=	$CDinfo['module_display_name'];
										$CMParams['module_orders']		=	$CDinfo['module_orders'];
										$CMParams['admin_id']			=	$sunAdminId;
										
										if($this->input->post('childmodule_view_data'.$mmc.'_'.$cmc)):
											$CMParams['view_data']		=	'Y';
										else:
											$CMParams['view_data']		=	'N';
										endif;
										if($this->input->post('childmodule_add_data'.$mmc.'_'.$cmc)):
											$CMParams['add_data']		=	'Y';
										else:
											$CMParams['add_data']		=	'N';
										endif;
										if($this->input->post('childmodule_edit_data'.$mmc.'_'.$cmc)):
											$CMParams['edit_data']		=	'Y';
										else:
											$CMParams['edit_data']		=	'N';
										endif;
										if($this->input->post('childmodule_delete_data'.$mmc.'_'.$cmc)):
											$CMParams['delete_data']	=	'Y';
										else:
											$CMParams['delete_data']	=	'N';
										endif;
										
										$CMlastInsertId					=	$this->common_model->add_data('child_module_permission',$CMParams);
							
										$UCMparam['encrypt_id']			=	manojEncript($CMlastInsertId);
										$UCMwhere['child_permission_id']=	$CMlastInsertId;
										$this->common_model->edit_data_by_multiple_cond('child_module_permission',$UCMparam,$UCMwhere);
									endif;
								endforeach;
							endif;
						endif;
					endforeach;
				endif;
				
				redirect(correctLink('subadminAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit subadmin details');
		$this->layouts->admin_view('subadmin/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 15 JANUARY 2018
	************************************************************************/
	function changestatus($changeStatusId='',$statusType='')
	{  
		$this->admin_model->authCheck('admin','edit_data');
		
		$param['status']		=	$statusType;
		$this->common_model->edit_data('admin',$param,$changeStatusId);
		
		if($statusType == "A"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		elseif($statusType == "I"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		elseif($statusType == "B"):
			$this->session->set_flashdata('alert_success',lang('statussuccess'));
		elseif($statusType == "D"):
			$this->session->set_flashdata('alert_success',lang('deletesuccess'));
		endif;
		
		redirect(correctLink('subadminAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
	
	/***********************************************************************
	** Function name : get_view_data
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get view data.
	** Date : 15 JANUARY 2018
	************************************************************************/
	function get_view_data()
	{
		$html					=	'';
		if($this->input->post('viewid')):
			$viewId				=	$this->input->post('viewid'); 
			$viewQuery			=	"SELECT adm.*, badm.admin_name as branch_name, sadm.admin_name as school_name, dep.department_name
									 FROM sms_admin as adm
									 LEFT JOIN sms_admin as badm ON adm.admin_branch_id=badm.encrypt_id
									 LEFT JOIN sms_admin as sadm ON adm.admin_school_id=sadm.encrypt_id
									 LEFT JOIN sms_department as dep ON adm.admin_department_id=dep.encrypt_id
									 WHERE adm.encrypt_id = '".$viewId."' AND adm.admin_type = 'Sub admin'";
			$viewData			= 	$this->common_model->get_data_by_query('single',$viewQuery);  
			if($viewData <> ""):
			
				$html			.=	'<table class="table border-none">
								  <tbody>';
				if($this->session->userdata('SMS_ADMIN_TYPE') == 'Super admin'):
					$html			.=	'<tr>
									  <td align="left" width="30%"><strong>School name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['school_name']).'</td>
									</tr>';
				endif;
				if($this->session->userdata('SMS_ADMIN_TYPE') == 'Super admin' || $this->session->userdata('SMS_ADMIN_TYPE') == 'School'):
					$html			.=	'<tr>
									  <td align="left" width="30%"><strong>Branch name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['branch_name']).'</td>
									</tr>';
				endif;
				$html			.=	'<tr>
									  <td align="left" width="30%"><strong>Designation Name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['department_name']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Sub-admin Name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_name']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Display name</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_display_name']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Email id</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_email_id']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Mobile number</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_mobile_number']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Address</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_address']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Locality</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_locality']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>City</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_city']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>State</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_state']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Zipcode</strong></td>
									  <td align="left" width="70%">'.stripslashes($viewData['admin_zipcode']).'</td>
									</tr>
									<tr>
									  <td align="left" width="30%"><strong>Status</strong></td>
									  <td align="left" width="70%">'.showStatus($viewData['status']).'</td>
									</tr>';
				$html			.=	'</tbody>
								</table>';
			endif;
		endif;
		echo $html; die;
	}
}
