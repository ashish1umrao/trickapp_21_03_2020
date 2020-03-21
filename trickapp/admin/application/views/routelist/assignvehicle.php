<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('routeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      route details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    assign vehicle details</li>
  <li class="pull-right"><a href="<?php echo correctLink('routeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          assign vehicle details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Route name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="hidden" name="route_id" id="route_id" value="<?php echo stripslashes($ROUTEDATA['encrypt_id']); ?>" />
                  <input type="text" name="route_name" id="route_name" value="<?php echo stripslashes($ROUTEDATA['route_name']); ?>" class="form-control" placeholder="Route name" readonly="readonly">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Vehicle<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('vehicle_id')): $vehicleid	=	set_value('vehicle_id'); elseif($EDITDATA['vehicle_id']): $vehicleid	=	stripslashes($EDITDATA['vehicle_id']); else: $vehicleid	=	''; endif; ?>
                  <select name="vehicle_id" id="vehicle_id" class="form-control required">
                  	<option value="">Select vehicle</option>
                    <?php if($VEHICLEDATA <> ""): foreach($VEHICLEDATA as $VEHICLEINFO): ?>
                    	<option value="<?php echo $VEHICLEINFO['encrypt_id']; ?>" <?php if($VEHICLEINFO['encrypt_id'] == $vehicleid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($VEHICLEINFO['vehicle_type_name'].' ('.$VEHICLEINFO['vehicle_no'].')'); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('vehicle_id')): ?>
                  <p for="vehicle_id" generated="true" class="error"><?php echo form_error('vehicle_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Driver<span class="required">*</span></label>
                <div class="col-lg-6">
        
                  <?php if(set_value('driver_id')): $driverid	=	set_value('driver_id'); elseif($EDITDATA['driver_id']): $driverid	=	stripslashes($EDITDATA['driver_id']); else: $driverid	=	''; endif; ?>
                  <select name="driver_id" id="driver_id" class="form-control required">
                  	<option value="">Select driver</option>
                    <?php if($DRIVERDATA <> ""): foreach($DRIVERDATA as $DRIVERINFO): ?>
                    	<option value="<?php echo $DRIVERINFO['encrypt_id']; ?>" <?php if($DRIVERINFO['encrypt_id'] == $driverid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($DRIVERINFO['user_f_name'].' '.$DRIVERINFO['user_m_name'].' '.$DRIVERINFO['user_l_name'].' ('.$DRIVERINFO['user_email'].')'); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('driver_id')): ?>
                  <p for="driver_id" generated="true" class="error"><?php echo form_error('driver_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Conductor<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('conductor_id')): $conductorid	=	set_value('conductor_id'); elseif($EDITDATA['conductor_id']): $conductorid	=	stripslashes($EDITDATA['conductor_id']); else: $conductorid	=	''; endif; ?>
                  <select name="conductor_id" id="conductor_id" class="form-control required">
                  	<option value="">Select conductor</option>
                    <?php if($CONDUCTORDATA <> ""): foreach($CONDUCTORDATA as $CONDUCTORINFO): ?>
                    	<option value="<?php echo $CONDUCTORINFO['encrypt_id']; ?>" <?php if($CONDUCTORINFO['encrypt_id'] == $conductorid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CONDUCTORINFO['user_f_name'].' '.$CONDUCTORINFO['user_m_name'].' '.$CONDUCTORINFO['user_l_name'].' ('.$CONDUCTORINFO['user_email'].')'); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('conductor_id')): ?>
                  <p for="conductor_id" generated="true" class="error"><?php echo form_error('conductor_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Attendant<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('attendant_id')): $attendantid	=	set_value('attendant_id'); elseif($EDITDATA['attendant_id']): $attendantid	=	stripslashes($EDITDATA['attendant_id']); else: $attendantid	=	''; endif; ?>
                  <select name="attendant_id" id="attendant_id" class="form-control required">
                  	<option value="">Select attendant</option>
                    <?php if($ATTENDANTDATA <> ""): foreach($ATTENDANTDATA as $ATTENDANTINFO): ?>
                    	<option value="<?php echo $ATTENDANTINFO['encrypt_id']; ?>" <?php if($ATTENDANTINFO['encrypt_id'] == $attendantid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($ATTENDANTINFO['user_f_name'].' '.$ATTENDANTINFO['user_m_name'].' '.$ATTENDANTINFO['user_l_name'].' ('.$ATTENDANTINFO['user_email'].') - '.$ATTENDANTINFO['user_type']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('attendant_id')): ?>
                  <p for="attendant_id" generated="true" class="error"><?php echo form_error('attendant_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('routeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>