<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('subadminAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      subadmin details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    subadmin details</li>
  <li class="pull-right"><a href="<?php echo correctLink('subadminAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          subadmin details</span> </header>
        <div class="panel-body"> 
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Department<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('admin_department_id')): $admindepartmentid	=	set_value('admin_department_id'); elseif($EDITDATA['admin_department_id']): $admindepartmentid	=	stripslashes($EDITDATA['admin_department_id']); else: $admindepartmentid	=	''; endif; ?>
                  <select name="admin_department_id" id="admin_department_id" class="form-control required">
                  	<option value="">Select department</option>
                    <?php if($DPDATA <> ""): foreach($DPDATA as $DPINFO): ?>
                    	<option value="<?php echo $DPINFO['encrypt_id']; ?>" <?php if($DPINFO['encrypt_id'] == $admindepartmentid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($DPINFO['department_name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('admin_department_id')): ?>
                  <p for="admin_department_id" generated="true" class="error"><?php echo form_error('admin_department_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
               <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Designation<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('admin_designation_id')): $admindesignationid	=	set_value('admin_designation_id'); elseif($EDITDATA['admin_designation_id']): $admindesignationid	=	stripslashes($EDITDATA['admin_designation_id']); else: $admindesignationid	=	''; endif; ?>
                  <select name="admin_designation_id" id="admin_department_id" class="form-control required">
                  	<option value="">Select designation</option>
                    <?php if($DGDATA <> ""): foreach($DGDATA as $DGINFO): ?>
                    	<option value="<?php echo $DGINFO['encrypt_id']; ?>" <?php if($DGINFO['encrypt_id'] == $admindesignationid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($DGINFO['designation_name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('admin_designation_id')): ?>
                  <p for="admin_designation_id" generated="true" class="error"><?php echo form_error('admin_designation_id'); ?></p>
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
                  <?php endif; if($mobileerror):  ?>
                  <p for="admin_mobile_number" generated="true" class="error"><?php echo $mobileerror; ?></p>
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
            <br clear="all" />
            <fieldset>
              <legend>Permission section</legend>
              <div class="col-md-12 col-sm-12 col-xs-12 modulpadding">
              	<div class="col-md-12 col-sm-12 col-xs-12 modul paddbotm">
                	<div class="col-md-7 mdul">
                    	<?php if($PERERROR): echo '<p generated="true" class="error">'.$PERERROR.'</p>'; endif; ?>
                    </div>
                    <div class="col-md-1 mdul">
                    	<h4>View data</h4>
                    </div>
                    <div class="col-md-1 mdul">
                    	<h4>Add data</h4>
                    </div>
                    <div class="col-md-1 mdul">
                    	<h4>Edit data</h4>
                    </div>
                    <div class="col-md-1 mdul">
                    	<h4>Delete data</h4>
                    </div>
                </div>
                <?php if($Modirectory <> ""): $i=1; foreach($Modirectory as $MODinfo): $mmc = $MODinfo['encrypt_id'];  
					  if($_POST['mainmodule'.$mmc]):
						$mainmodactive					=	'Y';
						if($_POST['mainmodule_view_data'.$mmc]):
							$mainmod_view_active		=	'Y';
						else:
							$mainmod_view_active		=	'N';
						endif;
						if($_POST['mainmodule_add_data'.$mmc]):
							$mainmod_add_active			=	'Y';
						else:
							$mainmod_add_active			=	'N';
						endif;
						if($_POST['mainmodule_edit_data'.$mmc]):
							$mainmod_edit_active		=	'Y';
						else:
							$mainmod_edit_active		=	'N';
						endif;
						if($_POST['mainmodule_delete_data'.$mmc]):
							$mainmod_delete_active		=	'Y';
						else:
							$mainmod_delete_active		=	'N';
						endif;
					elseif($MODULEDATA['mainmodule'.$mmc]):
						$mainmodactive					=	'Y';
						if($MODULEDATA['mainmodule_view_data'.$mmc]):
							$mainmod_view_active		=	'Y';
						else:
							$mainmod_view_active		=	'N';
						endif;
						if($MODULEDATA['mainmodule_add_data'.$mmc]):
							$mainmod_add_active			=	'Y';
						else:
							$mainmod_add_active			=	'N';
						endif;
						if($MODULEDATA['mainmodule_edit_data'.$mmc]):
							$mainmod_edit_active		=	'Y';
						else:
							$mainmod_edit_active		=	'N';
						endif;
						if($MODULEDATA['mainmodule_delete_data'.$mmc]):
							$mainmod_delete_active		=	'Y';
						else:
							$mainmod_delete_active		=	'N';
						endif;
					else:
						$mainmodactive					=	'N';
						$mainmod_view_active			=	'N';
						$mainmod_add_active				=	'N';
						$mainmod_edit_active			=	'N';
						$mainmod_delete_active			=	'N';
					endif;
				?> 
                <div class="col-md-12 col-sm-12 col-xs-12 modul">
                	<div class="col-md-2 mdul mdulH">
                    	<h4>Module <?php echo $i; ?></h4>
                    </div>
                    <div class="col-md-5 mdul checkmd paddbotm">
                    	<div class="checkmd">
                  			<input type="checkbox" name="mainmodule<?php echo $mmc; ?>" id="mainmodule<?php echo $mmc; ?>" value="Y" class="parentmodule" <?php if($mainmodactive == 'Y'): echo 'checked="checked"'; endif; ?>/>
                  			&nbsp;<label for="mainmodule<?php echo $mmc; ?>"><?php echo ucfirst($MODinfo['module_display_name']); ?></label>
                        </div>
                    </div>
                    <?php if($MODinfo['child_data'] == 'N'): ?>
                    <div class="col-md-1 mdul paddbotm">
                    	<input type="checkbox" name="mainmodule_view_data<?php echo $mmc; ?>" id="mainmodule_view_data<?php echo $mmc; ?>" value="Y" class="parentpermission" <?php if($mainmod_view_active == 'Y'): echo 'checked="checked"'; endif; ?>/>
                    </div>
                    <div class="col-md-1 mdul paddbotm">
                    	<input type="checkbox" name="mainmodule_add_data<?php echo $mmc; ?>" id="mainmodule_add_data<?php echo $mmc; ?>" value="Y" class="parentpermission" <?php if($mainmod_add_active == 'Y'): echo 'checked="checked"'; endif; ?>/>
                    </div>
                    <div class="col-md-1 mdul paddbotm">
                    	<input type="checkbox" name="mainmodule_edit_data<?php echo $mmc; ?>" id="mainmodule_edit_data<?php echo $mmc; ?>" value="Y" class="parentpermission" <?php if($mainmod_edit_active == 'Y'): echo 'checked="checked"'; endif; ?>/>
                    </div>
                    <div class="col-md-1 mdul paddbotm">
                    	<input type="checkbox" name="mainmodule_delete_data<?php echo $mmc; ?>" id="mainmodule_delete_data<?php echo $mmc; ?>" value="Y" class="parentpermission" <?php if($mainmod_delete_active == 'Y'): echo 'checked="checked"'; endif; ?>/>
                    </div>
               		<?php else: ?>
                    <div class="col-md-1 mdul paddbotm">&nbsp;</div>
                    <div class="col-md-1 mdul paddbotm">&nbsp;</div>
                    <div class="col-md-1 mdul paddbotm">&nbsp;</div>
                    <div class="col-md-1 mdul paddbotm">&nbsp;</div>
                    <?php $childdata 		= 	$this->admin_model->get_child_module($MODinfo['encrypt_id']);
					    if($childdata <> ""):
                         foreach($childdata as $CDinfo):	 $cmc = $CDinfo['encrypt_id'];	
                          if($_POST['childmodule'.$mmc.'_'.$cmc]):
                            $childmodactive					=	'Y';
                            if($_POST['childmodule_view_data'.$mmc.'_'.$cmc]):
                                $childmod_view_active		=	'Y';
                            else:
                                $childmod_view_active		=	'N';
                            endif;
                            if($_POST['childmodule_add_data'.$mmc.'_'.$cmc]):
                                $childmod_add_active		=	'Y';
                            else:
                                $childmod_add_active		=	'N';
                            endif;
                            if($_POST['childmodule_edit_data'.$mmc.'_'.$cmc]):
                                $childmod_edit_active		=	'Y';
                            else:
                                $childmod_edit_active		=	'N';
                            endif;
                            if($_POST['childmodule_delete_data'.$mmc.'_'.$cmc]):
                                $childmod_delete_active		=	'Y';
                            else:
                                $childmod_delete_active		=	'N';
                            endif;
						elseif($MODULEDATA['childmodule'.$mmc.'_'.$cmc]):
                            $childmodactive					=	'Y';
                            if($MODULEDATA['childmodule_view_data'.$mmc.'_'.$cmc]):
                                $childmod_view_active		=	'Y';
                            else:
                                $childmod_view_active		=	'N';
                            endif;
                            if($MODULEDATA['childmodule_add_data'.$mmc.'_'.$cmc]):
                                $childmod_add_active		=	'Y';
                            else:
                                $childmod_add_active		=	'N';
                            endif;
                            if($MODULEDATA['childmodule_edit_data'.$mmc.'_'.$cmc]):
                                $childmod_edit_active		=	'Y';
                            else:
                                $childmod_edit_active		=	'N';
                            endif;
                            if($MODULEDATA['childmodule_delete_data'.$mmc.'_'.$cmc]):
                                $childmod_delete_active		=	'Y';
                            else:
                                $childmod_delete_active		=	'N';
                            endif;
                        else:
                            $childmodactive					=	'N';
                            $childmod_view_active			=	'N';
                            $childmod_add_active			=	'N';
                            $childmod_edit_active			=	'N';
                            $childmod_delete_active			=	'N';
                        endif;
                        ?>
                    <div class="col-md-12 col-sm-12 col-xs-12 submodul">
                        <div class="col-md-2 mdul mdulH">
                            &nbsp;
                        </div>
                        <div class="col-md-5 mdul checkmd paddbotm">
                            <div class="checkmd">
                                <input type="checkbox" name="childmodule<?php echo $mmc;?>_<?php echo $cmc;?>" id="childmodule<?php echo $mmc;?>_<?php echo $cmc;?>" value="Y" class="childmodule"  <?php if($childmodactive == 'Y'): echo 'checked="checked"'; endif; ?>/>
                                &nbsp;<label for="childmodule<?php echo $mmc;?>_<?php echo $cmc;?>"><?php echo ucfirst($CDinfo['module_display_name']); ?></label>
                            </div>
                        </div>
                        <div class="col-md-1 mdul paddbotm">
                            <input type="checkbox" name="childmodule_view_data<?php echo $mmc; ?>_<?php echo $cmc;?>" id="childmodule_view_data<?php echo $mmc; ?>_<?php echo $cmc;?>" value="Y" class="childpermission" <?php if($childmod_view_active == 'Y'): echo 'checked="checked"'; endif; ?>>
                        </div>
                        <div class="col-md-1 mdul paddbotm">
                            <input type="checkbox" name="childmodule_add_data<?php echo $mmc; ?>_<?php echo $cmc;?>" id="childmodule_add_data<?php echo $mmc; ?>_<?php echo $cmc;?>" value="Y" class="childpermission" <?php if($childmod_add_active == 'Y'): echo 'checked="checked"'; endif; ?>>
                        </div>
                        <div class="col-md-1 mdul paddbotm">
                            <input type="checkbox" name="childmodule_edit_data<?php echo $mmc; ?>_<?php echo $cmc;?>" id="childmodule_edit_data<?php echo $mmc; ?>_<?php echo $cmc;?>" value="Y" class="childpermission" <?php if($childmod_edit_active == 'Y'): echo 'checked="checked"'; endif; ?>>
                        </div>
                        <div class="col-md-1 mdul paddbotm">
                            <input type="checkbox" name="childmodule_delete_data<?php echo $mmc; ?>_<?php echo $cmc;?>" id="childmodule_delete_data<?php echo $mmc; ?>_<?php echo $cmc;?>" value="Y" class="childpermission" <?php if($childmod_delete_active == 'Y'): echo 'checked="checked"'; endif; ?>>
                        </div>
                    </div>
                    <?php $j++; endforeach; endif; ?>
                    <?php endif; ?>  
                  </div>
                 <?php $i++; endforeach; endif; ?>  
                </div>
              </fieldset>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('subadminAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
$(document).on('click','#currentPageForm .parentmodule',function(){ 
	var curobj	=	$(this); 
	if(curobj.prop("checked") == true){
		curobj.closest('.modul').find('.parentpermission').prop('checked', true);
		curobj.closest('.modul').children('.submodul').find('.childmodule').prop('checked', true);
		curobj.closest('.modul').children('.submodul').find('.childpermission').prop('checked', true);
	}
	else if(curobj.prop("checked") == false){
		curobj.closest('.modul').find('.parentpermission').prop('checked', false);
		curobj.closest('.modul').children('.submodul').find('.childmodule').prop('checked', false);
		curobj.closest('.modul').children('.submodul').find('.childpermission').prop('checked', false);
	}
});

$(document).on('click','#currentPageForm .parentpermission',function(){
	var curobj	=	$(this); 
	var	count	=	0;
	curobj.closest('.modul').find('.parentpermission').each(function(){
		if($(this).prop("checked") == true){
			count = 1;
		}
	});
	if(count == 1){
		curobj.closest('.modul').find('.parentmodule').prop('checked', true);
	}
	else {
		curobj.closest('.modul').find('.parentmodule').prop('checked', false);
	}
});

$(document).on('click','#currentPageForm .childmodule',function(){ 
	var curobj	=	$(this);  
	if(curobj.prop("checked") == true){
		curobj.closest('.submodul').find('.childpermission').prop('checked', true);
	}
	else if(curobj.prop("checked") == false){
		curobj.closest('.submodul').find('.childpermission').prop('checked', false);
	}
	
	var	count	=	0;
	curobj.closest('.modul').find('.childmodule').each(function(){
		if($(this).prop("checked") == true){
			count = 1;
		}
	});  
	if(count == 1){
		curobj.closest('.modul').find('.parentmodule').prop('checked', true);
	}
	else {
		curobj.closest('.modul').find('.parentmodule').prop('checked', false);
	}
});

$(document).on('click','#currentPageForm .childpermission',function(){
	var curobj	=	$(this); 
	var	count	=	0;
	curobj.closest('.submodul').find('.childpermission').each(function(){
		if($(this).prop("checked") == true){
			count = 1;
		}
	});
	if(count == 1){
		curobj.closest('.submodul').find('.childmodule').prop('checked', true);
	}
	else {
		curobj.closest('.submodul').find('.childmodule').prop('checked', false);
	}
	
	var	counts	=	0;
	curobj.closest('.modul').find('.childpermission').each(function(){
		if($(this).prop("checked") == true){
			counts = 1;
		}
	});  
	if(counts == 1){
		curobj.closest('.modul').find('.parentmodule').prop('checked', true);
	}
	else {
		curobj.closest('.modul').find('.parentmodule').prop('checked', false);
	}
});
</script>