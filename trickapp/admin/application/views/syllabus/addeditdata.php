<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('syllabusListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      Syllabus details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    Syllabus details</li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
                Syllabus details</span> </header>
       <?php if($EDITDATA):
       echo   $studentDetails ; endif; ?>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['class_syllabus_id']?>"<?php //print_r($EDITDATA); die; ?>/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
            <fieldset>
            <legend>Syllabus Details</legend>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Class name<span class="required">*</span></label>
                 <?php if($EDITDATA['class_id'] == ""): ?>
					<div class="col-lg-6">
						<?php if(set_value('class_id')): $classid = set_value('class_id'); else: $classid  = $EDITDATA['class_id'];  endif; ?>
						<select name="class_id" id="class_id" class="form-control" onchange="return getSubjectdata(this.value);" required="required" >
						  <option value="">Select class name</option>
						  <?php if($CLASSDATA <> ""): foreach($CLASSDATA as $CLASSINFO): ?>
						  <option value="<?php echo $CLASSINFO['encrypt_id']; ?>" <?php if($CLASSINFO['encrypt_id'] == $classid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CLASSINFO['class_name']); ?></option>
						  <?php endforeach; endif; ?>
						</select>
						<?php if(form_error('class_id')): ?>
						<p for="class_id" generated="true" class="error"><?php echo form_error('class_id'); ?></p>
						<?php endif; ?>
					  </div>
			<?php endif; ?>
                </div>
              </div>             
			 </span>
			  <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Subject <span class="required">*</span></label>
                   <div class="col-lg-6"> 
					<?php if($EDITDATA == ""): ?>
						 <select name="subject_id" id="subject_id" class="form-control" required="required" >
                      <option value="">Select subject name</option>                      
                    </select> 
			
					<?php endif; ?>
                  </div>
                </div>
              </div>            
            </div>   
             <div class="col-lg-12">
              <label>Message</label>
              <?php if(set_value('message')): $newsdescription = set_value('message'); elseif($EDITDATA['syllabus']): $newsdescription = stripslashes($EDITDATA['syllabus']); else: $newsdescription = ''; endif; ?>
                  <textarea name="syllabus" id="message" required="required"><?php echo $newsdescription; ?></textarea>
                  <?php if(form_error('message')): ?>
                  <p for="message" generated="true" class="error"><?php echo form_error('message'); ?></p>
                  <?php endif; ?>
            </div>         
          </fieldset>

          <br clear="all" />            
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
				<a href="<?php echo correctLink('syllabusListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a>
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
</script>
<script>
$(document).ready(function(){
  create_editor_for_textarea('message');
});
</script>
