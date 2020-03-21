<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>



<script>
$(function(){
	$("#class_area").hide();
	$("#student_admission_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
	$("#student_relieving_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_dob").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      Assessment details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    Assessment details</li>
</ol>
{message}
<div class="form-w3layouts">
   
   <ul class="nav nav-tabs blue_tab">
      <li class="active"><a href="javascript:void(0);">Set Assessment</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditmarkdata">Enter Assessment Mark</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditmarkdataviewlist">Mark List</a></li>
    </ul>
 
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        
        <header class="panel-heading"> <span class="tools pull-left">
                
              
          <?=$EDITDATA?'Edit':'Add'?>
                Assessment details</span> </header>
       <?php if($EDITDATA):
       echo   $studentDetails ; endif; ?>
        
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['student_qunique_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
            <fieldset>
            <legend>Assessment Details</legend>
            <div class="col-lg-12 class-parent">
			<div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Assessment Type <span class="required">*</span></label>
                  <div class="col-lg-8">
                    <select name="common_to_all" id="common_to_all" class="form-control required" onchange="return getClassShow();">
                      <option value="">Please Select</option>
                      <option value="Y">Common To All</option>
					  <option value="N">Select Section</option>
                    </select>
                    <?php if(form_error('common_to_all')): ?>
                    <p for="common_to_all" generated="true" class="error"><?php echo form_error('common_to_all'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
			  <span id="class_area">
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
			 </span>
			   <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Assessment Name <span class="required">*</span></label>
                  <div class="col-lg-8">
                   <input type="text" name="name" autocomplete="off" id="name" value="<?php if(set_value('name')): echo set_value('name'); else: echo $EDITDATA['name']; endif; ?>" class="form-control required" placeholder="Assessment Name">
                  <?php if(form_error('name')): ?>
                  <p for="name" generated="true" class="error"><?php echo form_error('name'); ?></p>
                  <?php endif; ?>
                  </div>
                </div>
              </div>
			  
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Max.Mark <span class="required">*</span></label>
                  <div class="col-lg-8">
                   <input type="text" name="max_mark" autocomplete="off" id="max_mark" value="<?php if(set_value('max_mark')): echo set_value('max_mark'); else: echo $EDITDATA['max_mark']; endif; ?>" class="form-control required" placeholder="Max.Mark">
                  <?php if(form_error('max_mark')): ?>
                  <p for="max_mark" generated="true" class="error"><?php echo form_error('max_mark'); ?></p>
                  <?php endif; ?>
                  </div>
                </div>
              </div> 
            </div>            
          </fieldset>
          <br clear="all" />            
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> 
				<span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
	  <!-- HERE LIST ---->
<div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
         
       ASSESSMENT LIST </span> </header>
        <div class="panel-body">
    <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Sr.No.</th>
            <th>Assessment Name</th> 
			<th>Max.Mark</th> 
			<th>Common to all</th> 
			<th>Class</th> 
			<th>Section</th> 
			<th>Status</th>	
            <th>--</th>        
          </tr>
        </thead>
        <tbody>
		<?php  
		$i=1;
		foreach($EDITDATA as $row){
			if($row['common_to_all']=='Y'){$common_value="Yes";}else{$common_value="No";}
			if($row['class_name']==''){$class_value="---";}else{$class_value=$row['class_name'];}
			if($row['class_section_name']==''){$class_section_value="---";}else{$class_section_value=$row['class_section_name'];}
		?>
           <tr>
			<td><?=$i; ?></td>
			<td><?=$row['name']; ?></td>
			<td><?=$row['max_mark']; ?></td>
			<td><?=$common_value; ?></td>
			<td><?=$class_value; ?></td>
			<td><?=$class_section_value; ?></td>
			<td><?=showStatus($row['status'])?></td>
			<td>
			<div class="btn-group"> <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">Action <i class="fa fa-arrow-down icon-white"></i></a>
                  <ul class="dropdown-menu">                 
                     <?php if($row['status'] == 'Y'): ?>
                    <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/changestatus/<?=$row['encrypt_id']?>/N"><i class="fa fa-thumbs-down"></i> Inactive</a></li>
                    <?php elseif($row['status'] == 'N'): ?>
                    <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/changestatus/<?=$row['encrypt_id']?>/Y"><i class="fa fa-thumbs-up"></i> Active</a></li>
                    <?php endif; ?>
                  </ul>
                </div>
			</td>					
		   </tr>	
		<?php 
		$i=$i+1;
		} 
		?>
        </tbody>
      </table>
    </div>
    <BR>
    </form>
  </div>
</div>

</div>
<!-- END HERE ---->	  
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

function getClassShow(){
	var assessment_type = document.getElementById('common_to_all').value;
	if(assessment_type=='N'){
		$("#class_area").show();
	}
	else{
		$("#class_area").hide();
	}
}
</script>
