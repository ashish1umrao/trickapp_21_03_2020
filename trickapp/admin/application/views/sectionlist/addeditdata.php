<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('sectionListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      section details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    section details</li>
  <li class="pull-right"><a href="<?php echo correctLink('sectionListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          section details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Class name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('class_id')): $classid	=	set_value('class_id'); elseif($EDITDATA['class_id']): $classid	=	stripslashes($EDITDATA['class_id']); else: $classid	=	''; endif; ?>
                  <select name="class_id" id="class_id" class="form-control required">
                  	<option value="">Select class name</option>
                    <?php if($CLASSDATA <> ""): foreach($CLASSDATA as $CLASSINFO): ?>
                    	<option value="<?php echo $CLASSINFO['encrypt_id']; ?>" <?php if($CLASSINFO['encrypt_id'] == $classid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CLASSINFO['class_name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('class_id')): ?>
                  <p for="class_id" generated="true" class="error"><?php echo form_error('class_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Section name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="class_section_name" id="class_section_name" value="<?php if(set_value('class_section_name')): echo set_value('class_section_name'); else: echo stripslashes($EDITDATA['class_section_name']);endif; ?>" class="form-control required" placeholder="Section name">
                  <?php if(form_error('class_section_name')): ?>
                  <p for="class_section_name" generated="true" class="error"><?php echo form_error('class_section_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            
            
              <div class="form-group">
                <label class="col-lg-3 control-label">Section short name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="class_section_short_name" id="class_section_short_name" value="<?php if(set_value('class_section_short_name')): echo set_value('class_section_short_name'); else: echo stripslashes($EDITDATA['class_section_short_name']);endif; ?>" class="form-control required" placeholder="Section short name">
                  <?php if(form_error('class_section_short_name')): ?>
                  <p for="class_section_short_name" generated="true" class="error"><?php echo form_error('class_section_short_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">         
                <label class="col-lg-3 control-label">Class teacher<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('class_teacher_id')): $classteacherid	=	set_value('class_teacher_id'); elseif($EDITDATA['class_teacher_id']): $classteacherid	=	stripslashes($EDITDATA['class_teacher_id']); else: $classteacherid	=	''; endif; ?>
                  <select name="class_teacher_id" id="class_teacher_id" class="form-control required">
                  	<option value="">Select teacher</option>
                    <?php if($TEACHERDATA <> ""): foreach($TEACHERDATA as $TEACHERINFO): 
          						  $teachername	=	$TEACHERINFO['user_f_name'].' '.$TEACHERINFO['user_m_name'].' '.$TEACHERINFO['user_l_name'];
          					?>
                    	<option value="<?php echo $TEACHERINFO['encrypt_id']; ?>" <?php if($TEACHERINFO['encrypt_id'] == $classteacherid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($teachername); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('class_teacher_id')): ?>
                  <p for="class_teacher_id" generated="true" class="error"><?php echo form_error('class_teacher_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('sectionListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>