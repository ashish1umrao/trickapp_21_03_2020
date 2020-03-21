<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('workingDaysAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      working day details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    working day details</li>
  <li class="pull-right"><a href="<?php echo correctLink('workingDaysAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          working day details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              
              <div class="form-group">
               <?php if(set_value('working_day_name')): $workingdayname	=	set_value('same_address'); elseif($DAYSDATA): $workingdayname	=	$DAYSDATA; else: $workingdayname	=	array(); endif; ?>
               <?php if($WDAYSDATA <> ""): $i=1; foreach($WDAYSDATA as $WDAYSINFO): ?>
                <div class="col-lg-3">
                  <input type="checkbox" name="working_day_name[]" id="working_day_name<?php echo $i; ?>" value="<?php echo $WDAYSINFO['day_name'].'_____'.$WDAYSINFO['day_short_name']; ?>" <?php if(in_array($WDAYSINFO['day_name'],$workingdayname)): echo 'checked="checked"'; endif; ?> class="required">
                  &nbsp;<label for="working_day_name<?php echo $i; ?>"><?php echo addslashes($WDAYSINFO['day_name']).' ('.addslashes($WDAYSINFO['day_short_name']).')'; ?></label>
                </div><?php if($i==4): echo '<br clear="all" /><br clear="all" />'; endif; ?>
               <?php $i++; endforeach; endif; ?> 
               <?php if(form_error('working_day_name1')): ?>
                  <p for="working_day_name1" generated="true" class="error"><?php echo form_error('working_day_name1'); ?></p>
                  <?php endif; ?>
              </div>
            <div class="form-group">
              <div class="col-lg-12">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('workingDaysAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>