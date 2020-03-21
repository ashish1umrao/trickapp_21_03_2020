<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('leavetypeAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      leave types details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    leave types details</li>
  <li class="pull-right"><a href="<?php echo correctLink('leavetypeAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          leave types details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <div class="form-group">
              <label class="col-lg-3 control-label">User type<span class="required">*</span></label>
              <div class="col-lg-6">
                <?php if(set_value('user_type')): $usertype = set_value('user_type'); elseif($EDITDATA['user_type']): $usertype = stripslashes($EDITDATA['user_type']); else: $usertype = ''; endif; ?>
                <select name="user_type" id="user_type" class="form-control required">
                  <option value="">Select user type</option>
                  <option value="Teacher" <?php if($usertype == 'Teacher'): echo ' selected="selected"'; endif; ?>>Teacher</option>
                  <option value="Parent" <?php if($usertype == 'Parent'): echo ' selected="selected"'; endif; ?>>Parents</option>
                </select>
                <?php if(form_error('user_type')): ?>
                <p for="user_type" generated="true" class="error"><?php echo form_error('user_type'); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Leave type<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="leave_type" id="leave_type"value="<?php if(set_value('leave_type')): echo set_value('leave_type'); else: echo stripslashes($EDITDATA['leave_type']);endif; ?>" class="form-control required" placeholder="Leave type">
                <?php if(form_error('leave_type')): ?>
                <p for="leave_type" generated="true" class="error"><?php echo form_error('leave_type'); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('leavetypeAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>