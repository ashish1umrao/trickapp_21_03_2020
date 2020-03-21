<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('subjectListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      subject details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    subject details</li>
  <li class="pull-right"><a href="<?php echo correctLink('subjectListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          subject details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Subject head name</label>
                <div class="col-lg-6">
                  <?php if(set_value('subject_head_id')): $subjectheadid	=	set_value('subject_head_id'); elseif($EDITDATA['subject_head_id']): $subjectheadid	=	stripslashes($EDITDATA['subject_head_id']); else: $subjectheadid	=	''; endif; ?>
                  <select name="subject_head_id" id="subject_head_id" class="form-control">
                  	<option value="">Select subject head name</option>
                    <?php if($SHEADDATA <> ""): foreach($SHEADDATA as $SHEADINFO): ?>
                    	<option value="<?php echo $SHEADINFO['encrypt_id']; ?>" <?php if($SHEADINFO['encrypt_id'] == $subjectheadid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($SHEADINFO['subject_head_name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('subject_head_id')): ?>
                  <p for="subject_head_id" generated="true" class="error"><?php echo form_error('subject_head_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Subject name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="subject_name" id="subject_name"value="<?php if(set_value('subject_name')): echo set_value('subject_name'); else: echo stripslashes($EDITDATA['subject_name']);endif; ?>" class="form-control required" placeholder="Subject name">
                  <?php if(form_error('subject_name')): ?>
                  <p for="subject_name" generated="true" class="error"><?php echo form_error('subject_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Subject short name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="subject_short_name" id="subject_short_name"value="<?php if(set_value('subject_short_name')): echo set_value('subject_short_name'); else: echo stripslashes($EDITDATA['subject_short_name']);endif; ?>" class="form-control required" placeholder="Subject short name">
                  <?php if(form_error('subject_short_name')): ?>
                  <p for="subject_short_name" generated="true" class="error"><?php echo form_error('subject_short_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('subjectListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>