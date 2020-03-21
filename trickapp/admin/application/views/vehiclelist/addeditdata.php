<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('vehicleListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      vehicle details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    vehicle details</li>
  <li class="pull-right"><a href="<?php echo correctLink('vehicleListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          vehicle details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Vehicle no<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="vehicle_no" id="vehicle_no" value="<?php if(set_value('vehicle_no')): echo set_value('vehicle_no'); else: echo stripslashes($EDITDATA['vehicle_no']); endif; ?>" class="form-control required" placeholder="Vehicle no">
                  <?php if(form_error('vehicle_no')): ?>
                  <p for="vehicle_no" generated="true" class="error"><?php echo form_error('vehicle_no'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Vehicle type<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('vehicle_type')): $vehicletype	=	set_value('vehicle_type'); elseif($EDITDATA['vehicle_type']): $vehicletype	=	stripslashes($EDITDATA['vehicle_type']); else: $vehicletype	=	''; endif; ?>
                  <select name="vehicle_type" id="vehicle_type" class="form-control required">
                  	<option value="">Select Vehicle type</option>
                    <?php if($VEHICLETYPEDATA <> ""): foreach($VEHICLETYPEDATA as $VEHICLETYPEINFO): ?>
                    	<option value="<?php echo $VEHICLETYPEINFO['encrypt_id']; ?>" <?php if($VEHICLETYPEINFO['encrypt_id'] == $vehicletype): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($VEHICLETYPEINFO['vehicle_type_name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('vehicle_type')): ?>
                  <p for="vehicle_type" generated="true" class="error"><?php echo form_error('vehicle_type'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">No of seat<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="no_of_seat" id="no_of_seat" value="<?php if(set_value('no_of_seat')): echo set_value('no_of_seat'); else: echo stripslashes($EDITDATA['no_of_seat']); endif; ?>" class="form-control required" placeholder="No of seat">
                  <?php if(form_error('no_of_seat')): ?>
                  <p for="no_of_seat" generated="true" class="error"><?php echo form_error('no_of_seat'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Maximum set extend<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="max_seat_extend" id="max_seat_extend" value="<?php if(set_value('max_seat_extend')): echo set_value('max_seat_extend'); else: echo stripslashes($EDITDATA['max_seat_extend']); endif; ?>" class="form-control required" placeholder="Maximum set extend">
                  <?php if(form_error('max_seat_extend')): ?>
                  <p for="max_seat_extend" generated="true" class="error"><?php echo form_error('max_seat_extend'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('vehicleListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>