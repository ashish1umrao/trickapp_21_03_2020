<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('subjectHeadAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      subject head details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    subject head details</li>
  <li class="pull-right"><a href="<?php echo correctLink('subjectHeadAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          subject head details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Subject head name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="subject_head_name" id="subject_head_name"value="<?php if(set_value('subject_head_name')): echo set_value('subject_head_name'); else: echo stripslashes($EDITDATA['subject_head_name']);endif; ?>" class="form-control required" placeholder="Subject head name">
                  <?php if(form_error('subject_head_name')): ?>
                  <p for="subject_head_name" generated="true" class="error"><?php echo form_error('subject_head_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Subject head short name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="subject_head_short_name" id="subject_head_short_name"value="<?php if(set_value('subject_head_short_name')): echo set_value('subject_head_short_name'); else: echo stripslashes($EDITDATA['subject_head_short_name']);endif; ?>" class="form-control required" placeholder="Subject head short name">
                  <?php if(form_error('subject_head_short_name')): ?>
                  <p for="subject_head_short_name" generated="true" class="error"><?php echo form_error('subject_head_short_name'); ?></p>
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