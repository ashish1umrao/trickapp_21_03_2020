<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
$(function(){
	 $("#startdate").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,minDate: 0,yearRange: "2017:2050"});
  $("#enddate").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,minDate: 0,yearRange: "2017:2050"});
 
});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('holidaylistAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      holiday details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    holiday details</li>
  <li class="pull-right"><a href="<?php echo correctLink('holidaylistAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          holiday details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              
                
            
           
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Purpose<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="purpose" id="purpose" value="<?php if(set_value('purpose')): echo set_value('purpose'); else: echo stripslashes($EDITDATA['purpose']);endif; ?>" class="form-control required" placeholder="Title">
                  <?php if(form_error('purpose')): ?>
                  <p for="purpose" generated="true" class="error"><?php echo form_error('purpose'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            
              <div class="form-group">
                <label class="col-lg-3 control-label">Start Date<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="startdate" id="startdate" value="<?php if(set_value('startdate')): echo set_value('startdate'); else: echo YYMMDDtoDDMMYY($EDITDATA['startdate']);endif; ?>" class="form-control required" placeholder="From Date">
                  <?php if(form_error('startdate')): ?>
                  <p for="startdate" generated="true" class="error"><?php echo form_error('startdate'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            
                <div class="form-group">
                <label class="col-lg-3 control-label">End Date<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="enddate" id="enddate" value="<?php if(set_value('enddate')): echo set_value('enddate'); else: echo YYMMDDtoDDMMYY($EDITDATA['enddate']);endif; ?>" class="form-control required" placeholder="End Date">
                  <?php if(form_error('enddate')): ?>
                  <p for="enddate" generated="true" class="error"><?php echo form_error('enddate'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
      
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('holidaylistAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
