<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setassessment extends CI_Controller {

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
		error_reporting(0);
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('subjectHeadAdminData',currentFullUrl());
		$subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
						 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
		//print $subQuery ; die;					
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);		
		$data['error'] 				= 	'';
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('sms_set_assessment',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):		
			$error					=	'NO';
			$this->form_validation->set_rules('name', 'Term name', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):   
				$param['name']				= addslashes($this->input->post('name'));				
				$param['common_to_all']  	= addslashes($this->input->post('common_to_all'));				
				$param['max_mark'] 			= addslashes($this->input->post('max_mark'));
				$param['class_id'] 			= addslashes($this->input->post('class_id'));
				$param['section_id'] 			= addslashes($this->input->post('section_id'));
				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
				//	print "<pre>"; print_r($param); die;
					
				   	$alastInsertId				=	$this->common_model->add_data('sms_set_assessment',$param);					
										
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['id']	=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('sms_set_assessment',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$subjectHeadId				=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('sms_set_assessment',$param,$subjectHeadId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('subjectHeadAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		$branchQuery	= "SELECT ass.*,class.class_name,sec.class_section_name 
						  FROM sms_set_assessment ass LEFT JOIN sms_classes class on ass.class_id=class.encrypt_id 
						  LEFT JOIN sms_class_section sec on ass.section_id=sec.encrypt_id
						  WHERE ass.franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
						  AND ass.school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
						  AND ass.branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
						  AND ass.board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
			//	print $branchQuery; die;										
		$data['EDITDATA']					=	$this->common_model->getDataByQuery('multiple',$branchQuery);
		//print "<pre>"; print_r($data['EDITDATA']); die;
		$this->layouts->set_title('Edit assessment details');
		$this->layouts->admin_view('assessment_mark/addeditdata',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Jitendra Singh 
	 * * Purpose  : This function used for add edit data
	 * * Date : 30 APRIL 2019
	 * * **********************************************************************/
	public function addeditmarkdata($editid='')
	{	
		//print "<pre>"; print_r($this->session->userdata()); die;
		$data['error'] 				= 	'';		
		$desgQuery					=	"SELECT encrypt_id, name
									 	FROM sms_set_assessment
									 	WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' AND status='Y'";
		//print $desgQuery; die;
		$data['SHEADDATA']			=	$this->common_model->get_data_by_query('multiple',$desgQuery);
		
		$desgQuery					=	"SELECT encrypt_id, name
									 	FROM sms_exam_term
									 	WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' AND status='Y'";
		$data['SHEADDATA1']			=	$this->common_model->get_data_by_query('multiple',$desgQuery);
		
		
		
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('subjectHeadAdminData',currentFullUrl());
		$subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
						 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
		
		$data['error'] 				= 	'';
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('sms_set_assessment',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):		
			$error					=	'NO';
			$this->form_validation->set_rules('name', 'Term name', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):   
				$param['name']				= addslashes($this->input->post('name'));				
				$param['common_to_all']  	= addslashes($this->input->post('common_to_all'));				
				$param['max_mark'] 			= addslashes($this->input->post('max_mark'));
				$param['class_id'] 			= addslashes($this->input->post('class_id'));
				$param['section_id'] 			= addslashes($this->input->post('section_id'));
				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
				//	print "<pre>"; print_r($param); die;
					
				   	$alastInsertId				=	$this->common_model->add_data('sms_set_assessment',$param);					
										
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['id']	=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('sms_set_assessment',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$subjectHeadId				=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('sms_set_assessment',$param,$subjectHeadId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('subjectHeadAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		$branchQuery	= "SELECT ass.*,class.class_name,sec.class_section_name 
						  FROM sms_set_assessment ass LEFT JOIN sms_classes class on ass.class_id=class.encrypt_id 
						  LEFT JOIN sms_class_section sec on ass.section_id=sec.encrypt_id";
				//print $branchQuery; die;										
		$data['EDITDATA']					=	$this->common_model->getDataByQuery('multiple',$branchQuery);
		//print "<pre>"; print_r($data['EDITDATA']); die;
		$this->layouts->set_title('Edit assessment details');
		$this->layouts->admin_view('assessment_mark/addeditmarkdata',array(),$data);
	}	// END OF FUNCTION	
	
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Jitendra Singh 
	 * * Purpose  : This function used for add edit data
	 * * Date : 30 APRIL 2019
	 * * **********************************************************************/
	public function addeditmarkdataviewlist($editid='')
	{	
	
		$data['error'] 				= 	'';		
		$desgQuery					=	"SELECT encrypt_id, name
									 	FROM sms_set_assessment
									 	WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' AND status='Y'";
		$data['SHEADDATA']			=	$this->common_model->get_data_by_query('multiple',$desgQuery);
		
		$desgQuery					=	"SELECT encrypt_id, name
									 	FROM sms_exam_term
									 	WHERE school_id = '".$this->session->userdata('SMS_ADMIN_SCHOOL_ID')."' AND branch_id = '".$this->session->userdata('SMS_ADMIN_BRANCH_ID')."' AND board_id = '".$this->session->userdata('SMS_ADMIN_BOARD_ID')."' AND status='Y'";
		$data['SHEADDATA1']			=	$this->common_model->get_data_by_query('multiple',$desgQuery);
		
		
		
		$config['base_url'] 				= 	$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index';
		$this->session->set_userdata('subjectHeadAdminData',currentFullUrl());
		$subQuery          = "SELECT encrypt_id, class_name FROM sms_classes
						 	WHERE franchise_id = '" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id = '" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id = '" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND board_id = '" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "'";
        $data['CLASSDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
		
		$data['error'] 				= 	'';
		
		if($editid):
			$this->admin_model->authCheck('admin','edit_data');
			$data['EDITDATA']		=	$this->common_model->get_data_by_encryptId('sms_set_assessment',$editid);
		else:
			$this->admin_model->authCheck('admin','add_data');
		endif;
		
		if($this->input->post('SaveChanges')):		
			$error					=	'NO';
			$this->form_validation->set_rules('name', 'Term name', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'):   
				$param['name']				= addslashes($this->input->post('name'));				
				$param['common_to_all']  	= addslashes($this->input->post('common_to_all'));				
				$param['max_mark'] 			= addslashes($this->input->post('max_mark'));
				$param['class_id'] 			= addslashes($this->input->post('class_id'));
				$param['section_id'] 			= addslashes($this->input->post('section_id'));
				if($this->input->post('CurrentDataID') ==''):
					$param['franchise_id']		=	$this->session->userdata('SMS_ADMIN_FRANCHISE_ID');
					$param['school_id']			=	$this->session->userdata('SMS_ADMIN_SCHOOL_ID');
					$param['branch_id']			=	$this->session->userdata('SMS_ADMIN_BRANCH_ID');
					$param['board_id']			=	$this->session->userdata('SMS_ADMIN_BOARD_ID');
					$param['creation_date']		=	currentDateTime();
					$param['created_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$param['status']			=	'Y';
					
				   	$alastInsertId				=	$this->common_model->add_data('sms_set_assessment',$param);					
										
					$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
					$Uwhere['id']	=	$alastInsertId;
					$this->common_model->edit_data_by_multiple_cond('sms_set_assessment',$Uparam,$Uwhere);
					$this->session->set_flashdata('alert_success',lang('addsuccess'));
				else:
					$subjectHeadId				=	$this->input->post('CurrentDataID');
					$param['update_date']		=	currentDateTime();
					$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
					$this->common_model->edit_data('sms_set_assessment',$param,$subjectHeadId);
					$this->session->set_flashdata('alert_success',lang('updatesuccess'));
				endif;
				
				redirect(correctLink('subjectHeadAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
			endif;
		endif;
		$branchQuery	= "SELECT ass.*,class.class_name,sec.class_section_name 
						  FROM sms_set_assessment ass LEFT JOIN sms_classes class on ass.class_id=class.encrypt_id 
						  LEFT JOIN sms_class_section sec on ass.section_id=sec.encrypt_id";
		$data['EDITDATA']					=	$this->common_model->getDataByQuery('multiple',$branchQuery);
		$this->layouts->set_title('Edit assessment details');
		$this->layouts->admin_view('assessment_mark/addeditmarkdataview',array(),$data);
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
		$this->common_model->edit_data('sms_set_assessment',$param,$changeStatusId);
		
		$this->session->set_flashdata('alert_success',lang('statussuccess'));
		
		redirect(correctLink('subjectHeadAdminData',$this->session->userdata('SMS_ADMIN_PATH').$this->router->fetch_class().'/index'));
	}
	
	
	public function getStudentInfo(){
		$class_id 	= addslashes($this->input->post('class_id'));
		$section_id = addslashes($this->input->post('section_id'));
		$assessment_id = addslashes($this->input->post('assessment_id'));
		$subject_id = addslashes($this->input->post('subject_id'));
		
		$subQuery         =	"SELECT stud.student_id,stud.encrypt_id as stu_encrypt_id,stu_cl.student_roll_no,
							CONCAT(stud.student_f_name,' ',stud.student_l_name) as student_name,ass.max_mark 
							FROM sms_student_class stu_cl 
							LEFT JOIN sms_students stud on stu_cl.student_qunique_id=stud.student_qunique_id 
							left join sms_set_assessment ass on stu_cl.school_id = ass.school_id 
							WHERE stu_cl.franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND stu_cl.school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND stu_cl.branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND stu_cl.board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
							AND stu_cl.class_id='".$class_id."' AND stu_cl.section_id='".$section_id."' 
							and ass.encrypt_id='".$assessment_id."' group by stu_cl.student_roll_no";
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
         <span>Enter Marks </span> 		
		 </header>
        <div class="panel-body">
    
	<div class="row">
		<div class="col-lg-12">
			<div class="col-lg-1">Sr.No.</div>
			<div class="col-lg-2">Roll No.</div>
			<div class="col-lg-3">Student Name</div>
			<div class="col-lg-2">Max.Mark</div>
			<div class="col-lg-2">Mark</div>
			<div class="col-lg-2">Action</div>
		</div><br><hr>
		<?php  
		$i=1;
		foreach($data['STUDENTDATA'] as $row){	

		$subQuery         =	"select mark,encrypt_id from sms_stu_mark
							WHERE franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
							AND class_id='".$class_id."' AND section_id='".$section_id."' 
							and assessment_id='".$assessment_id."' and subject_id='".$subject_id."'
							and stu_id='".$row['stu_encrypt_id']."'";
        $data['MARKDATA'] = $this->common_model->get_data_by_query('single', $subQuery);
		?>	
			<input type="hidden" name="stu_id<?=$i; ?>" id="stu_id<?=$i; ?>" value="<?=$row['stu_encrypt_id']; ?>">		
            <div class="col-lg-12" id="stu-row<?=$i; ?>">
			<div class="col-lg-1"><?=$i; ?></div>
			<div class="col-lg-2" id="student_roll_no<?=$i; ?>"><?=$row['student_roll_no']; ?></div>
			<div class="col-lg-3" id="stu_name<?=$i; ?>"><?=$row['student_name']; ?></div>
			<div class="col-lg-2" id="max_mark<?=$i; ?>"><?=$row['max_mark']; ?></div>
			<div class="col-lg-2">
			<input type="text" name="mark" id="mark<?=$i; ?>" class="form-control" value="<?=$data['MARKDATA']['mark']; ?>" >
			<span id="msg_data<?=$i; ?>" style="color:green; font-weight:bold;"></span>
			<span id="msg_data_added<?=$i; ?>" style="color:green; font-weight:bold;display:none;">Record is added...</span>
			</div>
			<div class="col-lg-2">
			<?php if(!empty($data['MARKDATA']['mark'])) { ?>
			<?php /* ?><button type="button" name="delete_btn" id="delete_btn<?=$i; ?>" data-id3="10836" class="btn btn-xs btn-danger btn_delete" onclick="return deleteStudentMark('<?=$data['MARKDATA']['encrypt_id']; ?>','<?=$i; ?>');">x</button>
			<input type="hidden" name="delete_id<?=$i; ?>" id="delete_id<?=$i; ?>" value="<?=$data['MARKDATA']['encrypt_id']; ?>">
			<?php */ ?>
			<a href=""><i class="fa fa-refresh" id="update_btn<?=$i; ?>" aria-hidden="true" onclick="return UpdateStudentMark('<?=$data['MARKDATA']['encrypt_id']; ?>','<?=$i; ?>');"></i></a>
			<?php } else { ?>
			<button type="button" name="btn_add<?=$i; ?>" id="btn_add<?=$i; ?>" class="btn btn-xs btn-success">+</button>
			<?php /* <button type="button" name="delete_btn" id="delete_btn<?=$i; ?>" data-id3="10836" class="btn btn-xs btn-danger btn_delete" style="display:none;" onclick="return deleteStudentMark('<?=$data['MARKDATA']['encrypt_id']; ?>','<?=$i; ?>');">x</button><?php */ ?>
			<a href=""><i class="fa fa-refresh" id="update_btn<?=$i; ?>" aria-hidden="true" style="display:none;" onclick="return UpdateStudentMark('<?=$data['MARKDATA']['encrypt_id']; ?>','<?=$i; ?>');"></i></a>
			<?php } ?>
			</div>	
			</div><br><hr>
<script>
 $(document).on('click', '#btn_add<?=$i; ?>', function(){ 
 var assessment_id = $("#assessment_id").val();
 var term_id = $("#term_id").val();
 var class_id = $("#class_id").val();
 var section_id = $("#section_id").val();
 var subject_id = $("#subject_id").val();
 var stu_id = $("#stu_id<?=$i; ?>").val();
 var student_roll_no = $("#student_roll_no<?=$i; ?>").text();
 var stu_name = $("#stu_name<?=$i; ?>").text();
 var max_mark = $("#max_mark<?=$i; ?>").text();
 var mark = $("#mark<?=$i; ?>").val();
 var indexid = '<?=$i; ?>';
	$.ajax({
            type: 'post',
            url:  'getAddStudentMark',
            data: {assessment_id: assessment_id,term_id: term_id,class_id: class_id,section_id: section_id,subject_id: subject_id,stu_id: stu_id,student_roll_no: student_roll_no,stu_name: stu_name,max_mark: max_mark,mark: mark},
            success: function (response) {
             // $("#msg").html(response);  
			  //$("#msg_data_added"+indexid).html('Record is added...');	
			  $("#msg_data_added"+indexid).show().delay(1000).fadeOut();	
			  $("#btn_add<?=$i; ?>").hide();
			  $("#update_btn<?=$i; ?>").show();
            }
        });
        return false;	 
  });  

function UpdateStudentMark(encrypt_id,indexid){
	var mark = $("#mark"+indexid).val();
	$.ajax({  
		url:"getUpdateStudentMark",  
		method:"POST",  
		data:{encrypt_id:encrypt_id,mark:mark},  
		dataType:"text",  
		success:function(data){  
			$("#msg_data"+indexid).html('Record is updated...');	
			$("#msg_data"+indexid).show().delay(1000).fadeOut();
		}  
		});  
	return false;
 }   
</script>			
		<?php 
		$i=$i+1;
		} 
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
	
	public function getSubjectdata(){
		$class_id = $this->input->post('class_id');
		$section_id = $this->input->post('section_id');
		
		$subQuery         =	"SELECT sub.subject_id,sub.encrypt_id,sub.subject_name 
							FROM sms_class_subject sc LEFT JOIN sms_subject sub on 
							sc.subject_id=sub.encrypt_id 
							WHERE sc.franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND sc.school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND sc.branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND sc.board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
							AND sc.class_id='".$class_id."'";
        $data['SUBJECTDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
		foreach($data['SUBJECTDATA'] as $row){		
			echo "<option value='".$row['encrypt_id']."'>".$row['subject_name']."</option>";
		}
	}
	
	public function getAddStudentMark(){
		$assessment_id = $this->input->post('assessment_id');
		$term_id = $this->input->post('term_id');
		$class_id = $this->input->post('class_id');
		$section_id = $this->input->post('section_id');
		$subject_id = $this->input->post('subject_id');
		$stu_id = $this->input->post('stu_id');
		$student_roll_no = $this->input->post('student_roll_no');
		$stu_name = $this->input->post('stu_name');
		$max_mark = $this->input->post('max_mark');
		$mark = $this->input->post('mark');		

			$param = array('franchise_id'=>$this->session->userdata('SMS_ADMIN_FRANCHISE_ID'),
							'school_id'=>$this->session->userdata('SMS_ADMIN_SCHOOL_ID'),
							'branch_id'=>$this->session->userdata('SMS_ADMIN_BRANCH_ID'),
							'board_id'=>$this->session->userdata('SMS_ADMIN_BOARD_ID'),
							'assessment_id'=>$assessment_id,
							'term_id'=>$term_id,
							'class_id'=>$class_id,
							'section_id'=>$section_id,
							'subject_id'=>$subject_id,
							'stu_id'=>$stu_id,
							'student_roll_no'=>$student_roll_no,
							'stu_name'=>$stu_name,
							'max_mark'=>$max_mark,
							'mark'=>$mark,
							'creation_date'=>currentDateTime(),
							'created_by'=>$this->session->userdata('SMS_ADMIN_ID'),
							'status'=>'Y');	
			//print "<pre>"; print_r($param);				
			$alastInsertId				=	$this->common_model->add_data('sms_stu_mark',$param);	
			$Uparam['encrypt_id']		=	manojEncript($alastInsertId);
			$Uwhere['id']	=	$alastInsertId;
			$this->common_model->edit_data_by_multiple_cond('sms_stu_mark',$Uparam,$Uwhere);
			$this->session->set_flashdata('alert_success',lang('addsuccess'));
			print "<div class='alert alert-success' role='alert'>
			<strong>Well done!</strong> Data added successfully.</div>"; die;	
	}
	
	
	public function getDeleteStudentMark(){
		$encrypt_id = $this->input->post('encrypt_id');
		$this->common_model->delete_data('sms_stu_mark',$encrypt_id);
		print "<div class='alert alert-success' role='alert'>
		<strong>Well done!</strong> Data deleted successfully.</div>"; die;	
	}
	
	public function getUpdateStudentMark(){
		$encrypt_id = $this->input->post('encrypt_id');
		$mark		= $this->input->post('mark');	
		$encrypt_id				=	$encrypt_id;
		$param['mark']		=	$mark;
		$param['update_date']		=	currentDateTime();
		$param['updated_by']		=	$this->session->userdata('SMS_ADMIN_ID');
		$this->common_model->edit_data('sms_stu_mark',$param,$encrypt_id);		
	}
	
	public function getStudentInfoViewList(){
		$class_id 	= addslashes($this->input->post('class_id'));
		$section_id = addslashes($this->input->post('section_id'));
		$assessment_id = addslashes($this->input->post('assessment_id'));
		$subject_id = addslashes($this->input->post('subject_id'));
		$actionType = addslashes($this->input->post('actionType'));
		
		$subQuery         =	"SELECT stud.student_id,stud.encrypt_id as stu_encrypt_id,stu_cl.student_roll_no,
							CONCAT(stud.student_f_name,' ',stud.student_l_name) as student_name,ass.max_mark 
							FROM sms_student_class stu_cl 
							LEFT JOIN sms_students stud on stu_cl.student_qunique_id=stud.student_qunique_id 
							left join sms_set_assessment ass on stu_cl.school_id = ass.school_id 
							WHERE stu_cl.franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND stu_cl.school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND stu_cl.branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND stu_cl.board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
							AND stu_cl.class_id='".$class_id."' AND stu_cl.section_id='".$section_id."' 
							and ass.encrypt_id='".$assessment_id."' group by stu_cl.student_roll_no";
		$data['STUDENTDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
		if($actionType=='print'){
		$this->print_in_pdf($data['STUDENTDATA']);	
		}
		else {
		$count = sizeof($data['STUDENTDATA']);
		if($count>1){
?>
<div class="row">
    <div class="col-lg-12">
	 <span id="msg">			
		</span>
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
         <span>View Marks </span> 		
		 </header>
        <div class="panel-body">
    
	<div class="row">
		<div class="col-lg-12">
			<div class="col-lg-1">Sr.No.</div>
			<div class="col-lg-2">Roll No.</div>
			<div class="col-lg-4">Student Name</div>
			<div class="col-lg-2">Max.Mark</div>
			<div class="col-lg-3">Mark</div>		
		</div><br><hr>
		<?php  
		$i=1;
		foreach($data['STUDENTDATA'] as $row){	

		$subQuery         =	"select mark,encrypt_id from sms_stu_mark
							WHERE franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
							AND class_id='".$class_id."' AND section_id='".$section_id."' 
							and assessment_id='".$assessment_id."' and subject_id='".$subject_id."'
							and stu_id='".$row['stu_encrypt_id']."'";
		//print $subQuery;					
        $data['MARKDATA'] = $this->common_model->get_data_by_query('single', $subQuery);
//		print "<pre>"; print_r($data['MARKDATA']['mark']); die;
		if(!empty($data['MARKDATA']['mark'])){
		?>	
			<input type="hidden" name="stu_id<?=$i; ?>" id="stu_id<?=$i; ?>" value="<?=$row['stu_encrypt_id']; ?>">		
            <div class="col-lg-12" id="stu-row<?=$i; ?>">
			<div class="col-lg-1"><?=$i; ?></div>
			<div class="col-lg-2" id="student_roll_no<?=$i; ?>"><?=$row['student_roll_no']; ?></div>
			<div class="col-lg-4" id="stu_name<?=$i; ?>"><?=$row['student_name']; ?></div>
			<div class="col-lg-2" id="max_mark<?=$i; ?>"><?=$row['max_mark']; ?></div>
			<div class="col-lg-3"><?=$data['MARKDATA']['mark']; ?></div>			
		    </div><br><hr>		
		<?php 		
			}
			$i=$i+1;
		} 
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
	
	
	public function getStudentdataRecord(){
		$class_id = $this->input->post('class_id');
		$section_id = $this->input->post('section_id');
		
		$subQuery         =	"SELECT stud.student_id,stud.encrypt_id as stu_encrypt_id,stu_cl.student_roll_no,
							CONCAT(stud.student_f_name,' ',stud.student_l_name) as student_name,ass.max_mark 
							FROM sms_student_class stu_cl 
							LEFT JOIN sms_students stud on stu_cl.student_qunique_id=stud.student_qunique_id 
							left join sms_set_assessment ass on stu_cl.school_id = ass.school_id 
							WHERE stu_cl.franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND stu_cl.school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND stu_cl.branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND stu_cl.board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
							AND stu_cl.class_id='".$class_id."' AND stu_cl.section_id='".$section_id."' 
							group by stu_cl.student_roll_no";
		print $subQuery ; //die;
        $data['STUDENTDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);
		foreach($data['STUDENTDATA'] as $row){		
			echo "<option value='".$row['stu_encrypt_id']."'>".$row['student_name']."</option>";
		}
	}
	
	
		public function getAllSubjectdata(){
		$class_id 	= addslashes($this->input->post('class_id'));
		$section_id = addslashes($this->input->post('section_id'));
		$assessment_id = addslashes($this->input->post('assessment_id'));
		$subject_id = addslashes($this->input->post('subject_id'));
		$student_id = addslashes($this->input->post('student_id'));
		$subQuery         =	"SELECT stud.student_id,stud.encrypt_id as stu_encrypt_id,stu_cl.student_roll_no, CONCAT(stud.student_f_name,' ',stud.student_l_name) as student_name,ass.max_mark,subj.subject_name,stu_mark.mark 
							FROM sms_student_class stu_cl 
							LEFT JOIN sms_students stud on stu_cl.student_qunique_id=stud.student_qunique_id 
							left join sms_set_assessment ass on stu_cl.school_id = ass.school_id
							LEFT JOIN sms_stu_mark stu_mark on stud.encrypt_id=stu_mark.stu_id
							LEFT JOIN sms_subject subj on stu_mark.subject_id=subj.encrypt_id
							WHERE stu_cl.franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND stu_cl.school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND stu_cl.branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND stu_cl.board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
							AND stu_cl.class_id='".$class_id."' AND stu_cl.section_id='".$section_id."' 
							AND ass.encrypt_id='".$assessment_id."' AND stu_mark.stu_id='".$student_id."' GROUP BY subj.encrypt_id";
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
         <span>View Marks </span> 		
		 </header>
        <div class="panel-body">
    
	<div class="row">
		<div class="col-lg-12">
			<div class="col-lg-3">Sr.No.</div>
			<div class="col-lg-3">Subject</div>		
			<div class="col-lg-3">Max.Mark</div>
			<div class="col-lg-3">Mark</div>		
		</div><br><hr>
		<?php  
		$i=1;
		foreach($data['STUDENTDATA'] as $row){			
		?>	
			<input type="hidden" name="stu_id<?=$i; ?>" id="stu_id<?=$i; ?>" value="<?=$row['stu_encrypt_id']; ?>">		
            <div class="col-lg-12" id="stu-row<?=$i; ?>">
			<div class="col-lg-3"><?=$i; ?></div>
			<div class="col-lg-3" id="student_roll_no<?=$i; ?>"><?=$row['subject_name']; ?></div>
			<div class="col-lg-3" id="stu_name<?=$i; ?>"><?=$row['max_mark']; ?></div>		
			<div class="col-lg-3"><?=$row['mark']; ?></div>			
		    </div><br><hr>		
		<?php 					
			$i=$i+1;
		} 
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
	
	
	/* * *********************************************************************
     * * Function name: print_in_pdf
     * * Developed By: Jitendra Singh
     * * Purpose: This function used for print in pdf
     * * Date: 07 MAY 2019
     * ********************************************************************** */
    public function print_in_pdf($printpdfId = '') {
		$this->load->library('Mpdf');
        $this->load->view('assessment_mark/printinpdf');
        $this->download_pdf('student_mark_details.pdf');
    } // END OF FUNCTION	
	
	
	  public function view_student_report_card($class_id = '',$section_id='',$assessment_id='',$student_id='',$action='',$subject_id='') {				
		$this->load->library('Mpdf');
		if($action=='StudentInfo'){
		$subQuery         =	"SELECT stud.student_id,stud.encrypt_id as stu_encrypt_id,
							stu_cl.student_roll_no, CONCAT(stud.student_f_name,' ',stud.student_l_name) as student_name,
							ass.max_mark,subj.subject_name,stu_mark.mark,ass.name as assesment_name,term.name as term_name,
							CONCAT(admin.admin_name) as school_name,admin.admin_email_id,admin.admin_mobile_number,
							admin.admin_image as logo,class.class_name,section.class_section_name   
							FROM sms_student_class stu_cl 
							LEFT JOIN sms_students stud on stu_cl.student_qunique_id=stud.student_qunique_id 
							left join sms_set_assessment ass on stu_cl.school_id = ass.school_id
							LEFT JOIN sms_stu_mark stu_mark on stud.encrypt_id=stu_mark.stu_id
							LEFT JOIN sms_subject subj on stu_mark.subject_id=subj.encrypt_id
							LEFT JOIN  sms_exam_term term on stu_mark.term_id=term.encrypt_id
							LEFT JOIN sms_admin admin on stu_mark.school_id=admin.encrypt_id
							LEFT JOIN sms_classes class on stu_mark.class_id=class.encrypt_id
							LEFT JOIN sms_class_section section on stu_mark.section_id=section.encrypt_id
							WHERE stu_cl.franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND stu_cl.school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND stu_cl.branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND stu_cl.board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
							AND stu_cl.class_id='".$class_id."' AND stu_cl.section_id='".$section_id."' 
							AND ass.encrypt_id='".$assessment_id."' AND stu_mark.stu_id='".$student_id."' GROUP BY subj.encrypt_id";
		}
		else{
		$subQuery         =	"SELECT stud.student_id,stud.encrypt_id as stu_encrypt_id,
							stu_cl.student_roll_no, CONCAT(stud.student_f_name,' ',stud.student_l_name) as student_name,
							ass.max_mark,subj.subject_name,stu_mark.mark,ass.name as assesment_name,term.name as term_name,
							CONCAT(admin.admin_name) as school_name,admin.admin_email_id,admin.admin_mobile_number,
							admin.admin_image as logo,class.class_name,section.class_section_name   
							FROM sms_student_class stu_cl 
							LEFT JOIN sms_students stud on stu_cl.student_qunique_id=stud.student_qunique_id 
							left join sms_set_assessment ass on stu_cl.school_id = ass.school_id
							LEFT JOIN sms_stu_mark stu_mark on stud.encrypt_id=stu_mark.stu_id
							LEFT JOIN sms_subject subj on stu_mark.subject_id=subj.encrypt_id
							LEFT JOIN  sms_exam_term term on stu_mark.term_id=term.encrypt_id
							LEFT JOIN sms_admin admin on stu_mark.school_id=admin.encrypt_id
							LEFT JOIN sms_classes class on stu_mark.class_id=class.encrypt_id
							LEFT JOIN sms_class_section section on stu_mark.section_id=section.encrypt_id
							WHERE stu_cl.franchise_id='" . $this->session->userdata('SMS_ADMIN_FRANCHISE_ID') . "' 
							AND stu_cl.school_id='" . $this->session->userdata('SMS_ADMIN_SCHOOL_ID') . "' 
							AND stu_cl.branch_id='" . $this->session->userdata('SMS_ADMIN_BRANCH_ID') . "' 
							AND stu_cl.board_id='" . $this->session->userdata('SMS_ADMIN_BOARD_ID') . "' 
							AND stu_cl.class_id='".$class_id."' AND stu_cl.section_id='".$section_id."' 
							AND ass.encrypt_id='".$assessment_id."'  and stu_mark.subject_id='".$subject_id."' GROUP BY stu_cl.student_roll_no";	
		}
		//print $subQuery; die;
        $data['STUDENTDATA'] = $this->common_model->get_data_by_query('multiple', $subQuery);		
		$data['action'] = $action;
		$data['school_name'] = $data['STUDENTDATA'][0]['school_name'];
		$data['email'] = $data['STUDENTDATA'][0]['admin_email_id'];
		$data['contact_no'] = $data['STUDENTDATA'][0]['admin_mobile_number'];
		$data['assesment_name'] = $data['STUDENTDATA'][0]['assesment_name'];
		$data['term_name'] = $data['STUDENTDATA'][0]['term_name'];
		$data['logo'] = $data['STUDENTDATA'][0]['logo'];
		$data['class_name'] = $data['STUDENTDATA'][0]['class_name'];
		$data['section_name'] = $data['STUDENTDATA'][0]['class_section_name'];
		$data['student_name'] = $data['STUDENTDATA'][0]['student_name'];
		$data['student_roll_no'] = $data['STUDENTDATA'][0]['student_roll_no'];
		$data['subject_name'] = $data['STUDENTDATA'][0]['subject_name'];
		
		$this->load->view('assessment_mark/viewreportcard',$data);  
		$this->download_pdf('student_mark_details.pdf');		
    } // END OF FUNCTION	
	
	
	 /* * *********************************************************************
     * * Function name: download_pdf
     * * Developed By: Jitendra Singh
     * * Purpose: This function used for download pdf
     * * Date: 07 MAY 2019
     * ********************************************************************** */
    public function download_pdf($file = '') {
        header('Content-Description: File Transfer');
        // We'll be outputting a PDF
        header('Content-type: application/pdf');
        // It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="' . $file . '"');
        // The PDF source is in original.pdf
        readfile($this->config->item("root_path") . "assets/downloadpdf/report_card/" . $file);

        @unlink($this->config->item("root_path") . "assets/downloadpdf/report_card/" . $file);
        exit;
    } // END OF FUNCTION	
}
