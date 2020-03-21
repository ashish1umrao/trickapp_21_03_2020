<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>



<script>
$(function(){
	$("#start_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
	$("#end_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"}); 
});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('subjectHeadAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      exam term details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    term details</li>
  <li class="pull-right"><a href="<?php echo correctLink('subjectHeadAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          grade details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Grade <span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="name" id="name"value="<?php if(set_value('name')): echo set_value('name'); else: echo stripslashes($EDITDATA['name']);endif; ?>" class="form-control required" placeholder="Grade">
                  <?php if(form_error('name')): ?>
                  <p for="name" generated="true" class="error"><?php echo form_error('name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Grade Scale Value <span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="value" autocomplete="off" id="value" value="<?php if(set_value('value')): echo set_value('value'); else: echo $EDITDATA['value']; endif; ?>" class="form-control required" placeholder="Grade Scale Value">
                  <?php if(form_error('start_date')): ?>
                  <p for="value" generated="true" class="error"><?php echo form_error('value'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
			   <div class="form-group">
                <label class="col-lg-3 control-label">Lower Mark Range(in %) <span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="lower_mark" autocomplete="off" id="lower_mark" value="<?php if(set_value('lower_mark')): echo set_value('lower_mark'); else: echo $EDITDATA['lower_mark']; endif; ?>" class="form-control required" placeholder="Lower Mark Range(in %)">
                  <?php if(form_error('lower_mark')): ?>
                  <p for="lower_mark" generated="true" class="error"><?php echo form_error('lower_mark'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
			   <div class="form-group">
                <label class="col-lg-3 control-label">Upper Mark Range(in %) <span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="upper_mark" autocomplete="off" id="upper_mark" value="<?php if(set_value('upper_mark')): echo set_value('upper_mark'); else: echo $EDITDATA['upper_mark']; endif; ?>" class="form-control required" placeholder="Upper Mark Range(in %)">
                  <?php if(form_error('upper_mark')): ?>
                  <p for="upper_mark" generated="true" class="error"><?php echo form_error('upper_mark'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
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