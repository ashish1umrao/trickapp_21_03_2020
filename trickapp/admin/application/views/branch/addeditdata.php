<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('branchAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      branch details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    branch details</li>
  <li class="pull-right"><a href="<?php echo correctLink('branchAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          branch details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Branch code<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('branch_code')): $branch_code = set_value('branch_code'); elseif($EDITDATA['branch_code']): $branch_code = stripslashes($EDITDATA['branch_code']); else: $branch_code = ''; endif; ?>
                  <input type="text" name="branch_code" id="branch_code" value="<?php echo $branch_code; ?>" class="form-control required" placeholder="Branch code">
                  <?php if(form_error('branch_code')): ?>
                  <p for="branch_code" generated="true" class="error"><?php echo form_error('branch_code'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Name<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="admin_name" id="admin_name"value="<?php if(set_value('admin_name')): echo set_value('admin_name'); else: echo stripslashes($EDITDATA['admin_name']);endif; ?>" class="form-control required" placeholder="Name">
                  <?php if(form_error('admin_name')): ?>
                  <p for="admin_name" generated="true" class="error"><?php echo form_error('admin_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Display name<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="admin_display_name" id="admin_display_name"value="<?php if(set_value('admin_display_name')): echo set_value('admin_display_name'); else: echo stripslashes($EDITDATA['admin_display_name']);endif; ?>" class="form-control required" placeholder="Display name">
                  <?php if(form_error('admin_display_name')): ?>
                  <p for="admin_display_name" generated="true" class="error"><?php echo form_error('admin_display_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Slug url<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="admin_slug" id="admin_slug"value="<?php if(set_value('admin_slug')): echo set_value('admin_slug'); else: echo stripslashes($EDITDATA['admin_slug']);endif; ?>" class="form-control required" placeholder="Slug url">
                  <?php if(form_error('admin_slug')): ?>
                  <p for="admin_slug" generated="true" class="error"><?php echo form_error('admin_slug'); ?></p>
                  <?php endif;  ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Email Id<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="admin_email_id" id="admin_email_id"value="<?php if(set_value('admin_email_id')): echo set_value('admin_email_id'); else: echo stripslashes($EDITDATA['admin_email_id']);endif; ?>" class="form-control required email" placeholder="Email Id">
                  <?php if(form_error('admin_email_id')): ?>
                  <p for="admin_email_id" generated="true" class="error"><?php echo form_error('admin_email_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php if($EDITDATA <> ""): ?>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">New password</label>
                <div class="col-lg-8">
                  <input type="password" name="new_password" id="new_password"value="<?php if(set_value('new_password')): echo set_value('new_password'); endif; ?>" class="form-control" placeholder="New password">
                  <?php if(form_error('new_password')): ?>
                  <p for="new_password" generated="true" class="error"><?php echo form_error('new_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Confirm password</label>
                <div class="col-lg-8">
                  <input type="password" name="conf_password" id="conf_password"value="<?php if(set_value('conf_password')): echo set_value('conf_password'); endif; ?>" class="form-control" placeholder="Confirm password">
                  <?php if(form_error('conf_password')): ?>
                  <p for="conf_password" generated="true" class="error"><?php echo form_error('conf_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php else: ?>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Password<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="password" name="new_password" id="new_password"value="<?php if(set_value('new_password')): echo set_value('new_password'); endif; ?>" class="form-control required" placeholder="Password">
                  <?php if(form_error('new_password')): ?>
                  <p for="new_password" generated="true" class="error"><?php echo form_error('new_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Confirm password<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="password" name="conf_password" id="conf_password"value="<?php if(set_value('conf_password')): echo set_value('conf_password'); endif; ?>" class="form-control required" placeholder="Confirm password">
                  <?php if(form_error('conf_password')): ?>
                  <p for="conf_password" generated="true" class="error"><?php echo form_error('conf_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endif; ?>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Mobile number<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="admin_mobile_number" id="admin_mobile_number"value="<?php if(set_value('admin_mobile_number')): echo set_value('admin_mobile_number'); else: echo stripslashes($EDITDATA['admin_mobile_number']);endif; ?>" class="form-control required" placeholder="Mobile number">
                  <?php if(form_error('admin_mobile_number')): ?>
                  <p for="admin_mobile_number" generated="true" class="error"><?php echo form_error('admin_mobile_number'); ?></p>
                  <?php endif; if($b_mobileerror):  ?>
                  <p for="admin_mobile_number" generated="true" class="error"><?php echo $b_mobileerror; ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Address<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="admin_address" id="admin_address"value="<?php if(set_value('admin_address')): echo set_value('admin_address'); else: echo stripslashes($EDITDATA['admin_address']);endif; ?>" class="form-control required" placeholder="Address">
                  <?php if(form_error('admin_address')): ?>
                  <p for="admin_address" generated="true" class="error"><?php echo form_error('admin_address'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Locality<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="admin_locality" id="admin_locality"value="<?php if(set_value('admin_locality')): echo set_value('admin_locality'); else: echo stripslashes($EDITDATA['admin_locality']);endif; ?>" class="form-control required" placeholder="Locality">
                  <?php if(form_error('admin_locality')): ?>
                  <p for="admin_locality" generated="true" class="error"><?php echo form_error('admin_locality'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">City<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="admin_city" id="admin_city"value="<?php if(set_value('admin_city')): echo set_value('admin_city'); else: echo stripslashes($EDITDATA['admin_city']);endif; ?>" class="form-control required" placeholder="City">
                  <?php if(form_error('admin_city')): ?>
                  <p for="admin_city" generated="true" class="error"><?php echo form_error('admin_city'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">State<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="admin_state" id="admin_state"value="<?php if(set_value('admin_state')): echo set_value('admin_state'); else: echo stripslashes($EDITDATA['admin_state']);endif; ?>" class="form-control required" placeholder="State">
                  <?php if(form_error('admin_state')): ?>
                  <p for="admin_state" generated="true" class="error"><?php echo form_error('admin_state'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Zipcode<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="admin_zipcode" id="admin_zipcode"value="<?php if(set_value('admin_zipcode')): echo set_value('admin_zipcode'); else: echo stripslashes($EDITDATA['admin_zipcode']);endif; ?>" class="form-control required" placeholder="Zipcode">
                  <?php if(form_error('admin_zipcode')): ?>
                  <p for="admin_zipcode" generated="true" class="error"><?php echo form_error('admin_zipcode'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            
            <?php if($BOARDDATA <> ""): ?>
            <fieldset>
            <legend>Board details</legend>
             <div class="col-lg-12 boarddetails">
                <?php $boarddetails			=	array(); 
					  if(set_value('board_details')): 
						$boarddata			=	set_value('board_details'); 
						foreach($boarddata as $boardinfo):
							if($boardinfo):
								$boarddatass=	explode('_____',$boardinfo); 
								array_push($boarddetails,$boarddatass[0]);
							endif;
						endforeach;
					  elseif($B_BOARDDATA <> ""): 
					  	$boarddetails		=	$B_BOARDDATA;
					  endif; ?>
                <?php $i=1; foreach($BOARDDATA as $BOARDINFO): ?>
                <div class="col-lg-4">
                  <div class="form-group">
                    <div class="col-lg-12">
                      <input type="checkbox" name="board_details[]" id="board_details<?php echo $i; ?>" value="<?php echo stripslashes($BOARDINFO['encrypt_id'].'_____'.$BOARDINFO['board_name'].'_____'.$BOARDINFO['board_short_name']); ?>" <?php if(in_array($BOARDINFO['encrypt_id'],$boarddetails)): echo 'checked="checked"'; endif; ?> class="required">
                      <label for="same_details<?php echo $i; ?>"><?php echo stripslashes($BOARDINFO['board_name']); ?></label>
                    </div>
                  </div>
                </div>
                <?php $i++; endforeach; ?>
                <?php if(form_error('board_details')): ?>
                 <p for="board_details1" generated="true" class="error"><?php echo form_error('board_details'); ?></p>
                <?php endif; ?>
              </div>
            </fieldset>
            <?php endif; ?>
            
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('branchAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>