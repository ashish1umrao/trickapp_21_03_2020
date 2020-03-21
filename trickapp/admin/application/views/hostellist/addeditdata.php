<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('hostelAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      hostel details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    hostel details</li>
  <li class="pull-right"><a href="<?php echo correctLink('hostelAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          hostel details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <div class="form-group">
              <label class="col-lg-3 control-label">hostel Name<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="hostel_name" id="hostel_name"value="<?php if(set_value('hostel_name')): echo set_value('hostel_name'); else: echo stripslashes($EDITDATA['hostel_name']);endif; ?>" class="form-control required" placeholder="Name">
                <?php if(form_error('hostel_name')): ?>
                <p for="hostel_name" generated="true" class="error"><?php echo form_error('hostel_name'); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Display name<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="hostel_display_name" id="hostel_display_name"value="<?php if(set_value('hostel_display_name')): echo set_value('hostel_display_name'); else: echo stripslashes($EDITDATA['hostel_display_name']);endif; ?>" class="form-control required" placeholder="Display name">
                <?php if(form_error('hostel_display_name')): ?>
                <p for="hostel_display_name" generated="true" class="error"><?php echo form_error('hostel_display_name'); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Slug url<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="hostel_slug" id="hostel_slug"value="<?php if(set_value('hostel_slug')): echo set_value('hostel_slug'); else: echo stripslashes($EDITDATA['hostel_slug']);endif; ?>" class="form-control required" placeholder="Slug url">
                <?php if(form_error('hostel_slug')): ?>
                <p for="hostel_slug" generated="true" class="error"><?php echo form_error('hostel_slug'); ?></p>
                <?php endif; ?>
              </div>
            </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">hostel Warden<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('hostel_warden_id')): $wardenid	=	set_value('hostel_warden_id'); elseif($EDITDATA['hostel_warden_id']): $wardenid	=	stripslashes($EDITDATA['hostel_warden_id']); else: $wardenid	=	''; endif; ?>
                  <select name="hostel_warden_id" id="hostel_warden_id" class="form-control required">
                  	<option value="">Select warden</option>
                    <?php if($WARDENDATA <> ""): foreach($WARDENDATA as $WARDENINFO): ?>
                    	<option value="<?php echo $WARDENINFO['encrypt_id']; ?>" <?php if($WARDENINFO['encrypt_id'] == $wardenid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($WARDENINFO['user_f_name'].' '.$WARDENINFO['user_m_name'].' '.$WARDENINFO['user_l_name'].' ('.$WARDENINFO['user_email'].') - '.$WARDENINFO['user_type']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('hostel_warden_id')): ?>
                  <p for="hostel_warden_id" generated="true" class="error"><?php echo form_error('hostel_warden_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
               
                <div class="form-group">
                  <label class="col-lg-3 control-label">hostel Type<span class="required">*</span></label>
                    <div class="col-lg-6">
                    <?php if(set_value('hostel_type')): $hosteltype	=	set_value('hostel_type'); elseif($EDITDATA['hostel_type']): $hosteltype	=	$EDITDATA['hostel_type']; else: $hosteltype	=	''; endif; ?>
                    <select name="hostel_type" id="hostel_type" class="form-control required">
                      <option value="">Select Type</option>
                      <?php if($TYPEDATA <> ""): foreach($TYPEDATA as $TYPEINFO): ?>
                      <option value="<?php echo $TYPEINFO['hostel_type']; ?>" <?php if($TYPEINFO['hostel_type'] == $hosteltype): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($TYPEINFO['hostel_type']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('hostel_type')): ?>
                    <p for="hostel_type" generated="true" class="error"><?php echo form_error('hostel_type'); ?></p>
                    <?php endif; ?>
       </div>
               
              </div>
             <div class="form-group">
              <label class="col-lg-3 control-label">Total Rooms<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="hostel_total_room" id="hostel_address"value="<?php if(set_value('hostel_total_room')): echo set_value('hostel_total_room'); else: echo stripslashes($EDITDATA['hostel_total_room']);endif; ?>" class="form-control number required " placeholder="Total Rooms">
                <?php if(form_error('hostel_total_room')): ?>
                <p for="hostel_total_room" generated="true" class="error"><?php echo form_error('hostel_total_room'); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Discription</label>
              <div class="col-lg-6">
                <input type="text" name="hostel_description" id="hostel_description"value="<?php if(set_value('hostel_description')): echo set_value('hostel_description'); else: echo stripslashes($EDITDATA['hostel_description']);endif; ?>" class="form-control " placeholder="hostel Description">
                <?php if(form_error('hostel_description')): ?>
                <p for="hostel_description" generated="true" class="error"><?php echo form_error('hostel_description'); ?></p>
                <?php endif; ?>
              </div>
            </div>
           
            <div class="form-group">
              <label class="col-lg-3 control-label">Mobile number</label>
              <div class="col-lg-6">
                <input type="text" name="hostel_mobile_number" id="hostel_mobile_number"value="<?php if(set_value('hostel_mobile_number')): echo set_value('hostel_mobile_number'); else: echo stripslashes($EDITDATA['hostel_mobile_number']);endif; ?>" class="form-control " placeholder="Mobile number">
                <?php if(form_error('hostel_mobile_number')): ?>
                <p for="hostel_mobile_number" generated="true" class="error"><?php echo form_error('hostel_mobile_number'); ?></p>
                <?php endif; if($s_mobileerror):  ?>
                <p for="hostel_mobile_number" generated="true" class="error"><?php echo $s_mobileerror; ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Address</label>
              <div class="col-lg-6">
                <input type="text" name="hostel_address" id="hostel_address"value="<?php if(set_value('hostel_address')): echo set_value('hostel_address'); else: echo stripslashes($EDITDATA['hostel_address']);endif; ?>" class="form-control " placeholder="Address">
                <?php if(form_error('hostel_address')): ?>
                <p for="hostel_address" generated="true" class="error"><?php echo form_error('hostel_address'); ?></p>
                <?php endif; ?>
              </div>
            </div>
            
           
           
          
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('feeconcessionListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
