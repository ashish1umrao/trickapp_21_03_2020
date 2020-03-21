<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
$(function(){
	 $("#from_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,minDate: 0,yearRange: "2017:2050"});
  $("#to_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,minDate: 0,yearRange: "2017:2050"});
 
});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('EventlistAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      event details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    event details</li>
  <li class="pull-right"><a href="<?php echo correctLink('EventlistAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          event details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              
                 <div class="form-group">
                <label class="col-lg-3 control-label">Class name<span class="required">*</span></label>
                <div class="col-lg-6"> 
                  <?php if(set_value('class_id')): $classid	=	set_value('class_id'); elseif(['class_id']): $classid	=	stripslashes($EDITDATA['class_id']); else: $classid	=	''; endif; ?>
              <?php  if($classid=='All'): $select1 = 'selected="selected"'; else: $select1 = ''; endif;  ?> 
                    <select name="class_id" id="class_id" class="form-control required">
                  	<option value="">Select class name</option>
                        	
                        <option value="All" <?php echo $select1 ;  ?>  >All</option>
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
                    
                    <?php if(set_value('section_id')): $sectionid = set_value('section_id'); elseif($EDITDATA['section_id']): $sectionid  = stripslashes($EDITDATA['section_id']); else: $sectionid = ''; endif; ?>
                    <select name="section_id" id="section_id" class="form-control">
                      <option value="">Select Section</option>
                    </select>
                  </div>
            </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Message Title<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="purpose" id="purpose" value="<?php if(set_value('purpose')): echo set_value('purpose'); else: echo stripslashes($EDITDATA['purpose']);endif; ?>" class="form-control required" placeholder="Title">
                  <?php if(form_error('purpose')): ?>
                  <p for="purpose" generated="true" class="error"><?php echo form_error('purpose'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
               <div class="form-group">
                <label class="col-lg-3 control-label">Venue</label>
                <div class="col-lg-6">
                  <input type="text" name="venue" id="purpose" value="<?php if(set_value('venue')): echo set_value('venue'); else: echo stripslashes($EDITDATA['venue']);endif; ?>" class="form-control " placeholder="venue">
                  <?php if(form_error('venue')): ?>
                  <p for="venue" generated="true" class="error"><?php echo form_error('venue'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <div class="form-group">
             <?php $vislist =  explode(',',$EDITDATA['visibility']);

             
             ?>
        
                    <label class="col-lg-3 control-label" >Visiblity<span class="required"> * </span></label>
                      <div class="col-lg-6">
                    <select name="visibility[]" id="visibility" class="form-control col-md-7 col-xs-12 required" multiple="multiple">
                       <option value="All" <?php foreach($vislist as $visl): if($visl =='All'): echo "selected"; endif; endforeach; ?>>All</option>
                       <option value="Parent" <?php foreach($vislist as $visl): if($visl =='Parent'): echo "selected"; endif; endforeach; ?>>Parent</option>
                       <option value="Teacher" <?php foreach($vislist as $visl): if($visl =='Teacher'): echo "selected"; endif; endforeach; ?>>Teacher</option>
                         <option value="Non_teaching_staff" <?php foreach($vislist as $visl): if($visl =='Non_teaching_staff'): echo "selected"; endif; endforeach; ?>>Non Teaching Staff</option>
                       <!--<option value="Principal" <?php //foreach($vislist as $visl): if($visl =='Principal'): echo "selected"; endif; endforeach; ?>>Principal</option>-->
                    </select>
                 </div>
                </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">From Date<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="from_date" id="from_date" value="<?php if(set_value('from_date')): echo set_value('from_date'); else: echo YYMMDDtoDDMMYY($EDITDATA['from_date']);endif; ?>" class="form-control required" placeholder="From Date">
                  <?php if(form_error('from_date')): ?>
                  <p for="from_date" generated="true" class="error"><?php echo form_error('from_date'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            
                <div class="form-group">
                <label class="col-lg-3 control-label">To Date<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="to_date" id="to_date" value="<?php if(set_value('to_date')): echo set_value('to_date'); else: echo YYMMDDtoDDMMYY($EDITDATA['to_date']);endif; ?>" class="form-control required" placeholder="To Date">
                  <?php if(form_error('to_date')): ?>
                  <p for="to_date" generated="true" class="error"><?php echo form_error('to_date'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Event Time<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('time')): $eventTime	=	set_value('time'); elseif($EDITDATA['time']): $eventTime	=	stripslashes($EDITDATA['time']); else: $eventTime	=	''; endif; ?>
                  <select name="time" id="time" class="form-control required">
                  	<option value="">Select time</option>
                    <?php if($TIMEDATA <> ""): foreach($TIMEDATA as $TIMEINFO): ?>
                    	<option value="<?php echo $TIMEINFO['time_name']; ?>" <?php if($TIMEINFO['time_name'] == $eventTime): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($TIMEINFO['time_name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('time')): ?>
                  <p for="time" generated="true" class="error"><?php echo form_error('time'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
           <div class="form-group">
                <label class="col-lg-3 control-label">Custom Message</label>
                <div class="col-lg-6">
                  <input type="text" name="message" id="purpose" value="<?php if(set_value('message')): echo set_value('message'); else: echo stripslashes($EDITDATA['message']);endif; ?>" class="form-control" placeholder="Message to send event attendant">
                  <?php if(form_error('message')): ?>
                  <p for="message" generated="true" class="error"><?php echo form_error('message'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
         
              <div class="form-group">         
                <label class="col-lg-3 control-label">About Event</label>
                <div class="col-lg-8">
                  <?php if(set_value('about_event')): $aboutcontent	=	set_value('about_event'); elseif($EDITDATA['about_event']): $aboutcontent	=	stripslashes($EDITDATA['about_event']); else: $aboutcontent	=	''; endif; ?>
                  <textarea name="about_event" id="about_event" ><?php echo $aboutcontent; ?></textarea>
                  <?php if(form_error('about_event')): ?>
                  <p for="about_event" generated="true" class="error"><?php echo form_error('about_event'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('EventlistAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
	create_editor_for_textarea('about_event');
});
</script>
<script>

$(document).on('change','#currentPageForm #class_id',function(){
	
	var class_id		=	$(this).val();
	var section_id		=	'';
	$.ajax({
		type: 'post',
		 url: FULLSITEURL+CURRENTCLASS+'/get_section_name',
		data: {csrf_api_key:csrf_api_value,classid:class_id,sectionid:section_id},
	 success: function(data){
	 		$('#currentPageForm #section_id').html(data);
	 	}
	});
});
<?php if($EDITDATA <> "" || $_POST): ?>
	
	var class_id	=	'<?php echo $classid; ?>';
	var section_id	=	'<?php echo $sectionid; ?>';
	
	$.ajax({
		type: 'post',
		 url: FULLSITEURL+CURRENTCLASS+'/get_section_name',
		data: {csrf_api_key:csrf_api_value,classid:class_id,sectionid:section_id},
	 success: function(data){
	 		$('#currentPageForm #section_id').html(data);
	 	}
	});
<?php endif; ?>
</script>