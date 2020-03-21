<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('sessionMonthAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      session start month details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    session start month details</li>
  <li class="pull-right"><a href="<?php echo correctLink('sessionMonthAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          session start month details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              
              <div class="form-group">
               <?php if(set_value('session_month_name')): $sessionmonthname	=	set_value('session_month_name'); elseif($MONDATA): $sessionmonthname	=	$MONDATA; else: $sessionmonthname	=	array(); endif; ?>
               <?php if($MONTHDATA <> ""): $i=1; foreach($MONTHDATA as $MONTHINFO): ?>
                <div class="col-lg-3">
                  <input type="radio" name="session_month_name[]" id="session_month_name<?php echo $i; ?>" value="<?php echo $MONTHINFO['month_name'].'_____'.$MONTHINFO['month_short_name']; ?>" <?php if(in_array($MONTHINFO['month_name'],$sessionmonthname)): echo 'checked="checked"'; endif; ?> class="required">
                  &nbsp;<label for="working_day_name<?php echo $i; ?>"><?php echo addslashes($MONTHINFO['month_name']); ?></label>
                </div><?php if($i==4 || $i==8): echo '<br clear="all" /><br clear="all" />'; endif; ?>
               <?php $i++; endforeach; endif; ?> 
               <?php if(form_error('session_month_name1')): ?>
                  <p for="session_month_name1" generated="true" class="error"><?php echo form_error('session_month_name1'); ?></p>
                  <?php endif; ?>
              </div>
            <div class="form-group">
              <div class="col-lg-12">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('sessionMonthAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>