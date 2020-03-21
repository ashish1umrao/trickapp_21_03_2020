<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('classListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      class details</a></li>
  <li class="active">
    Manage Syllabus</li>
  <li class="pull-right"><a href="<?php echo correctLink('classListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
     <?php echo $class_name ;?>   Syllabus Details</span> </header>
          <?php  if($SUBDDATA):  ?>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
          
               <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
              
                <div class="col-lg-12">
                    <?php if($SUBDDATA): foreach ($SUBDDATA as $SB) :  ?>
              <div class="btn-group"> <a class="btn btn-success dropdown-toggle"  href="{FULL_SITE_URL}{CURRENT_CLASS}/syllabus/<?=$class_id?>/<?=$SB['subject_id']?>"><?php echo $SB['subject_name']; ?> </a>
              </div>   <?php   endforeach; endif;?>
                  <?php if(form_error('class_name')): ?>
                  <p for="class_name" generated="true" class="error"><?php echo form_error('class_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
             
            <h4 style="color:#8B5C7E;text-align: center;" ><?=$subject_name?>'s Syllabus</h4>
              <hr style="background-color:#8B5C7E; height:2px;"/>
                  <div class="form-group">         
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-8">
                  <?php if(set_value('syllabus')): $developpcontent	=	set_value('syllabus'); elseif($EDITDATA['syllabus']): $developpcontent	=	stripslashes($EDITDATA['syllabus']); else: $developpcontent	=	''; endif; ?>
                  <textarea name="syllabus" id="syllabus" class=" required"><?php echo $developpcontent; ?></textarea>
                  <?php if(form_error('syllabus')): ?>
                  <p for="syllabus" generated="true" class="error"><?php echo form_error('syllabus'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
               
              
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('classListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
          <?php
          else: ?>
              <p style="text-align:center;padding: 30px;">No subject Found in Class.</p>
         <?php    endif; ?>
      </section>
    </div>
  </div>
</div>



<script>
$(document).ready(function(){
	create_editor_for_textarea('syllabus');
});
</script>