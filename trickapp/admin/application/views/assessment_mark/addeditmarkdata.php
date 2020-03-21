<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>



<script>
$(function(){
	$("#class_area").hide();
	$("#student_admission_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
	$("#student_relieving_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_dob").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      Assessment details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    Assessment details</li>
</ol>
{message}
<div class="form-w3layouts">
   
   <ul class="nav nav-tabs blue_tab">
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/index">Set Assessment</a></li>
      <li class="active"><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditmarkdata">Enter Assessment Mark</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditmarkdataviewlist">Mark List</a></li>
    </ul>
 
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        
        <header class="panel-heading"> <span class="tools pull-left">
                
              
          <?=$EDITDATA?'Edit':'Add'?>
                Assessment details</span> </header>
       <?php if($EDITDATA):
       echo   $studentDetails ; endif; ?>
        
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['student_qunique_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
            <fieldset>
            <legend>Assessment Details</legend>
            <div class="col-lg-12 class-parent">
			<div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Assessment Name <span class="required">*</span></label>
                 <div class="col-lg-6">
                  <?php if(set_value('id')): $subjectheadid	=	set_value('id'); elseif($EDITDATA['id']): $id	=	stripslashes($EDITDATA['term_id']); else: $id	=	''; endif; ?>
                  <select name="assessment_id" id="assessment_id" class="form-control required">
                  	<option value="">Select Term</option>
                    <?php if($SHEADDATA <> ""): foreach($SHEADDATA as $SHEADINFO): ?>
                    	<option value="<?php echo $SHEADINFO['encrypt_id']; ?>" <?php if($SHEADINFO['encrypt_id'] == $id): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($SHEADINFO['name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('term_id')): ?>
                  <p for="assessment_id" generated="true" class="error"><?php echo form_error('assessment_id'); ?></p>
                  <?php endif; ?>
                </div>
                </div>
              </div>
			<div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Term Name <span class="required">*</span></label>
                 <div class="col-lg-8">
                  <?php if(set_value('id')): $subjectheadid	=	set_value('id'); elseif($EDITDATA['id']): $id	=	stripslashes($EDITDATA['term_id']); else: $id	=	''; endif; ?>
                  <select name="term_id" id="term_id" class="form-control required">
                  	<option value="">Please Select</option>
                    <?php if($SHEADDATA1 <> ""): foreach($SHEADDATA1 as $SHEADINFO): ?>
                    	<option value="<?php echo $SHEADINFO['encrypt_id']; ?>" <?php if($SHEADINFO['encrypt_id'] == $id): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($SHEADINFO['name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('term_id')): ?>
                  <p for="term_id" generated="true" class="error"><?php echo form_error('term_id'); ?></p>
                  <?php endif; ?>
                </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Class name<span class="required">*</span></label>
                  <div class="col-lg-6">
                    <?php if(set_value('class_id')): $classid = set_value('class_id'); else: $classid  = $EDITDATA['class_id'];  endif; ?>
                    <select name="class_id" id="class_id" class="form-control required">
                      <option value="">Select class name</option>
                      <?php if($CLASSDATA <> ""): foreach($CLASSDATA as $CLASSINFO): ?>
                      <option value="<?php echo $CLASSINFO['encrypt_id']; ?>" <?php if($CLASSINFO['encrypt_id'] == $classid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CLASSINFO['class_name']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('class_id')): ?>
                    <p for="class_id" generated="true" class="error"><?php echo form_error('class_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Section name<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('section_id')): $sectionid = set_value('section_id'); else: $sectionid  = $EDITDATA['section_id'];  endif; ?>
                    <select name="section_id" id="section_id" class="form-control required" onchange="return getSubjectdata(this.value);">
                      <option value="">Select section name</option>
                    </select>
                    <?php if(form_error('section_id')): ?>
                    <p for="section_id" generated="true" class="error"><?php echo form_error('section_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
			 </span>
			  <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Subject <span class="required">*</span></label>
                   <div class="col-lg-6">
                    <?php if(set_value('subject_id')): $classid = set_value('subject_id'); else: $subject_id  = $EDITDATA['subject_id'];  endif; ?>
                    <select name="subject_id" id="subject_id" class="form-control required">
                      <option value="">Select subject name</option>                      
                    </select>
                    <?php if(form_error('subject_id')): ?>
                    <p for="class_id" generated="true" class="error"><?php echo form_error('subject_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>            
            </div>            
          </fieldset>
          <br clear="all" />            
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" onclick="return getStudentInfo();"/>
                <a href="<?php echo correctLink('{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> 
				<span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
	  <!-- HERE LIST ---->
		<span id="student_record"></span>
</div>
<script>
$(document).on('change','#class_id',function(){
  var curobj      =   $(this);
  var classid     =   $(this).val();
  var sectionid   =   '';
  get_section_data(curobj,classid,sectionid);
});
</script>
<script>
<?php if($EDITDATA <> "" || $_POST): ?> 
$(document).ready(function(){  
  var curobj      =   $('#class_id');
  var classid     =   '<?php echo $classid; ?>';
  var sectionid   =   '<?php echo $sectionid; ?>';
  get_section_data(curobj,classid,sectionid);
});
<?php endif; ?>
</script> 
<script>
$(function(){
  UploadImage('0');
});

function getClassShow(){
	var assessment_type = document.getElementById('common_to_all').value;
	if(assessment_type=='N'){
		$("#class_area").show();
	}
	else{
		$("#class_area").hide();
	}
}

function getStudentInfo(){
	var class_id = $("#class_id").val();
	var subject_id = $("#subject_id").val();
	var section_id = $("#section_id").val();
	var assessment_id = $("#assessment_id").val();
	 $.ajax({
            type: 'post',
            url:  'getStudentInfo',
            data: {class_id: class_id,subject_id: subject_id,section_id: section_id,assessment_id: assessment_id},
            success: function (response) {
                $("#student_record").html(response);               
            }
        });
        return false;
}


function getSubjectdata(id){
	var class_id = $("#class_id").val();
	 $.ajax({
            type: 'post',
            url:  'getSubjectdata',
            data: {class_id: class_id,section_id: id},
            success: function (response) {
                $("#subject_id").html(response);               
            }
        });
        return false;	
}

function deleteStudentMark(id,index){
		var encrypt_id = id;
        if(confirm("Are you sure you want to delete this?"))  
        {  
            $.ajax({  
                url:"getDeleteStudentMark",  
                method:"POST",  
                data:{encrypt_id:encrypt_id},  
                dataType:"text",  
                success:function(data){  
                    getStudentInfo();   
                }  
            });  
			return false;
        }  
} 


   
</script>
