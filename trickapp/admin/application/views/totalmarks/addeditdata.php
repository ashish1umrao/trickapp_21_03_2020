<script type="text/javascript">
function sum() {
      var txtFirstNumberValue = document.getElementById('txt1').value;
      var txtSecondNumberValue = document.getElementById('txt2').value;
      var result = parseInt(txtFirstNumberValue) + parseInt(txtSecondNumberValue);
      if (!isNaN(result)) {
         document.getElementById('txt3').value = result;
      }	  
}
function getValidate(){
	var total_mark = $("#txt3").val();	
	if(total_mark!=100){
		  alert('Total marks is required 100!');
		  return false;
	}
}
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('subjectHeadAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      total mark details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    total mark details</li>
  <li class="pull-right"><a href="<?php echo correctLink('subjectHeadAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          total mark details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Written Exam <span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="written_exam" id="txt1" value="<?php if(set_value('written_exam')): echo set_value('written_exam'); else: echo stripslashes($EDITDATA['written_exam']);endif; ?>" class="form-control required" placeholder="Written Exam" onkeyup="sum();">
                  <?php if(form_error('written_exam')): ?>
                  <p for="name" generated="true" class="error"><?php echo form_error('written_exam'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Assessment Exam <span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="assessment_exam" autocomplete="off" id="txt2" value="<?php if(set_value('assessment_exam')): echo set_value('assessment_exam'); else: echo $EDITDATA['assessment_exam']; endif; ?>" class="form-control required" placeholder="Assessment Exam" onkeyup="sum();">
                  <?php if(form_error('start_date')): ?>
                  <p for="assessment_exam" generated="true" class="error"><?php echo form_error('assessment_exam'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
			   <div class="form-group">
                <label class="col-lg-3 control-label">Total Mark <span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="total_mark" autocomplete="off" id="txt3" value="<?php if(set_value('total_mark')): echo set_value('total_mark'); else: echo $EDITDATA['total_mark']; endif; ?>" class="form-control required" placeholder="Total Mark" readonly>
                  <?php if(form_error('lower_mark')): ?>
                  <p for="total_mark" generated="true" class="error"><?php echo form_error('total_mark'); ?></p>
                  <?php endif; ?>
                </div>
              </div>			  
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" onclick="return getValidate();"/>
                <a href="<?php echo correctLink('subjectHeadAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>

