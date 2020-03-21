
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('roomtypeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      room type details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    room type details</li>
  <li class="pull-right"><a href="<?php echo correctLink('roomtypeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
    
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          room type details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
         
            <div class="col-lg-12">
            
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Room type Name</label>
                  <div class="col-lg-8">
                    <input type="text" name="room_type_name" id="room_type_name" value="<?php if(set_value('room_type_name')): echo set_value('room_type_name'); else: echo stripslashes($EDITDATA['room_type_name']);endif; ?>" class="form-control" placeholder="Room type Name">
                    <?php if(form_error('room_type_name')): ?>
                    <p for="room_type_name" generated="true" class="error"><?php echo form_error('room_type_name'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
                
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">capacity(maximum person can live in room )<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="room_capacity" id="room_capacity" value="<?php if(set_value('room_capacity')): echo set_value('room_capacity'); else: echo stripslashes($EDITDATA['room_capacity']);endif; ?>" class="form-control number required" placeholder="capacity">
                    <?php if(form_error('room_capacity')): ?>
                    <p for="room_capacity" generated="true" class="error"><?php echo form_error('room_capacity'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
                
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Facility<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('facility_id')): $facilityid	=	set_value('facility_id'); elseif($FACILITYIDS): $facilityid	=	$FACILITYIDS; else: $facilityid	=	array(); endif; ?>
                    <select name="facility_id[]" id="facility_id" multiple="multiple" class="form-control required">
                      <option value="">Select facility </option>
                      <?php if($FACILITYDATA <> ""): foreach($FACILITYDATA as $FACILITYINFO): ?>
                      <option value="<?php echo $FACILITYINFO['encrypt_id']; ?>" <?php if(in_array($FACILITYINFO['encrypt_id'],$facilityid)): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($FACILITYINFO['facility_name']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('facility_id[]')): ?>
                    <p for="facility_id" generated="true" class="error"><?php echo form_error('facility_id[]'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
                
                    <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Monthly Fee<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="room_fee_monthly" id="room_fee_monthly" value="<?php if(set_value('room_fee_monthly')): echo set_value('room_fee_monthly'); else: echo stripslashes($EDITDATA['room_fee_monthly']);endif; ?>" class="form-control number required" placeholder="Monthly Fee in  &#8377;">
                    <?php if(form_error('room_fee_monthly')): ?>
                    <p for="room_fee_monthly" generated="true" class="error"><?php echo form_error('room_fee_monthly'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Yearly Fee<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="room_fee_yearly" id="room_fee_yearly" value="<?php if(set_value('room_fee_yearly')): echo set_value('room_fee_yearly'); else: echo stripslashes($EDITDATA['room_fee_yearly']);endif; ?>" class="form-control number required" placeholder="Yearly Fee in &#8377;">
                    <?php if(form_error('room_fee_yearly')): ?>
                    <p for="room_fee_yearly" generated="true" class="error"><?php echo form_error('room_fee_yearly'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
         
          
            
            
            
           
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('roomtypeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>

