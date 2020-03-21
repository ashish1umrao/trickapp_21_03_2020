
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('feefineListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      fee fine</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    fee fine</li>
  <li class="pull-right"><a href="<?php echo correctLink('feefineListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
               
          fee fine</span> </header>
       
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
              <div class="col-lg-6">
                <label class="col-lg-4 control-label">Fee Head<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php    if(set_value('fee_head_id')): $feeheadid	=	set_value('fee_head_id'); elseif($EDITDATA['fee_head_id']): $feeheadid	=	stripslashes($EDITDATA['fee_head_id']); else: $feeheadid	=	''; endif; ?>
                  <select name="fee_head_id" id="fee_head_id" class="form-control required">
                  	<option value="">Select Fee Head</option>
                    <?php  if($FEEHEADINGLIST <> ""): foreach($FEEHEADINGLIST as $HEADINFO): ?>
                    	<option value="<?php echo $HEADINFO->encrypt_id; ?>" <?php if($HEADINFO->encrypt_id == $feeheadid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($HEADINFO->heading_type); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('fee_head_id')): ?>
                  <p for="fee_head_id" generated="true" class="error"><?php echo form_error('fee_head_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-lg-6">
                <label class="col-lg-4 control-label">Fine Type<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('fee_fine_type')): $feefineid	=	set_value('fee_fine_type'); elseif($EDITDATA['fee_fine_type']): $feefineid	=	stripslashes($EDITDATA['fee_fine_type']); else: $feefineid	=	''; endif; ?>
                  <select name="fee_fine_type" id="fee_head_id" class="form-control required">
                  	<option value="">Select Fine Type</option>
                   
                        <option value="Percentage" <?php if('Percentage' == $feefineid): echo 'selected="selected"'; endif; ?>>Percentage</option>
                        <option value="Fixed" <?php if('Fixed' == $feeheadid): echo 'selected="selected"'; endif; ?>>Fixed</option>
                  
                  </select>
                  <?php if(form_error('fee_fine_type')): ?>
                  <p for="fee_fine_type" generated="true" class="error"><?php echo form_error('fee_fine_type'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              </div>
              <div class="form-group">
             <div class="col-lg-6">
                <label class="col-lg-4 control-label">Fine Amount (in % or &#8377 according to fine type)<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="fee_fine_value" id="fee_head_name" value="<?php if(set_value('fee_fine_value')): echo set_value('fee_fine_value'); else: echo stripslashes($EDITDATA['fee_fine_value']);endif; ?>" class="form-control required number" placeholder="Fine Amount">
                  <?php if(form_error('fee_fine_value')): ?>
                  <p for="fee_fine_value" generated="true" class="error"><?php echo form_error('fee_fine_value'); ?></p>
              
                  <?php endif; ?>
                   
                </div>
                </div>
                  
                 <div class="col-lg-6">
                <label class="col-lg-4 control-label">Fine  Every Day   &#8377<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="fee_fine_every_days" id="fee_fine_every_days" value="<?php if(set_value('fee_fine_value')): echo set_value('fee_fine_every_days'); else: echo stripslashes($EDITDATA['fee_fine_every_days']);endif; ?>" class="form-control required number" placeholder="Fine Every Day">
                  <?php if(form_error('fee_fine_every_days')): ?>
                  <p for="fee_fine_every_days" generated="true" class="error"><?php echo form_error('fee_fine_every_days'); ?></p>
              
                  <?php endif; ?>
                   
                </div>
                </div>
              </div>
           
              <div class="form-group">
               <div class="col-lg-6">
                <label class="col-lg-4 control-label">Fine Upto Days<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="fee_fine_upto_days" id="fee_fine_upto_days" value="<?php if(set_value('fee_fine_upto_days')): echo set_value('fee_fine_upto_days'); else: echo stripslashes($EDITDATA['fee_fine_upto_days']);endif; ?>" class="form-control required number" placeholder="Fine Upto days">
                  <?php if(form_error('fee_fine_upto_days')): ?>
                  <p for="fee_fine_upto_days" generated="true" class="error"><?php echo form_error('fee_fine_upto_days'); ?></p>
              
                  <?php endif; ?>
                   
                </div>
                </div>
           
              </div>
             
             
              
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('feefineListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>