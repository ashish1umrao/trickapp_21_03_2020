<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('schoolAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      school details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    school details</li>
  <li class="pull-right"><a href="<?php echo correctLink('schoolAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          school details</span> </header>
        <div class="panel-body">
          <form id="currentPageSchoolBranchForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$S_EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <fieldset>
            <legend>School details</legend>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Name<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="s_admin_name" id="s_admin_name" value="<?php if(set_value('s_admin_name')): echo set_value('s_admin_name'); else: echo stripslashes($S_EDITDATA['admin_name']);endif; ?>" class="form-control required" placeholder="Name">
                  <?php if(form_error('s_admin_name')): ?>
                  <p for="s_admin_name" generated="true" class="error"><?php echo form_error('s_admin_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Display name<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="s_admin_display_name" id="s_admin_display_name" value="<?php if(set_value('s_admin_display_name')): echo set_value('s_admin_display_name'); else: echo stripslashes($S_EDITDATA['admin_display_name']);endif; ?>" class="form-control required" placeholder="Display name">
                  <?php if(form_error('s_admin_display_name')): ?>
                  <p for="s_admin_display_name" generated="true" class="error"><?php echo form_error('s_admin_display_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Slug url<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="s_admin_slug" id="s_admin_slug" value="<?php if(set_value('s_admin_slug')): echo set_value('s_admin_slug'); else: echo stripslashes($S_EDITDATA['admin_slug']);endif; ?>" class="form-control required" placeholder="Slug url">
                  <?php if(form_error('s_admin_slug')): ?>
                  <p for="s_admin_slug" generated="true" class="error"><?php echo form_error('s_admin_slug'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Email Id<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="s_admin_email_id" id="s_admin_email_id" value="<?php if(set_value('s_admin_email_id')): echo set_value('s_admin_email_id'); else: echo stripslashes($S_EDITDATA['admin_email_id']);endif; ?>" class="form-control required email" placeholder="Email Id">
                  <?php if(form_error('s_admin_email_id')): ?>
                  <p for="s_admin_email_id" generated="true" class="error"><?php echo form_error('s_admin_email_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php if($S_EDITDATA <> ""): ?>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">New password</label>
                <div class="col-lg-8">
                  <input type="password" name="s_new_password" id="s_new_password" value="<?php if(set_value('s_new_password')): echo set_value('s_new_password'); endif; ?>" class="form-control" placeholder="New password">
                  <?php if(form_error('s_new_password')): ?>
                  <p for="s_new_password" generated="true" class="error"><?php echo form_error('s_new_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Confirm password</label>
                <div class="col-lg-8">
                  <input type="password" name="s_conf_password" id="s_conf_password" value="<?php if(set_value('s_conf_password')): echo set_value('s_conf_password'); endif; ?>" class="form-control" placeholder="Confirm password">
                  <?php if(form_error('s_conf_password')): ?>
                  <p for="s_conf_password" generated="true" class="error"><?php echo form_error('s_conf_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php else: ?>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Password<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="password" name="s_new_password" id="s_new_password" value="<?php if(set_value('s_new_password')): echo set_value('s_new_password'); endif; ?>" class="form-control required" placeholder="Password">
                  <?php if(form_error('s_new_password')): ?>
                  <p for="s_new_password" generated="true" class="error"><?php echo form_error('s_new_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Confirm password<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="password" name="s_conf_password" id="s_conf_password" value="<?php if(set_value('s_conf_password')): echo set_value('s_conf_password'); endif; ?>" class="form-control required" placeholder="Confirm password">
                  <?php if(form_error('s_conf_password')): ?>
                  <p for="s_conf_password" generated="true" class="error"><?php echo form_error('s_conf_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endif; ?>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Mobile number<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="s_admin_mobile_number" id="s_admin_mobile_number" value="<?php if(set_value('s_admin_mobile_number')): echo set_value('s_admin_mobile_number'); else: echo stripslashes($S_EDITDATA['admin_mobile_number']);endif; ?>" class="form-control required" placeholder="Mobile number">
                  <?php if(form_error('s_admin_mobile_number')): ?>
                  <p for="s_admin_mobile_number" generated="true" class="error"><?php echo form_error('s_admin_mobile_number'); ?></p>
                  <?php endif; if($s_mobileerror):  ?>
                  <p for="s_admin_mobile_number" generated="true" class="error"><?php echo $s_mobileerror; ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Address<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="s_admin_address" id="s_admin_address" value="<?php if(set_value('s_admin_address')): echo set_value('s_admin_address'); else: echo stripslashes($S_EDITDATA['admin_address']);endif; ?>" class="form-control required" placeholder="Address">
                  <?php if(form_error('s_admin_address')): ?>
                  <p for="s_admin_address" generated="true" class="error"><?php echo form_error('s_admin_address'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Locality<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="s_admin_locality" id="s_admin_locality" value="<?php if(set_value('s_admin_locality')): echo set_value('s_admin_locality'); else: echo stripslashes($S_EDITDATA['admin_locality']);endif; ?>" class="form-control required" placeholder="Locality">
                  <?php if(form_error('s_admin_locality')): ?>
                  <p for="s_admin_locality" generated="true" class="error"><?php echo form_error('s_admin_locality'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">City<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="s_admin_city" id="s_admin_city" value="<?php if(set_value('s_admin_city')): echo set_value('s_admin_city'); else: echo stripslashes($S_EDITDATA['admin_city']);endif; ?>" class="form-control required" placeholder="City">
                  <?php if(form_error('s_admin_city')): ?>
                  <p for="s_admin_city" generated="true" class="error"><?php echo form_error('s_admin_city'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">State<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="s_admin_state" id="s_admin_state" value="<?php if(set_value('s_admin_state')): echo set_value('s_admin_state'); else: echo stripslashes($S_EDITDATA['admin_state']);endif; ?>" class="form-control required" placeholder="State">
                  <?php if(form_error('s_admin_state')): ?>
                  <p for="s_admin_state" generated="true" class="error"><?php echo form_error('s_admin_state'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Zipcode<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="s_admin_zipcode" id="s_admin_zipcode" value="<?php if(set_value('s_admin_zipcode')): echo set_value('s_admin_zipcode'); else: echo stripslashes($S_EDITDATA['admin_zipcode']);endif; ?>" class="form-control required" placeholder="Zipcode">
                  <?php if(form_error('s_admin_zipcode')): ?>
                  <p for="s_admin_zipcode" generated="true" class="error"><?php echo form_error('s_admin_zipcode'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">School logo<span class="required">*</span></label>
                  <div class="col-lg-8"> 
                    <span style="color:#FF0000;" id="blinker">Image must be width:250px
                    and height:250px or expect ratio.</span><br clear="all" />
                    <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                    <input type="text" id="uploadimage0" name="uploadimage0" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($S_EDITDATA['admin_image']); endif; ?>" class="browseimageclass required" />
                    <br clear="all">
                    <?php if(form_error('uploadimage0')): ?>
                    <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label>
                    <?php endif; ?>
                    <label id="uploadstatus0" class="error"></label>
                    <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                    <span id="uploadphoto0" style="float:left;">
                    <?php if(set_value('uploadimage0')):?>
                    <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php elseif($S_EDITDATA['admin_image']):?>
                    <img src="<?php echo stripslashes($S_EDITDATA['admin_image'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($S_EDITDATA['admin_image'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php endif; ?>
                    </span> </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">School logo for APP<span class="required">*</span></label>
                  <div class="col-lg-8"> 
                    <span style="color:#FF0000;" id="blinker1">Image must be width:192px
                    and height:192px or expect ratio.</span><br clear="all" />
                    <span style="display:inline-block;" id="uploadIds1"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                    <input type="text" id="uploadimage1" name="uploadimage1" value="<?php if(set_value('uploadimage1')): echo set_value('uploadimage1'); else: echo stripslashes($S_EDITDATA['admin_image_app']); endif; ?>" class="browseimageclass required" />
                    <br clear="all">
                    <?php if(form_error('uploadimage1')): ?>
                    <label for="uploadimage1" generated="true" class="error"><?php echo form_error('uploadimage1'); ?></label>
                    <?php endif; ?>
                    <label id="uploadstatus1" class="error"></label>
                    <div id="uploadloader1" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                    <span id="uploadphoto1" style="float:left;">
                    <?php if(set_value('uploadimage1')):?>
                    <img src="<?php echo stripslashes(set_value('uploadimage1'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage1'))?>','1');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php elseif($S_EDITDATA['admin_image_app']):?>
                    <img src="<?php echo stripslashes($S_EDITDATA['admin_image_app'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($S_EDITDATA['admin_image_app'])?>','1');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php endif; ?>
                    </span> </div>
                </div>
              </div>
            </div>
            </fieldset>
            <fieldset>
            <legend>Branch details</legend>
            <input type="hidden" name="user_id" id="user_id" value="<?=$B_EDITDATA['encrypt_id']?>"/>
            <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">&nbsp;</label>
                    <div class="col-lg-8">
                      <?php if(set_value('same_details')): $samedetails	=	set_value('same_details'); else: $samedetails	=	''; endif; ?>
                      <input type="checkbox" name="same_details" id="same_details" value="Y" <?php if($samedetails == 'Y'): echo 'checked="checked"'; endif; ?>>
                      <label for="same_details">Same as above details</label>
                    </div>
                  </div>
                </div>
              </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Branch code<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('branch_code')): $branch_code = set_value('branch_code'); elseif($B_EDITDATA['branch_code']): $branch_code = stripslashes($B_EDITDATA['branch_code']); else: $branch_code = ''; endif; ?>
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
                  <?php if(set_value('b_admin_name')): $b_admin_name  =  set_value('b_admin_name'); elseif($B_EDITDATA['admin_name']): $b_admin_name  =  stripslashes($B_EDITDATA['admin_name']); else: $b_admin_name =  ''; endif; ?>
                  <input type="text" name="b_admin_name" id="b_admin_name" value="<?php echo $b_admin_name; ?>" class="form-control required" placeholder="Name">
                  <?php if(form_error('b_admin_name')): ?>
                  <p for="b_admin_name" generated="true" class="error"><?php echo form_error('b_admin_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Display name<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('b_admin_display_name')): $b_admin_display_name = set_value('b_admin_display_name'); elseif($B_EDITDATA['admin_display_name']): $b_admin_display_name = stripslashes($B_EDITDATA['admin_display_name']); else: $b_admin_display_name = ''; endif; ?>
                  <input type="text" name="b_admin_display_name" id="b_admin_display_name" value="<?php echo $b_admin_display_name; ?>" class="form-control required" placeholder="Display name">
                  <?php if(form_error('b_admin_display_name')): ?>
                  <p for="b_admin_display_name" generated="true" class="error"><?php echo form_error('b_admin_display_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Slug url<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('b_admin_slug')): $b_admin_slug = set_value('b_admin_slug'); elseif($B_EDITDATA['admin_slug']): $b_admin_slug = stripslashes($B_EDITDATA['admin_slug']); else: $b_admin_slug = ''; endif; ?>
                  <input type="text" name="b_admin_slug" id="b_admin_slug" value="<?php echo $b_admin_slug; ?>" class="form-control required" placeholder="Slug url">
                  <?php if(form_error('b_admin_slug')): ?>
                  <p for="b_admin_slug" generated="true" class="error"><?php echo form_error('b_admin_slug'); ?></p>
                  <?php endif; if($b_slugerror):  ?>
                  <p for="b_admin_slug" generated="true" class="error"><?php echo $b_slugerror; ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Email Id<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('b_admin_email_id')): $b_admin_email_id = set_value('b_admin_email_id'); elseif($B_EDITDATA['admin_email_id']): $b_admin_email_id = stripslashes($B_EDITDATA['admin_email_id']); else: $b_admin_email_id = ''; endif; ?>
                  <input type="text" name="b_admin_email_id" id="b_admin_email_id" value="<?php echo $b_admin_email_id; ?>" class="form-control required email" placeholder="Email Id">
                  <?php if(form_error('b_admin_email_id')): ?>
                  <p for="b_admin_email_id" generated="true" class="error"><?php echo form_error('b_admin_email_id'); ?></p>
                  <?php endif; if($b_emailerror):  ?>
                  <p for="b_admin_email_id" generated="true" class="error"><?php echo $b_emailerror; ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php if($B_EDITDATA <> ""): ?>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">New password</label>
                <div class="col-lg-8">
                  <input type="password" name="b_new_password" id="b_new_password" value="<?php if(set_value('b_new_password')): echo set_value('b_new_password'); endif; ?>" class="form-control" placeholder="New password">
                  <?php if(form_error('b_new_password')): ?>
                  <p for="b_new_password" generated="true" class="error"><?php echo form_error('b_new_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Confirm password</label>
                <div class="col-lg-8">
                  <input type="password" name="b_conf_password" id="b_conf_password" value="<?php if(set_value('b_conf_password')): echo set_value('b_conf_password'); endif; ?>" class="form-control" placeholder="Confirm password">
                  <?php if(form_error('b_conf_password')): ?>
                  <p for="b_conf_password" generated="true" class="error"><?php echo form_error('b_conf_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php else: ?>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Password<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="password" name="b_new_password" id="b_new_password" value="<?php if(set_value('b_new_password')): echo set_value('b_new_password'); endif; ?>" class="form-control required" placeholder="Password">
                  <?php if(form_error('b_new_password')): ?>
                  <p for="b_new_password" generated="true" class="error"><?php echo form_error('b_new_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Confirm password<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="password" name="b_conf_password" id="b_conf_password" value="<?php if(set_value('b_conf_password')): echo set_value('b_conf_password'); endif; ?>" class="form-control required" placeholder="Confirm password">
                  <?php if(form_error('b_conf_password')): ?>
                  <p for="b_conf_password" generated="true" class="error"><?php echo form_error('b_conf_password'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endif; ?>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Mobile number<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('b_admin_mobile_number')): $b_admin_mobile_number = set_value('b_admin_mobile_number'); elseif($B_EDITDATA['admin_mobile_number']): $b_admin_mobile_number = stripslashes($B_EDITDATA['admin_mobile_number']); else: $b_admin_mobile_number = ''; endif; ?>
                  <input type="text" name="b_admin_mobile_number" id="b_admin_mobile_number" value="<?php echo $b_admin_mobile_number; ?>" class="form-control required" placeholder="Mobile number">
                  <?php if(form_error('b_admin_mobile_number')): ?>
                  <p for="b_admin_mobile_number" generated="true" class="error"><?php echo form_error('b_admin_mobile_number'); ?></p>
                  <?php endif; if($b_mobileerror):  ?>
                  <p for="b_admin_mobile_number" generated="true" class="error"><?php echo $b_mobileerror; ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Address<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('b_admin_address')): $b_admin_address = set_value('b_admin_address'); elseif($B_EDITDATA['admin_address']): $b_admin_address = stripslashes($B_EDITDATA['admin_address']); else: $b_admin_address = ''; endif; ?>
                  <input type="text" name="b_admin_address" id="b_admin_address" value="<?php echo $b_admin_address; ?>" class="form-control required" placeholder="Address">
                  <?php if(form_error('b_admin_address')): ?>
                  <p for="b_admin_address" generated="true" class="error"><?php echo form_error('b_admin_address'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Locality<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('b_admin_locality')): $b_admin_locality = set_value('b_admin_locality'); elseif($B_EDITDATA['admin_locality']): $b_admin_locality = stripslashes($B_EDITDATA['admin_locality']); else: $b_admin_locality = ''; endif; ?>
                  <input type="text" name="b_admin_locality" id="b_admin_locality" value="<?php echo $b_admin_locality; ?>" class="form-control required" placeholder="Locality">
                  <?php if(form_error('b_admin_locality')): ?>
                  <p for="b_admin_locality" generated="true" class="error"><?php echo form_error('b_admin_locality'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">City<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('b_admin_city')): $b_admin_city = set_value('b_admin_city'); elseif($B_EDITDATA['admin_city']): $b_admin_city = stripslashes($B_EDITDATA['admin_city']); else: $b_admin_city = ''; endif; ?>
                  <input type="text" name="b_admin_city" id="b_admin_city" value="<?php echo $b_admin_city; ?>" class="form-control required" placeholder="City">
                  <?php if(form_error('b_admin_city')): ?>
                  <p for="b_admin_city" generated="true" class="error"><?php echo form_error('b_admin_city'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">State<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('b_admin_state')): $b_admin_state = set_value('b_admin_state'); elseif($B_EDITDATA['admin_state']): $b_admin_state = stripslashes($B_EDITDATA['admin_state']); else: $b_admin_state = ''; endif; ?>
                  <input type="text" name="b_admin_state" id="b_admin_state" value="<?php echo $b_admin_state; ?>" class="form-control required" placeholder="State">
                  <?php if(form_error('b_admin_state')): ?>
                  <p for="b_admin_state" generated="true" class="error"><?php echo form_error('b_admin_state'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Zipcode<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('b_admin_zipcode')): $b_admin_zipcode = set_value('b_admin_zipcode'); elseif($B_EDITDATA['admin_zipcode']): $b_admin_zipcode = stripslashes($B_EDITDATA['admin_zipcode']); else: $b_admin_zipcode = ''; endif; ?>
                  <input type="text" name="b_admin_zipcode" id="b_admin_zipcode" value="<?php echo $b_admin_zipcode; ?>" class="form-control required" placeholder="Zipcode">
                  <?php if(form_error('b_admin_zipcode')): ?>
                  <p for="b_admin_zipcode" generated="true" class="error"><?php echo form_error('b_admin_zipcode'); ?></p>
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
            
            </fieldset>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('schoolAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
<script>
$(function(){
	UploadImage('0');
	UploadImage('1');
});
</script>
<script>
$(function(){
if($("#currentPageSchoolBranchForm").length) {
	$("#currentPageSchoolBranchForm").validate({ 
		rules: {
			s_new_password: { minlength: 6, maxlength: 25 },
			s_conf_password: { minlength: 6, equalTo: "#s_new_password" },
	    s_admin_mobile_number:{ minlength:10, maxlength:15, numberandsign:true },
			b_new_password: { minlength: 6, maxlength: 25 },
			b_conf_password: { minlength: 6, equalTo: "#b_new_password" },
	    b_admin_mobile_number:{ minlength:10, maxlength:15, numberandsign:true },
      branch_code: { minlength: 6, maxlength: 6 }
		},
		messages: {
		  s_new_password: { minlength: "Password at least 6 chars!" },
		  s_conf_password: { equalTo: "Password fields have to match !!", minlength: "Confirm password at least 6 chars!" },
		  b_new_password: { minlength: "Password at least 6 chars!" },
		  b_conf_password: { equalTo: "Password fields have to match !!", minlength: "Confirm password at least 6 chars!" }
		}
	});
}
});
</script>
<script> // Same as above details
$(document).on('click','.form-horizontal #same_details', function(){
  if($(this).is(":checked")) {
    var admin_name     		= 	$('.form-horizontal #s_admin_name').val();
    var admin_display_name  =   $('.form-horizontal #s_admin_display_name').val();
    //var admin_slug   		=   $('.form-horizontal #s_admin_slug').val();
   // var admin_email_id   	=   $('.form-horizontal #s_admin_email_id').val();
    var admin_mobile_number =   $('.form-horizontal #s_admin_mobile_number').val();
	var admin_address       =   $('.form-horizontal #s_admin_address').val();
	var admin_locality      =   $('.form-horizontal #s_admin_locality').val();
	var admin_city        	=   $('.form-horizontal #s_admin_city').val();
	var admin_state        	=   $('.form-horizontal #s_admin_state').val();
	var admin_zipcode       =   $('.form-horizontal #s_admin_zipcode').val();
    
    $(".form-horizontal #b_admin_name").val(admin_name);
	$(".form-horizontal #b_admin_display_name").val(admin_display_name);
	//$(".form-horizontal #b_admin_slug").val(admin_slug);
	//$(".form-horizontal #b_admin_email_id").val(admin_email_id);
	$(".form-horizontal #b_admin_mobile_number").val(admin_mobile_number);
	$(".form-horizontal #b_admin_address").val(admin_address);
	$(".form-horizontal #b_admin_locality").val(admin_locality);
	$(".form-horizontal #b_admin_city").val(admin_city);
	$(".form-horizontal #b_admin_state").val(admin_state);
	$(".form-horizontal #b_admin_zipcode").val(admin_zipcode);
	
  } else {
	<?php if($EDITDATA <> "" || $_POST): ?> 
	
		var admin_name     		= 	'<?php echo $b_admin_name; ?>';
		var admin_display_name  =   '<?php echo $b_admin_display_name; ?>';
		var admin_slug   		=   '<?php echo $b_admin_slug; ?>';
	    var admin_email_id   	=   '<?php echo $b_admin_email_id; ?>';
		var admin_mobile_number =   '<?php echo $b_admin_mobile_number; ?>';
		var admin_address       =   '<?php echo $b_admin_address; ?>';
		var admin_locality      =   '<?php echo $b_admin_locality; ?>';
		var admin_city        	=   '<?php echo $b_admin_city; ?>';
		var admin_state        	=   '<?php echo $b_admin_state; ?>';
		var admin_zipcode       =   '<?php echo $b_admin_zipcode; ?>';
		
		$(".form-horizontal #b_admin_name").val(admin_name);
		$(".form-horizontal #b_admin_display_name").val(admin_display_name);
		$(".form-horizontal #b_admin_slug").val(admin_slug);
		$(".form-horizontal #b_admin_email_id").val(admin_email_id);
		$(".form-horizontal #b_admin_mobile_number").val(admin_mobile_number);
		$(".form-horizontal #b_admin_address").val(admin_address);
		$(".form-horizontal #b_admin_locality").val(admin_locality);
		$(".form-horizontal #b_admin_city").val(admin_city);
		$(".form-horizontal #b_admin_state").val(admin_state);
		$(".form-horizontal #b_admin_zipcode").val(admin_zipcode);
	
	<?php else: ?>
			
		$(".form-horizontal #b_admin_name").val('');
		$(".form-horizontal #b_admin_display_name").val('');
		$(".form-horizontal #b_admin_slug").val('');
		$(".form-horizontal #b_admin_email_id").val('');
		$(".form-horizontal #b_admin_mobile_number").val('');
		$(".form-horizontal #b_admin_address").val('');
		$(".form-horizontal #b_admin_locality").val('');
		$(".form-horizontal #b_admin_city").val('');
		$(".form-horizontal #b_admin_state").val('');
		$(".form-horizontal #b_admin_zipcode").val('');
	
	<?php endif; ?>
  }
});
</script>
<script>
var blink_speed = 1000; var t = setInterval(function () { var ele = document.getElementById('blinker'); ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden'); var ele1 = document.getElementById('blinker1'); ele1.style.visibility = (ele1.style.visibility == 'hidden' ? '' : 'hidden'); }, blink_speed);
</script>