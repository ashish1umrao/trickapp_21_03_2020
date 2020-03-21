<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('classListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      class details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    class details</li>
  <li class="pull-right"><a href="<?php echo correctLink('classListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          class details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Class name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="class_name" id="class_name" value="<?php if(set_value('class_name')): echo set_value('class_name'); else: echo stripslashes($EDITDATA['class_name']);endif; ?>" class="form-control required" placeholder="Class name">
                  <?php if(form_error('class_name')): ?>
                  <p for="class_name" generated="true" class="error"><?php echo form_error('class_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Class short name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="class_short_name" id="class_short_name" value="<?php if(set_value('class_short_name')): echo set_value('class_short_name'); else: echo stripslashes($EDITDATA['class_short_name']);endif; ?>" class="form-control required" placeholder="Class short name">
                  <?php if(form_error('class_short_name')): ?>
                  <p for="class_short_name" generated="true" class="error"><?php echo form_error('class_short_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <?php  if($ALLSUBDATA):  ?>
               <fieldset style="border-color:#8B5C7E;">
            <legend>Subjects</legend>
              <div class="form-group">
                  
               <?php if(set_value('subject_name')): $subjectname	=	set_value('subject_name'); elseif($SUBJECTDATA): $subjectname	=	$SUBJECTDATA; else: $subjectname	=	array(); endif; ?>
               <?php if($ALLSUBDATA <> ""): $i=1; foreach($ALLSUBDATA as $ALLSUBINFO): ?>
                <div class="col-lg-3">
                  <input type="checkbox" name="subject_name[]" id="subject_name<?php echo $i; ?>" value="<?php echo $ALLSUBINFO['encrypt_id']; ?>" <?php if(in_array($ALLSUBINFO['encrypt_id'],$subjectname)): echo 'checked="checked"'; endif; ?> class="required">
                  &nbsp;<label for="subject_name<?php echo $i; ?>"><?php echo addslashes($ALLSUBINFO['subject_name']); ?></label>
                </div><?php if($i==4): echo '<br clear="all" /><br clear="all" />'; endif; ?>
               <?php $i++; endforeach; endif; ?> 
               <?php if(form_error('subject_name1')): ?>
                  <p for="subject_name1" generated="true" class="error"><?php echo form_error('subject_name1'); ?></p>
                  <?php endif; ?>
              </div>
            
               </fieldset>
            
            <?php endif;  ?>
              <hr style="background-color:#8B5C7E; height:2px;"/>
              <?php 
                if(set_value('TotalPeriod')): $TotalPeriod = set_value('TotalPeriod'); elseif($SDETAILDATA <> ""): $TotalPeriod = count($SDETAILDATA); else: $TotalPeriod = 1; endif; ?>
                <input type="hidden" name="TotalPeriod" id="TotalPeriod" value="<?php echo $TotalPeriod; ?>" />
                <input type="hidden" name="TotalPeriodCount" id="TotalPeriodCount" value="<?php echo $TotalPeriod; ?>" />
                <?php if($suberror): echo '<p generated="true" class="error">'.$suberror.'</p>'; endif; ?>
              <div class="col-md-12 col-sm-12 col-xs-12 padding-none" id="Periodlocation">
              <?php if(set_value('TotalPeriod')){ 
              for($i=1; $i<= $TotalPeriod; $i++){
              $period_id_     	= 'period_id_'.$i;
			  $period_name_     = 'period_name_'.$i;
              $duration_        = 'duration_'.$i;
              $start_time_      = 'start_time_'.$i;
              $end_time_        = 'end_time_'.$i;
              ?>
              <span><?php if($i > 1){ echo '<hr />'; } ?>
                <div class="form-group">
					<input type="hidden" name="period_id_<?php echo $i; ?>" id="period_id_<?php echo $i; ?>" value="<?php echo set_value($$period_id_); ?>" />
                  <div class="col-sm-4 col-xs-12 padd-b-10">
                      <label>Period Name<span class="required"> * </span></label>
                      <input type="text" name="period_name_<?php echo $i; ?>" id="period_name_<?php echo $i; ?>" placeholder="Period Name*" class="form-control" value="<?php echo set_value($$period_name_); ?>" />
                  </div>
                  <div class="col-sm-2 col-xs-12">
                      <label>Duration</label>
                      <input type="text" name="duration_<?php echo $i; ?>" id="duration_<?php echo $i; ?>" placeholder="Duration" class="form-control col-md-7 col-xs-12" value="<?php echo set_value($$duration_); ?>" />
                  </div>
                 <div class="col-sm-2 col-xs-12 plr-5">
                      <label>Start Time</label>
                      <input type="text" name="start_time_<?php echo $i; ?>" id="start_time_<?php echo $i; ?>" placeholder="Start Time" class="form-control col-md-7 col-xs-12" value="<?php echo set_value($$start_time_); ?>" />
                  </div>
                  <div class="col-sm-2 col-xs-12 plr-5">
                    <label>End Time</label>
                    <input type="text" name="end_time_<?php echo $i; ?>" id="end_time_<?php echo $i; ?>" placeholder="End Time" class="form-control col-md-7 col-xs-12" value="<?php echo set_value($$end_time_); ?>" />
                  </div>
                  <div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;">
                     <label>&nbsp;</label><br clear="all" />
                     <?php if($i < $TotalPeriod){ ?>
                     <a href="javascript:void(0);" class="removeMoreSubject" id="RemovePeriod_<?php echo $i; ?>" style="display:block;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current subject" /></a>
                     <a href="javascript:void(0);" class="addMoreSubject" id="AddPeriod_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more schedule" /></a>
                     <?php }else{ ?>
                     <a href="javascript:void(0);" class="removeMoreSubject" id="RemovePeriod_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current subject" /></a>
                     <a href="javascript:void(0);" class="addMoreSubject" id="AddPeriod_<?php echo $i; ?>"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more schedule" /></a>
                     <?php } ?>
                  </div>
                </div>
              </span>
            <?php }  ?>

          <?php  }elseif($SDETAILDATA <> ""){ 
              $i=1; foreach($SDETAILDATA as $SDETAILINFO){ ?>
              <span><?php if($i > 1): echo '<hr />'; endif; ?>
                <div class="form-group">
                  
                  <input type="hidden" name="period_id_<?php echo $i; ?>" id="period_id_<?php echo $i; ?>" value="<?php echo $SDETAILINFO['encrypt_id']; ?>" />
                  
                  <div class="col-sm-4 col-xs-12 padd-b-10">
                      <label>Period Name<span class="required"> * </span></label>
                      <input type="text" name="period_name_<?php echo $i; ?>" id="period_name_<?php echo $i; ?>" placeholder="Period Name*" class="form-control" value="<?php echo $SDETAILINFO['period_name']; ?>" />
                  </div>
                  <div class="col-sm-2 col-xs-12">
                      <label>Duration</label>
                      <input type="text" name="duration_<?php echo $i; ?>" id="duration_<?php echo $i; ?>" placeholder="Duration" class="form-control col-md-7 col-xs-12" value="<?php echo $SDETAILINFO['duration']; ?>" />
                  </div>
                  <div class="col-sm-2 col-xs-12 plr-5">
                     <label>Start Time</label>
                     <input type="text" name="start_time_<?php echo $i; ?>" id="start_time_<?php echo $i; ?>" placeholder="Start Time" class="form-control col-md-7 col-xs-12" value="<?php echo $SDETAILINFO['start_time']; ?>" />
                   </div>
                   <div class="col-sm-2 col-xs-12 plr-5">
                      <label>End Time</label>
                      <input type="text" name="end_time_<?php echo $i; ?>" id="end_time_<?php echo $i; ?>" placeholder="End Time" class="form-control col-md-7 col-xs-12" value="<?php echo $SDETAILINFO['end_time']; ?>" />
                   </div>
                    <div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;">
                       <label>&nbsp;</label><br clear="all" />
                       <?php if($i < $TotalPeriod): ?>
                       <a href="javascript:void(0);" class="addMoreSubject" id="AddPeriod_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Period" /></a>
                       <?php else: ?>
                       <a href="javascript:void(0);" class="addMoreSubject" id="AddPeriod_<?php echo $i; ?>"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Period" /></a>
                       <?php endif; ?>
                    </div>
                </div>
              </span>
            <?php $i++; } 
            }else{   ?>

                <span>
                <div class="form-group">
				  <input type="hidden" name="period_id_1" id="period_id_1" value="" />
                  <div class="col-sm-4 col-xs-12 padd-b-10">
                      <label>Period Name<span class="required"> * </span></label>
                      <input type="text" name="period_name_1" id="period_name_1" placeholder="Period Name*" class="form-control" value="" />
                  </div>
                  <div class="col-sm-2 col-xs-12">
                      <label>Duration</label>
                      <input type="text" name="duration_1" id="duration_1" placeholder="Duration" class="form-control col-md-7 col-xs-12" value="" />
                  </div>
                 <div class="col-sm-2 col-xs-12 plr-5">
                      <label>Start Time</label>
                      <input type="text" name="start_time_1" id="start_time_1" placeholder="Start Time" class="form-control col-md-7 col-xs-12" value="" />
                  </div>
                  <div class="col-sm-2 col-xs-12 plr-5">
                    <label>End Time</label>
                    <input type="text" name="end_time_1" id="end_time_1" placeholder="End Time" class="form-control col-md-7 col-xs-12" value="" />
                  </div>
                  <div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;">
                     <label>&nbsp;</label><br clear="all" />
                      <a href="javascript:void(0);" class="removeMoreSubject" id="RemovePeriod_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current Period" /></a>
                       <a href="javascript:void(0);" class="addMoreSubject" id="AddPeriod_<?php echo $i; ?>"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Period" /></a>
                  </div>

                </div>
              </span>

            <?php } ?>
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
      </section>
    </div>
  </div>
</div>
<script>
$(function(){ 
  var scntDiv   =   $('#currentPageForm #Periodlocation');
  var pi      =   $('#currentPageForm #Periodlocation > span').size(); 
  
  $(document).on('click', '#currentPageForm .addMoreSubject', function() { 
    if($('#currentPageForm #class_name').val() == '')
    {
      alert('Please enter class name.');
      return false;
    }
    if($('#currentPageForm #class_short_name').val() == '')
    {
      alert('Please enter class short name.');
      return false;
    }
    var i     =   parseInt($('#currentPageForm #TotalPeriodCount').val());
    i++;
    pi++;
    $('<span><hr /><div class="form-group"><input type="hidden" name="period_id_'+i+'" id="period_id_'+i+'" value="" /><div class="col-sm-4 col-xs-12 padd-b-10"><label>Period Name</label><input type="text" name="period_name_'+i+'" id="period_name_'+i+'" placeholder="Period Name" class="form-control col-md-7 col-xs-12" value="" /></div><div class="col-sm-2 col-xs-12"><label>Duration(in minute)</label><input type="text" name="duration_'+i+'" id="duration_'+i+'" placeholder="Duration" class="form-control col-md-7 col-xs-12" value="" /></div><div class="col-sm-2 col-xs-12 plr-5"><label>Start Time</label><input type="text" name="start_time_'+i+'" id="start_time_'+i+'" placeholder="Start Time" class="form-control col-md-7 col-xs-12" value="" /></div><div class="col-sm-2 col-xs-12 plr-5"><label>End Time</label><input type="text" name="end_time_'+i+'" id="end_time_'+i+'" placeholder="End Time" class="form-control col-md-7 col-xs-12" value="" /></div><div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;"><label>&nbsp;</label><br clear="all" /><a href="javascript:void(0);" class="removeMoreSubject" id="RemovePeriod_'+i+'" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current Period" /></a><a href="javascript:void(0);" class="addMoreSubject" id="AddPeriod_'+i+'"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Grade" /></a></div></div></span>').appendTo(scntDiv);
    $('#currentPageForm #TotalPeriod').val(pi);
    $('#currentPageForm #TotalPeriodCount').val(i);
    
    $(this).closest('#Periodlocation').find('a.removeMoreSubject').show();
    $(this).closest('#Periodlocation').find('a.addMoreSubject').hide();
    $('#currentPageForm #RemovePeriod_'+i).hide();
    $('#currentPageForm #AddPeriod_'+i).show();
    return false;
  });
  
  $(document).on('click', '#currentPageForm .removeMoreSubject', function() {  
    if( pi > 1 ) {
      $(this).parents('span').remove();
      pi--;
      $('#currentPageForm #TotalPeriod').val(pi);
    }
    return false;
  });
});
</script>