<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewtimetable extends CI_Controller {

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
		
		$subQuery          = "SELECT encrypt_id,concat(user_f_name,' ',user_m_name,' ',user_l_name) as teacher_name 
							FROM sms_users
						 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND user_type = 'Teacher'";
		//print $subQuery ; die;					
        $data['TEACHERDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);	

		$subQuery          = "SELECT encrypt_id,working_day_name 
							FROM sms_working_days
						 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
		//print $subQuery ; die;					
        $data['WORKINGDAYDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
		
		//print "<pre>"; print_r($data['TEACHERDATA']); die;
		if($this->input->get('searchValue')):
			$sValue							=	$this->input->get('searchValue');
			$whereCon['like']		 		= 	"(name LIKE '%".$sValue."%')";
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchValue'] 			= 	'';
		endif;
		
		$whereCon['where']		 			= 	"";		
		$shortField 						= 	'id ASC';
		
		$this->load->library('pagination');
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('subjectHeadAdminData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$config['suffix']					= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'sms_exam_term';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->admin_model->SelectExamTermData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
		
		$data['ALLDATA'] 					= 	$this->admin_model->SelectExamTermData('data',$tblName,$whereCon,$shortField,$config['per_page'],$page); 
       // print "<pre>"; print_r($data['ALLDATA']); die;     
		$this->layouts->set_title('Set exam term details');
		$this->layouts->admin_view('time_table/index',array(),$data);
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
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('sms_exam_term',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):
			
			$error					=	'NO';
			$this->form_validation->set_rules('name', 'Term name', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):   
				$param['name']		= 	addslashes($this->input->post('name'));				
				$param['start_date']  = DDMMYYtoYYMMDD($this->input->post('start_date'));
				$param['end_date']  = DDMMYYtoYYMMDD($this->input->post('end_date'));
				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
				   	$alastInsertId				=	$this->common_model->add_data('sms_exam_term',$param);					
										
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['id']	=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('sms_exam_term',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$subjectHeadId				=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('sms_exam_term',$param,$subjectHeadId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('subjectHeadAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		
		$this->layouts->set_title('Edit subject head details');
		$this->layouts->admin_view('set_term/addeditdata',array(),$data);
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
		
		redirect(correctLink('subjectHeadAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
	
	public function getTeacherTimeTableInfoViewList(){
		$teacher_id 	= addslashes($this->input->post('teacher_id'));
		$day_id = addslashes($this->input->post('day_id'));		
		
		$subQuery         =	"SELECT t1.period_subject_teacher_id as id,t2.class_name,t3.class_section_name,t4.subject_name,t5.class_period_name
							FROM sms_period_subject_teacher t1 
							LEFT JOIN sms_classes t2 on t1.class_id=t2.encrypt_id
							LEFT JOIN sms_class_section t3 on t1.section_id=t3.encrypt_id
							LEFT JOIN sms_subject t4 on t1.subject_id=t4.encrypt_id
							LEFT JOIN sms_class_period t5 on t1.period_id=t5.encrypt_id
							WHERE t1.teacher_id='".$teacher_id."' AND t1.working_day_id='".$day_id."' 
							AND t1.franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND t1.school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND t1.branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND t1.board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'	
							ORDER BY t2.class_name";
		//print $subQuery; die;
		$data['STUDENTDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);		
		$count = sizeof($data['STUDENTDATA']);
		if($count>1){
?>
<div class="row">
    <div class="col-lg-12">
	 <span id="msg">			
		</span>
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
         <span>View Time Table </span> 		
		 </header>
        <div class="panel-body">
    
	<div class="row">
		<div class="col-lg-12">
			<div class="col-lg-3">Class</div>
			<div class="col-lg-3">Section</div>
			<div class="col-lg-3">Subject</div>
			<div class="col-lg-3">Period</div>
		</div><br><hr>
		<?php  
		$i=1;
		foreach($data['STUDENTDATA'] as $row){	
		?>	
			 <div class="col-lg-12">			
			<div class="col-lg-3"><?=$row['class_name']; ?></div>
			<div class="col-lg-3"><?=$row['class_section_name']; ?></div>
			<div class="col-lg-3"><?=$row['subject_name']; ?></div>
			<div class="col-lg-3"><?=$row['class_period_name']; ?></div>			
		    </div><br><hr>		
		<?php 		
			}
			$i=$i+1;
		?>
	<div>	
	</div>
    <BR>

  </div>
</div>

</div>
<!-- END HERE ---->	  
    </div>
  </div>
<?php		
	}	
	else{
		echo "<h2>No Record Found...";		
	}
	}
}
