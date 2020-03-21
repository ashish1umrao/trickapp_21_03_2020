<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('newsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      Notification </a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    Notification </li>
  <li class="pull-right"><a href="<?php echo correctLink('newsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
         Notification </span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <div class="form-group">
              <label class="col-lg-3 control-label">Notification Title<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="title" id="title"value="<?php if(set_value('title')): echo set_value('title'); else: echo stripslashes($EDITDATA['title']);endif; ?>" class="form-control required" placeholder="Notification Title">
                <?php if(form_error('title')): ?>
                <p for="title" generated="true" class="error"><?php echo form_error('title'); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Content<span class="required">*</span></label>
              <div class="col-lg-6">
                <?php if(set_value('message')): $newsdescription = set_value('message'); elseif($EDITDATA['message']): $newsdescription = stripslashes($EDITDATA['message']); else: $newsdescription = ''; endif; ?>
                  <textarea name="message" id="message" ><?php echo $newsdescription; ?></textarea>
                  <?php if(form_error('message')): ?>
                  <p for="message" generated="true" class="error"><?php echo form_error('message'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
           
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('newsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  create_editor_for_textarea('message');
});
</script>
<script>
$(function(){
  UploadImage('0');
});
</script>
