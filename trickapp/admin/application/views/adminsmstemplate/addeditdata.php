<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('templatesmsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      SMS Template</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
   SMS Template</li>
  <li class="pull-right"><a href="<?php echo correctLink('templatesmsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
         SMS Template</span> </header>
        <div class="panel-body">
        <form id="Current_page_form_admin" method="post" role="form" action="">
                <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/><?php // print_r($EDITDATA); die; ?>
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                <div class="col-lg-12">
                     <div class="col-lg-4">
                    <div class="form-group">
                      <label>SMS Type<span class="required">*</span></label>
                      <input id="name" class="form-control " name="smstype" type="text" value="<?php if(set_value('smstype')): echo set_value('smstype'); else: echo stripslashes($EDITDATA['sms_type']);endif; ?>" placeholder="Type">
                      <?php if(form_error('smstype')): ?>
                      <label for="smstype" generated="true" class="error"><?php echo form_error('smstype'); ?></label>
                      <?php endif; ?>
                    </div>
                  </div>
                    
                   <div class="col-lg-4">
                    <div class="form-group">
                      <label>smstypedata<span class="required">*</span></label>
                      <input id="name" class="form-control " name="smstypedata" type="text" value="<?php if(set_value('smstypedata')): echo set_value('smstypedata'); else: echo stripslashes($EDITDATA['smstypename']);endif; ?>" placeholder="smstypedata">
                      <?php if(form_error('smstypedata')): ?>
                      <label for="smstypedata" generated="true" class="error"><?php echo form_error('smstypedata'); ?></label>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                 <div class="col-lg-12">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label>Content<span class="required">*</span></label>
                      <textarea  style="resize:none" name="content" id="content" class="form-control required tag-check" placeholder="Content"><?php if(set_value('content')): echo set_value('content'); else: echo stripslashes($EDITDATA['content']);endif; ?></textarea>
                       
                      <label style="color: #386a94;font-weight: 500 !important;" ><span id="remain">160</span> characters remaining<label>
                      <?php if(form_error('Content')): ?>
                      
                      <label for="Content" generated="true" class="error"><?php echo form_error('content'); ?></label>
                      <?php endif; ?>
                    </div>
                  </div>
                 </div>
                </div>
                <br clear="all" />
                <div class="col-md-12 add-padding">
                  <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-default" />
                  <a href="<?php echo correctLink('templatesmsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a>
                  <div class="col-sm-12" style="text-align:right;"> <span class="btn btn-outline btn-default">Note
                    :- <strong><span style="color:#FF0000;">*</span> Indicates
                    required field</strong> </span> </div>
                </div>
              </form>
        </div>
      </section>
    </div>
  </div>
</div>
<style>
label.error {
    color: red;
}
</style>