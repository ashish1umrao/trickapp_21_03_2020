<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
$(function(){
	$("#birth_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y')-2; ?>"});
	$("#join_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('teacherListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      teacher details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    teacher details</li>
  <li class="pull-right"><a href="<?php echo correctLink('teacherListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
     <?php if($EDITDATA <> ""): ?>
    <ul class="nav nav-tabs blue_tab">
      <li class="active"><a href="javascript:void(0);">Personal</a></li>
        <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdoc/<?php echo $userId; ?>">Upload Documents</a></li>
    </ul>
    <?php endif; ?>
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          teacher details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
            <fieldset>
            <legend>Teacher details</legend>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Subject name<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('subject_id')): $subjectid	=	set_value('subject_id'); elseif($SUBJECTIDS): $subjectid	=	$SUBJECTIDS; else: $subjectid	=	array(); endif; ?>
                    <select name="subject_id[]" id="subject_id" multiple="multiple" class="form-control required">
                      <option value="">Select subject name</option>
                      <?php if($SUBJECTDATA <> ""): foreach($SUBJECTDATA as $SUBJECTINFO): ?>
                      <option value="<?php echo $SUBJECTINFO['encrypt_id']; ?>" <?php if(in_array($SUBJECTINFO['encrypt_id'],$subjectid)): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($SUBJECTINFO['subject_name'].' ('.$SUBJECTINFO['board_name'].')'); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('subject_id[]')): ?>
                    <p for="subject_id" generated="true" class="error"><?php echo form_error('subject_id[]'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Employee id</label>
                  <div class="col-lg-8">
                    <input type="text" name="employee_id" id="employee_id" value="<?php if(set_value('employee_id')): echo set_value('employee_id'); else: echo stripslashes($EDITDATA['employee_id']);endif; ?>" class="form-control" placeholder="Employee id">
                    <?php if(form_error('employee_id')): ?>
                    <p for="employee_id" generated="true" class="error"><?php echo form_error('employee_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">First name<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="user_f_name" id="user_f_name" value="<?php if(set_value('user_f_name')): echo set_value('user_f_name'); else: echo stripslashes($EDITDATA['user_f_name']);endif; ?>" class="form-control required" placeholder="First name">
                    <?php if(form_error('user_f_name')): ?>
                    <p for="user_f_name" generated="true" class="error"><?php echo form_error('user_f_name'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Middle name</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_m_name" id="user_m_name" value="<?php if(set_value('user_m_name')): echo set_value('user_m_name'); else: echo stripslashes($EDITDATA['user_m_name']);endif; ?>" class="form-control" placeholder="Middle name">
                    <?php if(form_error('user_m_name')): ?>
                    <p for="user_m_name" generated="true" class="error"><?php echo form_error('user_m_name'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Last name</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_l_name" id="user_l_name" value="<?php if(set_value('user_l_name')): echo set_value('user_l_name'); else: echo stripslashes($EDITDATA['user_l_name']);endif; ?>" class="form-control" placeholder="Last name">
                    <?php if(form_error('user_l_name')): ?>
                    <p for="user_l_name" generated="true" class="error"><?php echo form_error('user_l_name'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Email<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="user_email" id="user_email" value="<?php if(set_value('user_email')): echo set_value('user_email'); else: echo stripslashes($EDITDATA['user_email']);endif; ?>" class="form-control required" placeholder="Email">
                    <?php if(form_error('user_email')): ?>
                    <p for="user_email" generated="true" class="error"><?php echo form_error('user_email'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <?php if($EDITDATA <> ""): ?>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">New password</label>
                  <div class="col-lg-8">
                    <input type="password" name="new_password" id="new_password" value="<?php if(set_value('new_password')): echo set_value('new_password'); endif; ?>" class="form-control" placeholder="New password">
                    <?php if(form_error('new_password')): ?>
                    <p for="new_password" generated="true" class="error"><?php echo form_error('new_password'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Confirm password</label>
                  <div class="col-lg-8">
                    <input type="password" name="conf_password" id="conf_password" value="<?php if(set_value('conf_password')): echo set_value('conf_password'); endif; ?>" class="form-control" placeholder="Confirm password">
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
                    <input type="password" name="new_password" id="new_password" value="<?php if(set_value('new_password')): echo set_value('new_password'); endif; ?>" class="form-control required" placeholder="Password">
                    <?php if(form_error('new_password')): ?>
                    <p for="new_password" generated="true" class="error"><?php echo form_error('new_password'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Confirm password<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="password" name="conf_password" id="conf_password" value="<?php if(set_value('conf_password')): echo set_value('conf_password'); endif; ?>" class="form-control required" placeholder="Confirm password">
                    <?php if(form_error('conf_password')): ?>
                    <p for="conf_password" generated="true" class="error"><?php echo form_error('conf_password'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <?php endif; ?>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Phone1</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_phone" id="user_phone" value="<?php if(set_value('user_phone')): echo set_value('user_phone'); else: echo stripslashes($EDITDATA['user_phone']);endif; ?>" class="form-control" minlength="10"  maxlength="15" placeholder="Phone1">
                    <?php if(form_error('user_phone')): ?>
                    <p for="user_phone" generated="true" class="error"><?php echo form_error('user_phone'); ?></p>
                    <?php endif; if($phone1error):  ?>
                    <p for="user_phone" generated="true" class="error"><?php echo $phone1error; ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Phone2</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_emer_phone" id="user_emer_phone" value="<?php if(set_value('user_emer_phone')): echo set_value('user_emer_phone'); else: echo stripslashes($EDITDATA['user_emer_phone']);endif; ?>" class="form-control" minlength="10"  maxlength="15" placeholder="Phone2">
                    <?php if(form_error('user_emer_phone')): ?>
                    <p for="user_emer_phone" generated="true" class="error"><?php echo form_error('user_emer_phone'); ?></p>
                    <?php endif;  if($phone2error):  ?>
                    <p for="user_emer_phone" generated="true" class="error"><?php echo $phone2error; ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Mobile1<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="user_mobile" id="user_mobile" value="<?php if(set_value('user_mobile')): echo set_value('user_mobile'); else: echo stripslashes($EDITDATA['user_mobile']);endif; ?>" class="form-control" minlength="10"  maxlength="15" placeholder="Mobile1">
                    <?php if(form_error('user_mobile')): ?>
                    <p for="user_mobile" generated="true" class="error"><?php echo form_error('user_mobile'); ?></p>
                    <?php endif;  if($mobile1error):  ?>
                    <p for="user_mobile" generated="true" class="error"><?php echo $mobile1error; ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Mobile2</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_emer_mobile" id="user_emer_mobile" value="<?php if(set_value('user_emer_mobile')): echo set_value('user_emer_mobile'); else: echo stripslashes($EDITDATA['user_emer_mobile']);endif; ?>" class="form-control" minlength="10"  maxlength="15" placeholder="Mobile2">
                    <?php if(form_error('user_emer_mobile')): ?>
                    <p for="user_emer_mobile" generated="true" class="error"><?php echo form_error('user_emer_mobile'); ?></p>
                    <?php endif;  if($mobile2error):  ?>
                    <p for="user_emer_mobile" generated="true" class="error"><?php echo $mobile2error; ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Gender<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('user_gender')): $usergenders	=	explode('___',set_value('user_gender')); $usergender	=	$usergenders[0]?$usergenders[0]:''; elseif($EDITDATA['user_gender']): $usergender	=	$EDITDATA['user_gender']; else: $usergender	=	''; endif; ?>
                    <select name="user_gender" id="user_gender" class="form-control required">
                      <option value="">Select gender</option>
                      <?php if($GENDERDATA <> ""): foreach($GENDERDATA as $GENDERINFO): ?>
                      <option value="<?php echo $GENDERINFO['user_gender_name'].'___'.$GENDERINFO['user_gender_short_name']; ?>" <?php if($GENDERINFO['user_gender_name'] == $usergender): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($GENDERINFO['user_gender_name']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('user_gender')): ?>
                    <p for="user_gender" generated="true" class="error"><?php echo form_error('user_gender'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Marital status<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('user_marital_status')): $usermaritalstatuss	=	explode('___',set_value('user_marital_status')); $usermaritalstatus	=	$usermaritalstatuss[0]?$usermaritalstatuss[0]:''; elseif($EDITDATA['user_marital_status']): $usermaritalstatus	=	$EDITDATA['user_marital_status']; else: $usermaritalstatus	=	''; endif; ?>
                    <select name="user_marital_status" id="user_marital_status" class="form-control required">
                      <option value="">Select marital status</option>
                      <?php if($MERITALDATA <> ""): foreach($MERITALDATA as $MERITALINFO): ?>
                      <option value="<?php echo $MERITALINFO['user_merital_name'].'___'.$MERITALINFO['user_merital_short_name']; ?>" <?php if($MERITALINFO['user_merital_name'] == $usermaritalstatus): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($MERITALINFO['user_merital_name']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('user_marital_status')): ?>
                    <p for="user_marital_status" generated="true" class="error"><?php echo form_error('user_marital_status'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Blood group</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_blood_group" id="user_blood_group" value="<?php if(set_value('user_blood_group')): echo set_value('user_blood_group'); else: echo stripslashes($EDITDATA['user_blood_group']);endif; ?>" class="form-control" placeholder="Blood group">
                    <?php if(form_error('user_blood_group')): ?>
                    <p for="user_blood_group" generated="true" class="error"><?php echo form_error('user_blood_group'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Religion</label>
                  <div class="col-lg-8">
                    <?php if(set_value('user_religion')): $userreligion	=	set_value('user_religion'); elseif($EDITDATA['user_religion']): $userreligion	=	$EDITDATA['user_religion']; else: $userreligion	=	''; endif; ?>
                    <select name="user_religion" id="user_religion" class="form-control required">
                      <option value="">Select religion</option>
                      <?php if($RERIGIONDATA <> ""): foreach($RERIGIONDATA as $RERIGIONINFO): ?>
                      <option value="<?php echo $RERIGIONINFO['user_religion_name']; ?>" <?php if($RERIGIONINFO['user_religion_name'] == $userreligion): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($RERIGIONINFO['user_religion_name']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('user_religion')): ?>
                    <p for="user_religion" generated="true" class="error"><?php echo form_error('user_religion'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Nationality</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_nationality" id="user_nationality" value="<?php if(set_value('user_nationality')): echo set_value('user_nationality'); else: echo stripslashes($EDITDATA['user_nationality']);endif; ?>" class="form-control" placeholder="Nationality">
                    <?php if(form_error('user_nationality')): ?>
                    <p for="user_nationality" generated="true" class="error"><?php echo form_error('user_nationality'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            </fieldset>
            <br clear="all" />
            <fieldset>
            <legend>Teacher's correspondence address</legend>
            <div class="address-parent">
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">State</label>
                    <div class="col-lg-8">
                      <?php if(set_value('user_c_state')): $usercstate	=	set_value('user_c_state'); elseif($EDITDATA['user_c_state']): $usercstate	=	$EDITDATA['user_c_state']; else: $usercstate	=	''; endif; ?>
                      <select name="user_c_state" id="user_c_state" class="form-control selectstate">
                        <option value="">Select state</option>
                        <?php if($STATEDATA <> ""): foreach($STATEDATA as $STATEINFO): ?>
                        <option value="<?php echo $STATEINFO['state']; ?>" <?php if($STATEINFO['state'] == $usercstate): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($STATEINFO['state']); ?></option>
                        <?php endforeach; endif; ?>
                      </select>
                      <?php if(form_error('user_c_state')): ?>
                      <p for="user_c_state" generated="true" class="error"><?php echo form_error('user_c_state'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">City</label>
                    <div class="col-lg-8">
                      <?php if(set_value('user_c_city')): $userccity	=	set_value('user_c_city'); elseif($EDITDATA['user_c_city']): $userccity	=	stripslashes($EDITDATA['user_c_city']); else: $userccity	=	''; endif; ?>
                      <?php /*?><select name="user_c_city" id="user_c_city" class="form-control selectcity">
                        <option value="">Select city</option>
                      </select><?php */?>
                      <input type="text" name="user_c_city" id="user_c_city" value="<?php echo $userccity; ?>" class="form-control selectcity" placeholder="City">
                      <?php if(form_error('user_c_city')): ?>
                      <p for="user_c_city" generated="true" class="error"><?php echo form_error('user_c_city'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Locality</label>
                    <div class="col-lg-8">
                      <?php if(set_value('user_c_locality')): $userclocality	=	set_value('user_c_locality'); elseif($EDITDATA['user_c_locality']): $userclocality	=	stripslashes($EDITDATA['user_c_locality']); else: $userclocality	=	''; endif; ?>
                      <?php /*?><select name="user_c_locality" id="user_c_locality" class="form-control selectlocality">
                        <option value="">Select locality</option>
                      </select><?php */?>
                      <input type="text" name="user_c_locality" id="user_c_locality" value="<?php echo $userclocality; ?>" class="form-control selectlocality" placeholder="Locality">
                      <?php if(form_error('user_c_locality')): ?>
                      <p for="user_c_locality" generated="true" class="error"><?php echo form_error('user_c_locality'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Address</label>
                    <div class="col-lg-8">
                      <?php if(set_value('user_c_address')): $usercaddress	=	set_value('user_c_address'); elseif($EDITDATA['user_c_address']): $usercaddress	=	stripslashes($EDITDATA['user_c_address']); else:  $usercaddress	=	''; endif; ?>
                      <input type="text" name="user_c_address" id="user_c_address" value="<?php echo $usercaddress; ?>" class="form-control selectaddress" placeholder="Address">
                      <?php if(form_error('user_c_address')): ?>
                      <p for="user_c_address" generated="true" class="error"><?php echo form_error('user_c_address'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Zip code</label>
                    <div class="col-lg-8">
                      <?php if(set_value('user_c_zipcode')): $userczipcode	=	set_value('user_c_zipcode'); elseif($EDITDATA['user_c_zipcode']): $userczipcode	=	stripslashes($EDITDATA['user_c_zipcode']); else: $userczipcode	=	''; endif; ?>
                      <input type="text" name="user_c_zipcode" id="user_c_zipcode" value="<?php echo $userczipcode; ?>" class="form-control selectzip" placeholder="Zip code">
                      <?php if(form_error('user_c_zipcode')): ?>
                      <p for="user_c_zipcode" generated="true" class="error"><?php echo form_error('user_c_zipcode'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </fieldset>
            <br clear="all" />
            <fieldset>
            <legend>Permanent address</legend>
            <div class="address-parent">
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">&nbsp;</label>
                    <div class="col-lg-8">
                      <?php if(set_value('same_address')): $sameaddress	=	set_value('same_address'); elseif($EDITDATA['same_address']): $sameaddress	=	$EDITDATA['same_address']; else: $sameaddress	=	''; endif; ?>
                      <input type="checkbox" name="same_address" id="same_address" value="Y" <?php if($sameaddress == 'Y'): echo 'checked="checked"'; endif; ?>>
                      <label for="same_address">Same as correspondence address</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">State</label>
                    <div class="col-lg-8">
                      <?php if(set_value('user_p_state')): $userpstate	=	set_value('user_p_state'); elseif($EDITDATA['user_p_state']): $userpstate	=	$EDITDATA['user_p_state']; else: $userpstate	=	''; endif; ?>
                      <select name="user_p_state" id="user_p_state" class="form-control selectstate">
                        <option value="">Select state</option>
                        <?php if($STATEDATA <> ""): foreach($STATEDATA as $STATEINFO): ?>
                        <option value="<?php echo $STATEINFO['state']; ?>" <?php if($STATEINFO['state'] == $userpstate): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($STATEINFO['state']); ?></option>
                        <?php endforeach; endif; ?>
                      </select>
                      <?php if(form_error('user_p_state')): ?>
                      <p for="user_p_state" generated="true" class="error"><?php echo form_error('user_p_state'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">City</label>
                    <div class="col-lg-8">
                      <?php if(set_value('user_p_city')): $userpcity	=	set_value('user_p_city'); elseif($EDITDATA['user_p_city']): $userpcity	=	stripslashes($EDITDATA['user_p_city']); else: $userpcity	=	''; endif; ?>
                      <?php /*?><select name="user_p_city" id="user_p_city" class="form-control selectcity">
                        <option value="">Select city</option>
                      </select><?php */?>
                      <input type="text" name="user_p_city" id="user_p_city" value="<?php echo $userpcity; ?>" class="form-control selectcity" placeholder="City">
                      <?php if(form_error('user_p_city')): ?>
                      <p for="user_p_city" generated="true" class="error"><?php echo form_error('user_p_city'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Locality</label>
                    <div class="col-lg-8">
                      <?php if(set_value('user_p_locality')): $userplocality	=	set_value('user_p_locality'); elseif($EDITDATA['user_p_locality']): $userplocality	=	stripslashes($EDITDATA['user_p_locality']); else: $userplocality	=	''; endif; ?>
                      <?php /*?><select name="user_p_locality" id="user_p_locality" class="form-control selectlocality">
                        <option value="">Select locality</option>
                      </select><?php */?>
                      <input type="text" name="user_p_locality" id="user_p_locality" value="<?php echo $userplocality; ?>" class="form-control selectlocality" placeholder="Locality">
                      <?php if(form_error('user_p_locality')): ?>
                      <p for="user_p_locality" generated="true" class="error"><?php echo form_error('user_p_locality'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Address</label>
                    <div class="col-lg-8">
                      <?php if(set_value('user_p_address')): $userpaddress	=	set_value('user_p_address'); elseif($EDITDATA['user_p_address']): $userpaddress	=	stripslashes($EDITDATA['user_p_address']); else: $userpaddress	=	''; endif; ?>
                      <input type="text" name="user_p_address" id="user_p_address" value="<?php echo $userpaddress; ?>" class="form-control selectaddress" placeholder="Address">
                      <?php if(form_error('user_p_address')): ?>
                      <p for="user_p_address" generated="true" class="error"><?php echo form_error('user_p_address'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Zip code</label>
                    <div class="col-lg-8">
                      <?php if(set_value('user_p_zipcode')): $userpzipcode	=	set_value('user_p_zipcode'); elseif($EDITDATA['user_p_zipcode']): $userpzipcode	=	stripslashes($EDITDATA['user_p_zipcode']); else: $userpzipcode	=	''; endif; ?>
                      <input type="text" name="user_p_zipcode" id="user_p_zipcode" value="<?php echo $userpzipcode; ?>" class="form-control selectzip" placeholder="Zip code">
                      <?php if(form_error('user_p_zipcode')): ?>
                      <p for="user_p_zipcode" generated="true" class="error"><?php echo form_error('user_p_zipcode'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </fieldset>
            <br clear="all" />
            <fieldset>
            <legend>KYC details</legend>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Adhar number</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_adhar_no" id="user_adhar_no" value="<?php if(set_value('user_adhar_no')): echo set_value('user_adhar_no'); else: echo stripslashes($EDITDATA['user_adhar_no']);endif; ?>" class="form-control" placeholder="Adhar number">
                    <?php if(form_error('user_adhar_no')): ?>
                    <p for="user_adhar_no" generated="true" class="error"><?php echo form_error('user_adhar_no'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Pan number</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_pan_no" id="user_pan_no" value="<?php if(set_value('user_pan_no')): echo set_value('user_pan_no'); else: echo stripslashes($EDITDATA['user_pan_no']);endif; ?>" class="form-control" placeholder="Pan number">
                    <?php if(form_error('user_pan_no')): ?>
                    <p for="user_pan_no" generated="true" class="error"><?php echo form_error('user_pan_no'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Account number</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_account_no" id="user_account_no" value="<?php if(set_value('user_account_no')): echo set_value('user_account_no'); else: echo stripslashes($EDITDATA['user_account_no']);endif; ?>" class="form-control" placeholder="Account number">
                    <?php if(form_error('user_account_no')): ?>
                    <p for="user_account_no" generated="true" class="error"><?php echo form_error('user_account_no'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Name on account</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_name_on_account" id="user_name_on_account" value="<?php if(set_value('user_name_on_account')): echo set_value('user_name_on_account'); else: echo stripslashes($EDITDATA['user_name_on_account']);endif; ?>" class="form-control" placeholder="Name on account">
                    <?php if(form_error('user_name_on_account')): ?>
                    <p for="user_name_on_account" generated="true" class="error"><?php echo form_error('user_name_on_account'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Bank name</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_bank_name" id="user_bank_name" value="<?php if(set_value('user_bank_name')): echo set_value('user_bank_name'); else: echo stripslashes($EDITDATA['user_bank_name']);endif; ?>" class="form-control" placeholder="Bank name">
                    <?php if(form_error('user_bank_name')): ?>
                    <p for="user_bank_name" generated="true" class="error"><?php echo form_error('user_bank_name'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">IFSC code</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_ifsc_code" id="user_ifsc_code" value="<?php if(set_value('user_ifsc_code')): echo set_value('user_ifsc_code'); else: echo stripslashes($EDITDATA['user_ifsc_code']);endif; ?>" class="form-control" placeholder="IFSC code">
                    <?php if(form_error('user_ifsc_code')): ?>
                    <p for="user_ifsc_code" generated="true" class="error"><?php echo form_error('user_ifsc_code'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            </fieldset>
            <br clear="all" />
            <fieldset>
            <legend>Other Details</legend>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Date of birth</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_dob" id="birth_date" value="<?php if(set_value('user_dob')): echo set_value('user_dob'); else: echo YYMMDDtoDDMMYY($EDITDATA['user_dob']);endif; ?>" class="form-control" placeholder="Date of birth">
                    <?php if(form_error('user_dob')): ?>
                    <p for="user_dob" generated="true" class="error"><?php echo form_error('user_dob'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Date of joining</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_joining_date" id="join_date" value="<?php if(set_value('user_joining_date')): echo set_value('user_joining_date'); else: echo YYMMDDtoDDMMYY($EDITDATA['user_joining_date']);endif; ?>" class="form-control" placeholder="Date of joining">
                    <?php if(form_error('user_joining_date')): ?>
                    <p for="user_joining_date" generated="true" class="error"><?php echo form_error('user_joining_date'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Pickup location</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_pickup" id="user_pickup" value="<?php if(set_value('user_pickup')): echo set_value('user_pickup'); else: echo stripslashes($EDITDATA['user_pickup']);endif; ?>" class="form-control" placeholder="Pickup location">
                    <?php if(form_error('user_pickup')): ?>
                    <p for="user_pickup" generated="true" class="error"><?php echo form_error('user_pickup'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Drop location</label>
                  <div class="col-lg-8">
                    <input type="text" name="user_drop" id="user_drop" value="<?php if(set_value('user_drop')): echo set_value('user_drop'); else: echo stripslashes($EDITDATA['user_drop']);endif; ?>" class="form-control" placeholder="Drop location">
                    <?php if(form_error('user_drop')): ?>
                    <p for="user_drop" generated="true" class="error"><?php echo form_error('user_drop'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Profile picture</label>
                  <div class="col-lg-8"> <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                    <input type="text" id="uploadimage0" name="uploadimage0" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($EDITDATA['user_pic']); endif; ?>" class="browseimageclass" />
                    <br clear="all">
                    <?php if(form_error('uploadimage0')): ?>
                    <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label>
                    <?php endif; ?>
                    <label id="uploadstatus0" class="error"></label>
                    <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                    <span id="uploadphoto0" style="float:left;">
                    <?php if(set_value('uploadimage0')):?>
                    <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php elseif($EDITDATA['user_pic']):?>
                    <img src="<?php echo stripslashes($EDITDATA['user_pic'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($EDITDATA['user_pic'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php endif; ?>
                    </span> </div>
                </div>
              </div>
            </div>
            </fieldset>
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('teacherListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
});
</script>
<?php /*?><script> 
$(document).on('change','.form-horizontal .selectstate',function(){
  var curobj	=	$(this);
  var state     = 	$(this).val();
  var city      = 	'';
  
  get_city_data(curobj,state,city);
});

$(document).on('change','.form-horizontal .selectcity',function(){
  var curobj	=	$(this);
  var state     = 	curobj.closest('.address-parent').find('.selectstate').val();
  var city      = 	$(this).val();
  var locality  = 	'';
  
  get_locality_data(curobj,state,city,locality);
});

$(document).on('change','.form-horizontal .selectlocality',function(){
  var curobj	=	$(this);
  var state     = 	curobj.closest('.address-parent').find('.selectstate').val();
  var city      = 	curobj.closest('.address-parent').find('.selectcity').val();
  var locality  = 	$(this).val();
  var zip      = 	'';
  
  get_zipcode_data(curobj,state,city,locality,zip);
});
</script><?php */?>
<script> // same as correspondence address
$(document).on('click','.form-horizontal #same_address', function(){
  var curobj		=	$(this);
  if($(this).is(":checked")) {
    var state     	= 	$('.form-horizontal #user_c_state').val();
    var city       	=   $('.form-horizontal #user_c_city').val();
    var locality   	=   $('.form-horizontal #user_c_locality').val();
    var address   	=   $('.form-horizontal #user_c_address').val();
    var zip        	=   $('.form-horizontal #user_c_zipcode').val();
    
    $(".form-horizontal #user_p_state option[value='"+state+"']").prop("selected", true);
    curobj.closest('.address-parent').find('.selectcity').val(city);
	curobj.closest('.address-parent').find('.selectlocality').val(locality);
	curobj.closest('.address-parent').find('.selectaddress').val(address);
	curobj.closest('.address-parent').find('.selectzip').val(zip);
	
    //get_city_data(curobj,state,city);
    //get_locality_data(curobj,state,city,locality);
    //get_zipcode_data(curobj,state,city,locality,zip);
	//curobj.closest('.address-parent').find('.selectaddress').val(address);
	
  } else {
	<?php if($EDITDATA <> "" || $_POST): ?> 
	
		var state      	= 	'<?php echo $userpstate; ?>';
		var city       	=   '<?php echo $userpcity; ?>';
		var locality   	=   '<?php echo $userplocality; ?>';
		var address   	=   '<?php echo $userpaddress; ?>';
		var zip        	=   '<?php echo $userpzipcode; ?>';
		
		$(".form-horizontal #user_p_state option[value='"+state+"']").prop("selected", true);
		curobj.closest('.address-parent').find('.selectcity').val(city);
		curobj.closest('.address-parent').find('.selectlocality').val(locality);
		curobj.closest('.address-parent').find('.selectaddress').val(address);
		curobj.closest('.address-parent').find('.selectzip').val(zip);
	
		//get_city_data(curobj,state,city);
		//get_locality_data(curobj,state,city,locality);
		//get_zipcode_data(curobj,state,city,locality,zip);
		//curobj.closest('.address-parent').find('.selectaddress').val(address);
	
	<?php else: ?>
			
		$(".form-horizontal #user_p_state option:first").attr('selected','selected');
		curobj.closest('.address-parent').find('.selectcity').val('');
		curobj.closest('.address-parent').find('.selectlocality').val('');
		curobj.closest('.address-parent').find('.selectaddress').val('');
		curobj.closest('.address-parent').find('.selectzip').val('');
			
		//curobj.closest('.address-parent').find('.selectcity').parent().parent().show();
		//curobj.closest('.address-parent').find('.selectcity').html('<option value="">Select city</option>');
		//curobj.closest('.address-parent').find('.selectlocality').html('<option value="">Select locality</option>');
		//curobj.closest('.address-parent').find('.selectaddress').val('');
		//curobj.closest('.address-parent').find('.selectzip').val('');
	
	<?php endif; ?>
  }
});
</script>
<?php /*?><script>
<?php if($EDITDATA <> "" || $_POST): ?> 
$(document).ready(function(){ 
	var c_state				=	'<?php echo $usercstate; ?>';  
	var c_city				=	'<?php echo $userccity; ?>'; 
	var	c_locality			=	'<?php echo $userclocality; ?>';
	var	c_address			=	'<?php echo $usercaddress; ?>';
	var	c_zipcode			=	'<?php echo $userczipcode; ?>';
	
	var curcobj				=	$(".form-horizontal #user_c_state");
	
	$(".form-horizontal #user_c_state option[value="+c_state+"]").prop("selected", true);
	get_city_data(curcobj,c_state,c_city);
	get_locality_data(curcobj,c_state,c_city,c_locality);
	get_zipcode_data(curcobj,c_state,c_city,c_locality,c_zipcode);
	curcobj.closest('.address-parent').find('.selectaddress').val(c_address);
	
	var p_state    			=   '<?php echo $userpstate; ?>';
	var p_city      		= 	'<?php echo $userpcity; ?>';
	var p_locality     		=   '<?php echo $userplocality; ?>';
	var p_address   		=   '<?php echo $userpaddress; ?>';
	var p_zipcode      		=   '<?php echo $userpzipcode; ?>';
	
	var curpobj				=	$(".form-horizontal #user_p_state");
	
	$(".form-horizontal #user_p_state option[value="+p_state+"]").prop("selected", true);
	get_city_data(curpobj,p_state,p_city);
	get_locality_data(curpobj,p_state,p_city,p_locality);
	get_zipcode_data(curpobj,p_state,p_city,p_locality,p_zipcode);
	curpobj.closest('.address-parent').find('.selectaddress').val(p_address);
	
}); 
<?php endif; ?>
</script><?php */?>
