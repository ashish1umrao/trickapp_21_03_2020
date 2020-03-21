<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/index">Profile details</a></li>
  <li class="active">Edit profile details</li>
  <li class="pull-right"><a href="{FULL_SITE_URL}{CURRENT_CLASS}/index" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> 
        <span class="tools pull-left"> Edit profile details </span> 
        <span class="tools pull-right"></span>
        </header>
          
      
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$profileuserdata['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <div class="form-group">
              <label class="col-lg-3 control-label">First Name<span class="required">*</span></label>
              <div class="col-lg-6">
                    <input type="text" name="user_f_name" id="user_f_name" value="<?php if(set_value('user_f_name')): echo set_value('user_f_name'); else: echo stripslashes($profileuserdata['user_f_name']);endif; ?>" class="form-control required" placeholder="First name">
                    <?php if(form_error('user_f_name')): ?>
                    <p for="user_f_name" generated="true" class="error"><?php echo form_error('user_f_name'); ?></p>
                    <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Middle Name</label>
              <div class="col-lg-6">
                    <input type="text" name="user_m_name" id="user_m_name" value="<?php if(set_value('user_m_name')): echo set_value('user_m_name'); else: echo stripslashes($profileuserdata['user_m_name']);endif; ?>" class="form-control " placeholder="Middle name">
                    <?php if(form_error('user_m_name')): ?>
                    <p for="user_m_name" generated="true" class="error"><?php echo form_error('user_m_name'); ?></p>
                    <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Last Name</label>
              <div class="col-lg-6">
                    <input type="text" name="user_l_name" id="user_m_name" value="<?php if(set_value('user_l_name')): echo set_value('user_l_name'); else: echo stripslashes($profileuserdata['user_l_name']);endif; ?>" class="form-control " placeholder="Last name">
                    <?php if(form_error('user_l_name')): ?>
                    <p for="user_l_name" generated="true" class="error"><?php echo form_error('user_l_name'); ?></p>
                    <?php endif; ?>
              </div>
            </div>
            
            
            
            
            
            <div class="form-group">
              <label class="col-lg-3 control-label">New password</label>
              <div class="col-lg-6">
                  <input type="password" name="new_password" id="new_password"value="<?php if(set_value('new_password')): echo set_value('new_password'); endif; ?>" class="form-control" placeholder="New password">
                  <?php if(form_error('new_password')): ?>
                  <p for="new_password" generated="true" class="error"><?php echo form_error('new_password'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Confirm password</label>
              <div class="col-lg-6">
                <input type="password" name="conf_password" id="conf_password"value="<?php if(set_value('conf_password')): echo set_value('conf_password'); endif; ?>" class="form-control" placeholder="Confirm password">
                  <?php if(form_error('conf_password')): ?>
                  <p for="conf_password" generated="true" class="error"><?php echo form_error('conf_password'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
           
            <div class="form-group">
              <label class="col-lg-3 control-label">Address<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="user_c_address" id="user_address"value="<?php if(set_value('user_c_address')): echo set_value('user_c_address'); else: echo stripslashes($profileuserdata['user_c_address']);endif; ?>" class="form-control required" placeholder="Address">
                  <?php if(form_error('user_c_address')):  ?> 
                  <p for="user_c_address" generated="true" class="error"><?php echo form_error('user_address'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Locality<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="user_c_locality" id="admin_locality"value="<?php if(set_value('user_c_locality')): echo set_value('user_c_locality'); else: echo stripslashes($profileuserdata['user_c_locality']);endif; ?>" class="form-control required" placeholder="Locality">
                  <?php if(form_error('user_c_locality')): ?>
                  <p for="user_c_locality" generated="true" class="error"><?php echo form_error('user_c_locality'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">City<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="user_c_city" id="admin_city"value="<?php if(set_value('user_c_city')): echo set_value('user_c_city'); else: echo stripslashes($profileuserdata['user_c_city']);endif; ?>" class="form-control required" placeholder="City">
                  <?php if(form_error('admin_city')): ?>
                  <p for="user_c_city" generated="true" class="error"><?php echo form_error('user_c_city'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">State<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="user_c_state" id="admin_state"value="<?php if(set_value('user_c_state')): echo set_value('user_c_state'); else: echo stripslashes($profileuserdata['user_c_state']);endif; ?>" class="form-control required" placeholder="State">
                  <?php if(form_error('user_c_state')): ?>
                  <p for="user_c_state" generated="true" class="error"><?php echo form_error('user_c_state'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Zipcode<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="user_c_zipcode" id="admin_zipcode"value="<?php if(set_value('user_c_zipcode')): echo set_value('user_c_zipcode'); else: echo stripslashes($profileuserdata['user_c_zipcode']);endif; ?>" class="form-control required" placeholder="Zipcode">
                  <?php if(form_error('user_c_zipcode')): ?>
                  <p for="user_c_zipcode" generated="true" class="error"><?php echo form_error('user_c_zipcode'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="{FULL_SITE_URL}{CURRENT_CLASS}/index" class="btn btn-default">Cancel</a>
                <span class="tools pull-right">
                <span class="btn btn-outline btn-default">Note
                    :- <strong><span style="color:#FF0000;">*</span> Indicates
                    required fields</strong> </span>
                </span>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
