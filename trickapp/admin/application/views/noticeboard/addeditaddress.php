<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentListAdminDatastudentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      student details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    student address details</li>
  <li class="pull-right"><a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
    <ul class="nav nav-tabs blue_tab">
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata/<?php echo $studentId; ?>">Personal</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditparents/<?php echo $studentId; ?>">Parents</a></li>
      <li class="active"><a href="javascript:void(0);">Address</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addedithealth/<?php echo $studentId; ?>">Health</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addedittransport/<?php echo $studentId; ?>">Transport</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditqrcode/<?php echo $studentId; ?>">Student QRCode</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdoc/<?php echo $studentId; ?>">Upload Documents</a></li>
    </ul>
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
               
          student address details</span> </header>
            <?php 
       echo   $studentDetails ;  ?>
        <div class="panel-body">
            <div class="pull-right">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
            <fieldset>
            <legend>Correspondence address</legend>
            <div class="address-parent">
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">State<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_c_state')): $studentcstate  = set_value('student_c_state'); elseif($EDITDATA['student_c_state']): $studentcstate = $EDITDATA['student_c_state']; else: $studentcstate  = ''; endif; ?>
                      <select name="student_c_state" id="student_c_state" class="form-control selectstate required">
                        <option value="">Select state</option>
                        <?php if($STATEDATA <> ""): foreach($STATEDATA as $STATEINFO): ?>
                        <option value="<?php echo $STATEINFO['state']; ?>" <?php if($STATEINFO['state'] == $studentcstate): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($STATEINFO['state']); ?></option>
                        <?php endforeach; endif; ?>
                      </select>
                      <?php if(form_error('student_c_state')): ?>
                      <p for="student_c_state" generated="true" class="error"><?php echo form_error('student_c_state'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">City<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_c_city')): $studentccity  = set_value('student_c_city'); elseif($EDITDATA['student_c_city']): $studentccity  = stripslashes($EDITDATA['student_c_city']); else: $studentccity  = ''; endif; ?>
                      <input type="text" name="student_c_city" id="student_c_city" value="<?php echo $studentccity; ?>" class="form-control selectcity required" placeholder="City">
                      <?php if(form_error('student_c_city')): ?>
                      <p for="student_c_city" generated="true" class="error"><?php echo form_error('student_c_city'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Locality<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_c_locality')): $studentclocality  = set_value('student_c_locality'); elseif($EDITDATA['student_c_locality']): $studentclocality  = stripslashes($EDITDATA['student_c_locality']); else: $studentclocality  = ''; endif; ?>
                      <input type="text" name="student_c_locality" id="student_c_locality" value="<?php echo $studentclocality; ?>" class="form-control selectlocality required" placeholder="Locality">
                      <?php if(form_error('student_c_locality')): ?>
                      <p for="student_c_locality" generated="true" class="error"><?php echo form_error('student_c_locality'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Address<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_c_address')): $studentcaddress  = set_value('student_c_address'); elseif($EDITDATA['student_c_address']): $studentcaddress = stripslashes($EDITDATA['student_c_address']); else:  $studentcaddress = ''; endif; ?>
                      <input type="text" name="student_c_address" id="student_c_address" value="<?php echo $studentcaddress; ?>" class="form-control selectaddress required" placeholder="Address">
                      <?php if(form_error('student_c_address')): ?>
                      <p for="student_c_address" generated="true" class="error"><?php echo form_error('student_c_address'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Zip code<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_c_zipcode')): $studentczipcode  = set_value('student_c_zipcode'); elseif($EDITDATA['student_c_zipcode']): $studentczipcode = stripslashes($EDITDATA['student_c_zipcode']); else: $studentczipcode  = ''; endif; ?>
                      <input type="text" name="student_c_zipcode" id="student_c_zipcode" value="<?php echo $studentczipcode; ?>" class="form-control selectzip required" placeholder="Zip code">
                      <?php if(form_error('student_c_zipcode')): ?>
                      <p for="student_c_zipcode" generated="true" class="error"><?php echo form_error('student_c_zipcode'); ?></p>
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
                      <?php if(set_value('same_address')): $sameaddress = set_value('same_address'); elseif($EDITDATA['same_address']): $sameaddress  = $EDITDATA['same_address']; else: $sameaddress = ''; endif; ?>
                      <input type="checkbox" name="same_address" id="same_address" value="Y" <?php if($sameaddress == 'Y'): echo 'checked="checked"'; endif; ?>>
                      <label for="same_address">Same as correspondence address</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">State<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_p_state')): $studentpstate  = set_value('student_p_state'); elseif($EDITDATA['student_p_state']): $studentpstate = $EDITDATA['student_p_state']; else: $studentpstate  = ''; endif; ?>
                      <select name="student_p_state" id="student_p_state" class="form-control selectstate required">
                        <option value="">Select state</option>
                        <?php if($STATEDATA <> ""): foreach($STATEDATA as $STATEINFO): ?>
                        <option value="<?php echo $STATEINFO['state']; ?>" <?php if($STATEINFO['state'] == $studentpstate): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($STATEINFO['state']); ?></option>
                        <?php endforeach; endif; ?>
                      </select>
                      <?php if(form_error('student_p_state')): ?>
                      <p for="student_p_state" generated="true" class="error"><?php echo form_error('student_p_state'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">City<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_p_city')): $studentpcity  = set_value('student_p_city'); elseif($EDITDATA['student_p_city']): $studentpcity  = stripslashes($EDITDATA['student_p_city']); else: $studentpcity  = ''; endif; ?>
                      <input type="text" name="student_p_city" id="student_p_city" value="<?php echo $studentpcity; ?>" class="form-control selectcity required" placeholder="City">
                      <?php if(form_error('student_p_city')): ?>
                      <p for="student_p_city" generated="true" class="error"><?php echo form_error('student_p_city'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Locality<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_p_locality')): $studentplocality  = set_value('student_p_locality'); elseif($EDITDATA['student_p_locality']): $studentplocality  = stripslashes($EDITDATA['student_p_locality']); else: $studentplocality  = ''; endif; ?>
                      <input type="text" name="student_p_locality" id="student_p_locality" value="<?php echo $studentplocality; ?>" class="form-control selectlocality required" placeholder="Locality">
                      <?php if(form_error('student_p_locality')): ?>
                      <p for="student_p_locality" generated="true" class="error"><?php echo form_error('student_p_locality'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Address<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_p_address')): $studentpaddress  = set_value('student_p_address'); elseif($EDITDATA['student_p_address']): $studentpaddress = stripslashes($EDITDATA['student_p_address']); else: $studentpaddress  = ''; endif; ?>
                      <input type="text" name="student_p_address" id="student_p_address" value="<?php echo $studentpaddress; ?>" class="form-control selectaddress required" placeholder="Address">
                      <?php if(form_error('student_p_address')): ?>
                      <p for="student_p_address" generated="true" class="error"><?php echo form_error('student_p_address'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Zip code<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_p_zipcode')): $studentpzipcode  = set_value('student_p_zipcode'); elseif($EDITDATA['student_p_zipcode']): $studentpzipcode = stripslashes($EDITDATA['student_p_zipcode']); else: $studentpzipcode  = ''; endif; ?>
                      <input type="text" name="student_p_zipcode" id="student_p_zipcode" value="<?php echo $studentpzipcode; ?>" class="form-control selectzip required" placeholder="Zip code">
                      <?php if(form_error('student_p_zipcode')): ?>
                      <p for="student_p_zipcode" generated="true" class="error"><?php echo form_error('student_p_zipcode'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </fieldset>
            <br clear="all" />
            <fieldset>
            <legend>Office address</legend>
            <div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Parent's Type<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('o_address_type')): $oaddresstype  = set_value('o_address_type'); elseif($EDITDATA['o_address_type']): $oaddresstype = $EDITDATA['o_address_type']; else: $oaddresstype  = ''; endif; ?>
                      <select name="o_address_type" id="o_address_type" class="form-control required">
                        <option value="">Select state</option>
                        <option value="Father" <?php if($oaddresstype == 'Father'): echo 'selected="selected"'; endif; ?>>Father</option>
                        <option value="Mother" <?php if($oaddresstype == 'Mother'): echo 'selected="selected"'; endif; ?>>Mother</option>
                      </select>
                      <?php if(form_error('o_address_type')): ?>
                      <p for="o_address_type" generated="true" class="error"><?php echo form_error('o_address_type'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">State<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_o_state')): $studentostate  = set_value('student_o_state'); elseif($EDITDATA['student_o_state']): $studentostate = $EDITDATA['student_o_state']; else: $studentostate  = ''; endif; ?>
                      <select name="student_o_state" id="student_o_state" class="form-control required">
                        <option value="">Select state</option>
                        <?php if($STATEDATA <> ""): foreach($STATEDATA as $STATEINFO): ?>
                        <option value="<?php echo $STATEINFO['state']; ?>" <?php if($STATEINFO['state'] == $studentostate): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($STATEINFO['state']); ?></option>
                        <?php endforeach; endif; ?>
                      </select>
                      <?php if(form_error('student_o_state')): ?>
                      <p for="student_o_state" generated="true" class="error"><?php echo form_error('student_o_state'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">City<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_o_city')): $studentocity  = set_value('student_o_city'); elseif($EDITDATA['student_o_city']): $studentocity  = stripslashes($EDITDATA['student_o_city']); else: $studentocity  = ''; endif; ?>
                      <input type="text" name="student_o_city" id="student_o_city" value="<?php echo $studentocity; ?>" class="form-control required" placeholder="City">
                      <?php if(form_error('student_o_city')): ?>
                      <p for="student_o_city" generated="true" class="error"><?php echo form_error('student_o_city'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Locality<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_o_locality')): $studentolocality  = set_value('student_o_locality'); elseif($EDITDATA['student_o_locality']): $studentolocality  = stripslashes($EDITDATA['student_o_locality']); else: $studentolocality  = ''; endif; ?>
                      <input type="text" name="student_o_locality" id="student_o_locality" value="<?php echo $studentolocality; ?>" class="form-control required" placeholder="Locality">
                      <?php if(form_error('student_o_locality')): ?>
                      <p for="student_o_locality" generated="true" class="error"><?php echo form_error('student_o_locality'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Address<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_o_address')): $studentoaddress  = set_value('student_o_address'); elseif($EDITDATA['student_o_address']): $studentoaddress = stripslashes($EDITDATA['student_o_address']); else: $studentoaddress  = ''; endif; ?>
                      <input type="text" name="student_o_address" id="student_o_address" value="<?php echo $studentoaddress; ?>" class="form-control required" placeholder="Address">
                      <?php if(form_error('student_o_address')): ?>
                      <p for="student_o_address" generated="true" class="error"><?php echo form_error('student_o_address'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Zip code<span class="required">*</span></label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_o_zipcode')): $studentozipcode  = set_value('student_o_zipcode'); elseif($EDITDATA['student_o_zipcode']): $studentozipcode = stripslashes($EDITDATA['student_o_zipcode']); else: $studentozipcode  = ''; endif; ?>
                      <input type="text" name="student_o_zipcode" id="student_o_zipcode" value="<?php echo $studentozipcode; ?>" class="form-control required" placeholder="Zip code">
                      <?php if(form_error('student_o_zipcode')): ?>
                      <p for="student_o_zipcode" generated="true" class="error"><?php echo form_error('student_o_zipcode'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </fieldset>
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
<script> // same as correspondence address
$(document).on('click','.form-horizontal #same_address', function(){
  var curobj    = $(this);
  if($(this).is(":checked")) {
    var state       =   $('.form-horizontal #student_c_state').val();
    var city        =   $('.form-horizontal #student_c_city').val();
    var locality    =   $('.form-horizontal #student_c_locality').val();
    var address     =   $('.form-horizontal #student_c_address').val();
    var zip         =   $('.form-horizontal #student_c_zipcode').val();
    
    $(".form-horizontal #student_p_state option[value='"+state+"']").prop("selected", true);
    curobj.closest('.address-parent').find('.selectcity').val(city);
     curobj.closest('.address-parent').find('.selectlocality').val(locality);
    curobj.closest('.address-parent').find('.selectaddress').val(address);
    curobj.closest('.address-parent').find('.selectzip').val(zip);
  
  } else {
  <?php if($EDITDATA <> "" || $_POST): ?> 
  
    var state       =   '<?php echo $studentpstate; ?>';
    var city        =   '<?php echo $studentpcity; ?>';
    var locality    =   '<?php echo $studentplocality; ?>';
    var address     =   '<?php echo $studentpaddress; ?>';
    var zip         =   '<?php echo $studentpzipcode; ?>';
    
    $(".form-horizontal #student_p_state option[value='"+state+"']").prop("selected", true);
    curobj.closest('.address-parent').find('.selectcity').val(city);
    curobj.closest('.address-parent').find('.selectlocality').val(locality);
    curobj.closest('.address-parent').find('.selectaddress').val(address);
    curobj.closest('.address-parent').find('.selectzip').val(zip);
  
  <?php else: ?>
      
    $(".form-horizontal #student_p_state option:first").attr('selected','selected');
    curobj.closest('.address-parent').find('.selectcity').val('');
    curobj.closest('.address-parent').find('.selectlocality').val('');
    curobj.closest('.address-parent').find('.selectaddress').val('');
    curobj.closest('.address-parent').find('.selectzip').val('');
  
  <?php endif; ?>
  }
});
</script>
