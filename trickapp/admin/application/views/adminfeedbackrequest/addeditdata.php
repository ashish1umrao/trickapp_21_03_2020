<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentleaverequestAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      leave types details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    leave types details</li>
  <li class="pull-right"><a href="<?php echo correctLink('studentleaverequestAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
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
              <label class="col-lg-3 control-label">Update Status<span class="required">*</span></label>
              <div class="col-lg-6">
                <?php if(set_value('leave_status')): $usertype = set_value('leave_status'); elseif($EDITDATA['leave_status']): $usertype = stripslashes($EDITDATA['leave_status']); else: $usertype = ''; endif; ?>
                <select name="leave_status" id="leave_status" class="form-control required">
                  <option value="">Select user type</option>
                  <option value="Request" <?php if($usertype == 'Request'): echo ' selected="selected"'; endif; ?>>Request</option>
                  <option value="Accepted" <?php if($usertype == 'Accepted'): echo ' selected="selected"'; endif; ?>>Accepted</option>
                  <option value="Rejected" <?php if($usertype == 'Rejected'): echo ' selected="selected"'; endif; ?>>Rejected</option>
                </select>
                <?php if(form_error('leave_status')): ?>
                <p for="leave_status" generated="true" class="error"><?php echo form_error('leave_status'); ?></p>
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