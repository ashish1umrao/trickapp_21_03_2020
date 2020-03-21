<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentListAdminDatastudentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      student details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    student health details</li>
  <li class="pull-right"><a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
    <ul class="nav nav-tabs blue_tab">
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata/<?php echo $studentId; ?>">Personal</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditparents/<?php echo $studentId; ?>">Parents</a></li>
      <li ><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditaddress/<?php echo $studentId; ?>">Address</a></li>
      <li   class="active"><a href="javascript:void(0);">Health</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditqrcode/<?php echo $studentId; ?>">Student QRCode</a></li>
    </ul>
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          student health details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
         
            </fieldset>
            <br clear="all" />
            <fieldset>
            <legend> Health details</legend>
            <div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Blood Group<span class="required">*</span></label>
                      <div class="col-lg-8">
                      <?php if(set_value('student_blood_group')): $studentblood  = set_value('student_blood_group'); elseif($EDITDATA['student_blood_group']): $studentblood  = stripslashes($EDITDATA['student_blood_group']); else: $studentocity  = ''; endif; ?>
                      <input type="text"     name="student_blood_group" id="student_blood_group" value="<?php echo $studentblood; ?>" class="form-control required" placeholder="Blood Group">
                      <?php if(form_error('student_blood_group')): ?>
                      <p for="student_blood_group" generated="true" class="error"><?php echo form_error('student_blood_group'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                  
                      <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Height(cm)</label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_height')): $studentheight = set_value('student_height'); elseif($EDITDATA['student_height']): $studentheight = stripslashes($EDITDATA['student_height']); else: $studentheight  = ''; endif; ?>
                      <input type="number"  min="0" name="student_height" id="student_height" value="<?php echo $studentheight; ?>" class="form-control required" placeholder="Height">
                      <?php if(form_error('student_height')): ?>
                      <p for="student_height" generated="true" class="error"><?php echo form_error('student_height'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                  
                  
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">weight(kg)</label>
                  <div class="col-lg-8">
                      <?php if(set_value('student_weight')): $studentweight  = set_value('student_weight'); elseif($EDITDATA['student_weight']): $studentweight  = stripslashes($EDITDATA['student_weight']); else: $studentweight  = ''; endif; ?>
                      <input type="number"  min="0" name="student_weight" id="student_weight" value="<?php echo $studentweight; ?>" class="form-control " placeholder="Weight">
                      <?php if(form_error('student_weight')): ?>
                      <p for="student_weight" generated="true" class="error"><?php echo form_error('student_weight'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Allergy From</label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_allergy_from')): $studentallergy  = set_value('student_allergy_from'); elseif($EDITDATA['student_allergy_from']): $studentallergy  = stripslashes($EDITDATA['student_allergy_from']); else: $studentallergy  = ''; endif; ?>
                      <input type="text" name="student_allergy_from" id="student_allergy_from" value="<?php echo $studentallergy; ?>" class="form-control " placeholder="Allergy From">
                      <?php if(form_error('student_allergy_from')): ?>
                      <p for="student_allergy_from" generated="true" class="error"><?php echo form_error('student_allergy_from'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Last Medical Checkup</label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_l_medical_checkup')): $studentcheckup  = set_value('student_l_medical_checkup'); elseif($EDITDATA['student_l_medical_checkup']): $studentcheckup  = stripslashes($EDITDATA['student_l_medical_checkup']); else: $studentcheckup  = ''; endif; ?>
                      <input type="text" name="student_l_medical_checkup" id="student_l_medical_checkup" value="<?php echo $studentcheckup; ?>" class="form-control " placeholder="Medical Checkup">
                      <?php if(form_error('student_l_medical_checkup')): ?>
                      <p for="student_l_medical_checkup" generated="true" class="error"><?php echo form_error('student_l_medical_checkup'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                  <div class="col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-4 control-label">Special Notes</label>
                    <div class="col-lg-8">
                      <?php if(set_value('student_special_notes')): $studentnotes  = set_value('student_special_notes'); elseif($EDITDATA['student_special_notes']): $studentnotes = stripslashes($EDITDATA['student_special_notes']); else: $studentnotes  = ''; endif; ?>
                      <input type="text" name="student_special_notes" id="student_o_zipcode" value="<?php echo $studentnotes; ?>" class="form-control " placeholder="Special Notes">
                      <?php if(form_error('student_special_notes')): ?>
                      <p for="student_special_notes" generated="true" class="error"><?php echo form_error('student_special_notes'); ?></p>
                      <?php endif; ?>
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
<script> // same as correspondence health
$(document).on('click','.form-horizontal #same_health', function(){
  var curobj    = $(this);
  if($(this).is(":checked")) {
    var state       =   $('.form-horizontal #student_c_state').val();
    var city        =   $('.form-horizontal #student_c_city').val();
    var locality    =   $('.form-horizontal #student_c_locality').val();
    var health     =   $('.form-horizontal #student_c_health').val();
    var zip         =   $('.form-horizontal #student_c_zipcode').val();
    
    $(".form-horizontal #student_p_state option[value='"+state+"']").prop("selected", true);
    curobj.closest('.health-parent').find('.selectcity').val(city);
     curobj.closest('.health-parent').find('.selectlocality').val(locality);
    curobj.closest('.health-parent').find('.selecthealth').val(health);
    curobj.closest('.health-parent').find('.selectzip').val(zip);
  
  } else {
  <?php if($EDITDATA <> "" || $_POST): ?> 
  
    var state       =   '<?php echo $studentpstate; ?>';
    var city        =   '<?php echo $studentpcity; ?>';
    var locality    =   '<?php echo $studentplocality; ?>';
    var health     =   '<?php echo $studentphealth; ?>';
    var zip         =   '<?php echo $studentpzipcode; ?>';
    
    $(".form-horizontal #student_p_state option[value='"+state+"']").prop("selected", true);
    curobj.closest('.health-parent').find('.selectcity').val(city);
    curobj.closest('.health-parent').find('.selectlocality').val(locality);
    curobj.closest('.health-parent').find('.selecthealth').val(health);
    curobj.closest('.health-parent').find('.selectzip').val(zip);
  
  <?php else: ?>
      
    $(".form-horizontal #student_p_state option:first").attr('selected','selected');
    curobj.closest('.health-parent').find('.selectcity').val('');
    curobj.closest('.health-parent').find('.selectlocality').val('');
    curobj.closest('.health-parent').find('.selecthealth').val('');
    curobj.closest('.health-parent').find('.selectzip').val('');
  
  <?php endif; ?>
  }
});
</script>
