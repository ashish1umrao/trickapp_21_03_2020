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
  <li><a href="<?php echo correctLink('classwisefeeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
  Class Wise Fee</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    Class Wise Fee</li>
  <li class="pull-right"><a href="<?php echo correctLink('classwisefeeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          Class Wise Fee</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <div class="form-group">
                <label class="col-lg-3 control-label">Class name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('class_id')): $classid	=	set_value('class_id'); elseif($EDITDATA['class_id']): $classid	=	stripslashes($EDITDATA['class_id']); else: $classid	=	''; endif; ?>
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
              <div class="form-group">
                <label class="col-lg-3 control-label">Fee Head<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('fee_head_name')): $feeheadid	=	set_value('fee_head_name'); elseif($EDITDATA['fee_head_name']): $feeheadid	=	stripslashes($EDITDATA['fee_head_name']); else: $feeheadid	=	''; endif; ?>
                  <select name="fee_head_name" id="fee_head_name" class="form-control required">
                  	<option value="">Select Fee Heading</option>
                    <?php if($FEEHEADINGLIST <> ""):  foreach($FEEHEADINGLIST as $FEEHEAD): ?>
                    	<option value="<?php echo $FEEHEAD->encrypt_id; ?>" <?php if($FEEHEAD->encrypt_id == $feeheadid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($FEEHEAD->heading_type); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('fee_head_name')): ?>
                  <p for="fee_head_name" generated="true" class="error"><?php echo form_error('fee_head_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Per Month Fee <span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="per_month_fee" id="per_month_fee"value="<?php if(set_value('per_month_fee')): echo set_value('per_month_fee'); else: echo stripslashes($EDITDATA['per_month_fee']);endif; ?>" class="form-control required number" placeholder="Per Month Fee" autocomplete="off" minlength="3" maxlength="5">
                  <?php if(form_error('per_month_fee')): ?>
                  <p for="per_month_fee" generated="true" class="error"><?php echo form_error('per_month_fee'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('classwisefeeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>