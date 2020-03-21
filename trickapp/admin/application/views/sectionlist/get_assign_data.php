<form id="Current_page_assign_form" class="form-horizontal mgik-form" method="post" action="">
<input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$perioddata['encrypt_id']?>"  />
<input type="hidden" name="class_id" id="class_id" value="<?=$classid?>"  />
<input type="hidden" name="section_id" id="section_id" value="<?=$sectionid?>"  />
<input type="hidden" name="period_id" id="period_id" value="<?=$periodid?>"  />
<input type="hidden" name="workday_id" id="workday_id" value="<?=$workdayid?>"  />
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
<table class="table border-none">
  <tbody>
    <tr>
      <td align="left"><strong>Subject Name</strong></td>
      <td align="left">
	  <?php if($perioddata['subject_id']): $subjectid	= stripslashes($perioddata['subject_id']); else: $subjectid	= ''; endif; ?>
      <select name="subject_id" id="subject_id" class="form-control required">
        <option value="">Select Subject</option>
        <?php if($subjectdata <> ""): foreach($subjectdata as $subjectinfo): ?>
        <option value="<?php echo $subjectinfo['encrypt_id']; ?>" <?php if($subjectid == $subjectinfo['encrypt_id']) echo 'selected="selected"'; ?>><?php echo stripslashes($subjectinfo['subject_name']); ?></option>
        <?php endforeach; endif; ?>
      </select>
      </td>
    </tr>
    </tr>
      <td align="left"><strong>Teacher Name</strong></td>
      <td align="left">
	  <?php if($perioddata['teacher_id']): $teacherid	= stripslashes($perioddata['teacher_id']); else: $teacherid	= ''; endif; ?>
      <select name="teacher_id" id="teacher_id" class="form-control required">
        <option value="">Select Teacher</option>
      </select>
      </td>
    </tr>
    <tr>
      <td align="left">&nbsp;</td>
      <td align="left"><input type="submit" name="SaveChanges" id="SaveChanges" value="Assign" class="btn btn-primary" /></td>
    </tr>
  </tbody>
</table>
</form>
<script>
$(document).on('change','#Current_page_assign_form #subject_id',function(){
	var schoolid		=	$('#Current_page_assign_form #school_id').val();
	var branchid		=	$('#Current_page_assign_form #branch_id').val();
	var subjectid		=	$(this).val();
	var	teacherid		=	'';
	$.ajax({
		type: 'post',
		 url: FULLSITEURL+CURRENTCLASS+'/get_teacher_by_subject',
		data: {csrf_api_key:csrf_api_value,schoolid:schoolid,branchid:branchid,subjectid:subjectid,teacherid:teacherid},
	 success: function(response){
	 	$('#Current_page_assign_form #teacher_id').html(response);
	  }
	});
});
</script>
<script>
<?php if($perioddata <> ""): ?>
	var subjectid		=	'<?php echo $subjectid; ?>';
	var	teacherid		=	'<?php echo $teacherid; ?>';
	$.ajax({
		type: 'post',
		 url: FULLSITEURL+CURRENTCLASS+'/get_teacher_by_subject',
		data: {csrf_api_key:csrf_api_value,subjectid:subjectid,teacherid:teacherid},
	 success: function(response){
	 	$('#Current_page_assign_form #teacher_id').html(response);
	  }
	});
<?php endif; ?>
</script>
<script>
$(document).ready(function(){
  $('#Current_page_assign_form').validate({
  		submitHandler: function(form) {
			  var formdata		=	$('#Current_page_assign_form').serialize();
              $.ajax({
			  	  type: 'post',
				   url: FULLSITEURL+CURRENTCLASS+'/add_period_assign_data',
				  data: formdata,
			   success: function(response){
			   			window.location.reload();
					}
			  });
              return false;
            }
  });
});
</script>