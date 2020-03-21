<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>



<script>
$(function(){
	$("#student_admission_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
	$("#student_relieving_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_dob").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      noticeboard details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    noticeboard details</li>
  <li class="pull-right"><a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">

  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        
        <header class="panel-heading"> <span class="tools pull-left">
                
              
          <?=$EDITDATA?'Edit':'Add'?>
                noticeboard details</span> </header>
       <?php if($EDITDATA):
       echo   $studentDetails ; endif; ?>
        
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['student_qunique_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
            <fieldset>
            <legend>Noticeboard details</legend>
            <div class="col-lg-12 class-parent">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Class name<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('class_id')): $classid = set_value('class_id'); else: $classid  = $EDITDATA['class_id'];  endif; ?>
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
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Section name<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('section_id')): $sectionid = set_value('section_id'); else: $sectionid  = $EDITDATA['section_id'];  endif; ?>
                    <select name="section_id" id="section_id" class="form-control required">
                      <option value="">Select section name</option>
                    </select>
                    <?php if(form_error('section_id')): ?>
                    <p for="section_id" generated="true" class="error"><?php echo form_error('section_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
      
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Visibility<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('class_id')): $classid = set_value('class_id'); else: $classid  = $EDITDATA['class_id'];  endif; ?>
                    <select name="visibility" id="visibility" class="form-control required">
                      <option value="">Select visibility mode</option>                     
                      <option value="All" <?php if($CLASSINFO['encrypt_id'] == 'All'): echo 'selected="selected"'; endif; ?>>All                        
                      </option>
                       <option value="Teacher" <?php if($CLASSINFO['encrypt_id'] == 'Teacher'): echo 'selected="selected"'; endif; ?>>Teacher                        
                      </option>
                       <option value="Parent" <?php if($CLASSINFO['encrypt_id'] == 'Parent'): echo 'selected="selected"'; endif; ?>>Parent                        
                      </option>                    
                    </select>
                    <?php if(form_error('class_id')): ?>
                    <p for="class_id" generated="true" class="error"><?php echo form_error('class_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Picture<span class="required">*</span></label>
                  <div class="col-lg-8">
                  <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                    <input type="text" id="uploadimage0" name="uploadimage0" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($EDITDATA['student_image']); endif; ?>" class="browseimageclass" />
                    <br clear="all">
                    <?php if(form_error('uploadimage0')): ?>
                    <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label>
                    <?php endif; ?>
                    <label id="uploadstatus0" class="error"></label>
                    <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                    <span id="uploadphoto0" style="float:left;">
                    <?php if(set_value('uploadimage0')):?>
                    <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php elseif($EDITDATA['student_image']):?>
                    <img src="<?php echo stripslashes($EDITDATA['student_image'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($EDITDATA['student_image'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php endif; ?>
                    </span> 
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <label>Message</label>
              <?php if(set_value('message')): $newsdescription = set_value('message'); elseif($EDITDATA['message']): $newsdescription = stripslashes($EDITDATA['message']); else: $newsdescription = ''; endif; ?>
                  <textarea name="message" id="message" ><?php echo $newsdescription; ?></textarea>
                  <?php if(form_error('message')): ?>
                  <p for="message" generated="true" class="error"><?php echo form_error('message'); ?></p>
                  <?php endif; ?>
            </div>
          </fieldset>
          <br clear="all" />
           
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
<script>
$(document).on('change','#class_id',function(){
  var curobj      =   $(this);
  var classid     =   $(this).val();
  var sectionid   =   '';
  get_section_data(curobj,classid,sectionid);
});
</script>
<script>
<?php if($EDITDATA <> "" || $_POST): ?> 
$(document).ready(function(){ 
  var curobj      =   $('#class_id');
  var classid     =   '<?php echo $classid; ?>';
  var sectionid   =   '<?php echo $sectionid; ?>';
  get_section_data(curobj,classid,sectionid);
});
<?php endif; ?>
</script> 
<script>
$(function(){
  UploadImage('0');
});
</script>
<script>
$(document).ready(function(){
  create_editor_for_textarea('message');
});
</script>