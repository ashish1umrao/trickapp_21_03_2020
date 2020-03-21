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
      Time Table details</a></li>
  <li class="active">
    
    View Time Table Details</li>
</ol>
{message}
<div class="form-w3layouts"> 
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        
        <header class="panel-heading"> <span class="tools pull-left">
                
              
           View Time Table Details</span> </header>
       <?php if($EDITDATA):
       echo   $studentDetails ; endif; ?>
        
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['student_qunique_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
            <fieldset>
            <legend>Time Table Search </legend>
            <div class="col-lg-12 class-parent">
			<div class="col-lg-4">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Select Teacher <span class="required">*</span></label>
                 <div class="col-lg-8">
                  <?php if(set_value('id')): $subjectheadid	=	set_value('id'); elseif($TEACHERDATAINFO['id']): $id	=	stripslashes($TEACHERDATAINFO['term_id']); else: $id	=	''; endif; ?>
                  <select name="teacher_id" id="teacher_id" class="form-control required">
                  	<option value="">Please Select</option>
                    <?php if($TEACHERDATA <> ""): foreach($TEACHERDATA as $TEACHERDATAINFO): ?>
                    	<option value="<?php echo $TEACHERDATAINFO['encrypt_id']; ?>" <?php if($TEACHERDATAINFO['encrypt_id'] == $id): echo 'selected="selected"'; endif; ?>><?php echo ucwords(stripslashes($TEACHERDATAINFO['teacher_name'])); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('term_id')): ?>
                  <p for="teacher_id" generated="true" class="error"><?php echo form_error('teacher_id'); ?></p>
                  <?php endif; ?>
                </div>
                </div>
              </div>
			<div class="col-lg-4">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Select Day <span class="required">*</span></label>
                 <div class="col-lg-8">
                  <?php if(set_value('id')): $subjectheadid	=	set_value('id'); elseif($EDITDATA['id']): $id	=	stripslashes($EDITDATA['term_id']); else: $id	=	''; endif; ?>
                  <select name="day_id" id="day_id" class="form-control required">
                  	<option value="">Please Select</option>
                    <?php if($WORKINGDAYDATA <> ""): foreach($WORKINGDAYDATA as $WORKINGDAYDATAINFO): ?>
                    	<option value="<?php echo $WORKINGDAYDATAINFO['encrypt_id']; ?>" <?php if($WORKINGDAYDATAINFO['encrypt_id'] == $id): echo 'selected="selected"'; endif; ?>><?php echo ucwords(stripslashes($WORKINGDAYDATAINFO['working_day_name'])); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('term_id')): ?>
                  <p for="term_id" generated="true" class="error"><?php echo form_error('term_id'); ?></p>
                  <?php endif; ?>
                </div>
                </div>
              </div>
			  
			  <div class="col-lg-4">
			  <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" onclick="return getStudentInfo();"/>
                <a href="<?php echo correctLink('{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> 
				
				<br><br><span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
			  </div>
			  
			 </span>       
            </div>            
          </fieldset>         
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
	var teacher_id = $("#teacher_id").val();
	var day_id = $("#day_id").val();	
	 $.ajax({
            type: 'post',
            url:  'getTeacherTimeTableInfoViewList',
            data: {teacher_id: teacher_id,day_id: day_id},
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
