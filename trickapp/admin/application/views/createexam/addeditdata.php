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
      exam details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    exam details</li>
  <li class="pull-right"><a href="<?php echo correctLink('subjectHeadAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          exam details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Term <span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('id')): $subjectheadid	=	set_value('id'); elseif($EDITDATA['id']): $id	=	stripslashes($EDITDATA['term_id']); else: $id	=	''; endif; ?>
                  <select name="term_id" id="term_id" class="form-control">
                  	<option value="">Select Term</option>
                    <?php if($SHEADDATA <> ""): foreach($SHEADDATA as $SHEADINFO): ?>
                    	<option value="<?php echo $SHEADINFO['encrypt_id']; ?>" <?php if($SHEADINFO['encrypt_id'] == $id): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($SHEADINFO['name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('term_id')): ?>
                  <p for="term_id" generated="true" class="error"><?php echo form_error('term_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Exam Name <span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="name" autocomplete="off" id="name" value="<?php if(set_value('name')): echo set_value('name'); else: echo $EDITDATA['name']; endif; ?>" class="form-control" placeholder="Exam Name">
                  <?php if(form_error('name')): ?>
                  <p for="name" generated="true" class="error"><?php echo form_error('name'); ?></p>
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