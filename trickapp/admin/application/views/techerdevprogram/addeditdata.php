<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
$(function(){
	$("#develop_program_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y')+1; ?>"});
});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('techerDevProgramAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      devlopment program details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    devlopment program details</li>
  <li class="pull-right"><a href="<?php echo correctLink('techerDevProgramAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          devlopment program details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Title<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="develop_program_title" id="develop_program_title" value="<?php if(set_value('develop_program_title')): echo set_value('develop_program_title'); else: echo stripslashes($EDITDATA['develop_program_title']);endif; ?>" class="form-control required" placeholder="Title">
                  <?php if(form_error('develop_program_title')): ?>
                  <p for="develop_program_title" generated="true" class="error"><?php echo form_error('develop_program_title'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Date<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="develop_program_date" id="develop_program_date" value="<?php if(set_value('develop_program_date')): echo set_value('develop_program_date'); else: echo YYMMDDtoDDMMYY($EDITDATA['develop_program_date']);endif; ?>" class="form-control required" placeholder="Date">
                  <?php if(form_error('develop_program_date')): ?>
                  <p for="develop_program_date" generated="true" class="error"><?php echo form_error('develop_program_date'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Time<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('develop_program_time')): $developptime	=	set_value('develop_program_time'); elseif($EDITDATA['develop_program_time']): $developptime	=	stripslashes($EDITDATA['develop_program_time']); else: $developptime	=	''; endif; ?>
                  <select name="develop_program_time" id="develop_program_time" class="form-control required">
                  	<option value="">Select time</option>
                    <?php if($TIMEDATA <> ""): foreach($TIMEDATA as $TIMEINFO): ?>
                    	<option value="<?php echo $TIMEINFO['time_name']; ?>" <?php if($TIMEINFO['time_name'] == $developptime): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($TIMEINFO['time_name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('develop_program_time')): ?>
                  <p for="develop_program_time" generated="true" class="error"><?php echo form_error('develop_program_time'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">         
                <label class="col-lg-3 control-label">Development class Teacher (Speaker)<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('develop_program_speaker')): $developpspeaker	=	set_value('develop_program_speaker'); elseif($EDITDATA['develop_program_speaker']): $developpspeaker	=	stripslashes($EDITDATA['develop_program_speaker']); else: $developpspeaker	=	''; endif; ?>
                  <select name="develop_program_speaker" id="develop_program_speaker" class="form-control required">
                  	<option value="">Select teacher</option>
                    <?php if($TEACHERDATA <> ""): foreach($TEACHERDATA as $TEACHERINFO): 
						  $teachername	=	$TEACHERINFO['user_f_name'].' '.$TEACHERINFO['user_m_name'].' '.$TEACHERINFO['user_l_name'];
					?>
                    	<option value="<?php echo $teachername; ?>" <?php if($teachername == $developpspeaker): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($teachername); ?></option>
                    <?php endforeach; endif; ?>
                    <option value="Oteher"<?php if('Oteher' == $developpspeaker): echo 'selected="selected"'; endif; ?>>Other teacher</option>
                  </select>
                  <?php if(form_error('develop_program_speaker')): ?>
                  <p for="develop_program_speaker" generated="true" class="error"><?php echo form_error('develop_program_speaker'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group" id="other_teacher_name_div" <?php if($developpspeaker == 'Oteher'): echo 'style="display:block;"'; else: echo  'style="display:none;"'; endif; ?>>
                <label class="col-lg-3 control-label">Other teacher name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="develop_program_other_speaker" id="develop_program_other_speaker" value="<?php if(set_value('develop_program_other_speaker')): echo set_value('develop_program_other_speaker'); else: echo stripslashes($EDITDATA['develop_program_other_speaker']);endif; ?>" class="form-control required" placeholder="Other teacher name">
                  <?php if(form_error('develop_program_other_speaker')): ?>
                  <p for="develop_program_other_speaker" generated="true" class="error"><?php echo form_error('develop_program_other_speaker'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">         
                <label class="col-lg-3 control-label">Content<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('develop_program_content')): $developpcontent	=	set_value('develop_program_content'); elseif($EDITDATA['develop_program_content']): $developpcontent	=	stripslashes($EDITDATA['develop_program_content']); else: $developpcontent	=	''; endif; ?>
                  <textarea name="develop_program_content" id="develop_program_content" class=" required"><?php echo $developpcontent; ?></textarea>
                  <?php if(form_error('develop_program_content')): ?>
                  <p for="develop_program_content" generated="true" class="error"><?php echo form_error('develop_program_content'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('techerDevProgramAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
$(document).ready(function(){
	create_editor_for_textarea('develop_program_content');
});
</script>
<script>
$(document).on('change','#develop_program_speaker',function(){
	var teacherid	=	$(this).val();
	if(teacherid == 'Oteher')
		$('#other_teacher_name_div').css('display','block');
	else
		$('#other_teacher_name_div').css('display','none');
});
</script>