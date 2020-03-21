<header class="header fixed-top clearfix">
  <div class="brand"> <a href="{FULL_SITE_URL}dashboard" class="logo"><?php echo sessionData('SMS_LIBRARY_ADMIN_DISLPLAY_NAME'); ?></a>
    <div class="sidebar-toggle-box">
      <div class="fa fa-bars"></div>
    </div>
  </div>
  <div class="top-nav clearfix">
  <div class="center">
   <?php if(sessionData('SMS_LIBRARY_ADMIN_TYPE') == 'Super admin'): ?>
   		 <select name="currentFranchisingId" id="currentFranchisingId">
           <?php echo $this->admin_model->get_franchising_for_navigation(); ?>
         </select>
         <select name="currentSchoolBranchId" id="currentSchoolBranchId">
           <?php echo $this->admin_model->get_school_branch_for_navigation(); ?>
         </select>
   <?php elseif(sessionData('SMS_LIBRARY_ADMIN_TYPE') == 'Franchising'): ?>
         <select name="currentSchoolBranchId" id="currentSchoolBranchId">
           <?php echo $this->admin_model->get_school_branch_for_navigation(); ?>
         </select>
   <?php elseif(sessionData('SMS_LIBRARY_ADMIN_TYPE') == 'School'): ?>
         <select name="currentSchoolBranchId" id="currentSchoolBranchId">
           <?php echo $this->admin_model->get_school_branch_for_navigation(sessionData('SMS_LIBRARY_ADMIN_ID')); ?>
         </select>
   <?php endif; ?>
    <select name="currentSchoolBranchId" id="currentSchoolBranchBoardId">
    	<?php echo $this->admin_model->get_board_for_navigation(); ?>
    </select>
    <ul class="nav pull-right top-menu">
      <li class="dropdown"> <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0);">&nbsp;&nbsp;<span class="username">Welcome <?php echo sessionData('SMS_LIBRARY_ADMIN_DISLPLAY_NAME'); ?></span> <b class="caret"></b> </a>
        <ul class="dropdown-menu extended logout">
          <li><a href="{FULL_SITE_URL}profile/index"><i class=" fa fa-suitcase"></i>Profile</a></li>
          <li><a href="{BASE_URL}logout"><i class="fa fa-key"></i> Log Out</a></li>
        </ul>
      </li>
    </ul>
    </div>
  </div>
</header>
<script>
$(document).on('change','#currentFranchisingId',function(){
	var franchiseId		=	$(this).val();
	if(franchiseId != '')
	{
		$.ajax({
			type: 'post',
			 url: BASEURL+'login/set_cureent_franchise',
			data: {franchiseId:franchiseId},
		 success: function(response){
		 		if(response == 'SUCCESS')
				{
					location.reload();
				}
		 	}
		});
	}
});

$(document).on('change','#currentSchoolBranchId',function(){
	var currentSBId		=	$(this).val();
	if(currentSBId != '')
	{
		$.ajax({
			type: 'post',
			 url: BASEURL+'login/set_cureent_school_branch',
			data: {currentSBId:currentSBId},
		 success: function(response){
		 		if(response == 'SUCCESS')
				{
					location.reload();
				}
		 	}
		});
	}
});

$(document).on('change','#currentSchoolBranchBoardId',function(){
	var currentBoardId		=	$(this).val();
	if(currentBoardId != '')
	{
		$.ajax({
			type: 'post',
			 url: BASEURL+'login/set_cureent_board',
			data: {currentBoardId:currentBoardId},
		 success: function(response){
		 		if(response == 'SUCCESS')
				{
					location.reload();
				}
		 	}
		});
	}
});

$(document).ready(function(){
	<?php if(!$this->session->userdata('SMS_LIBRARY_ADMIN_FRANCHISE_ID')): ?>
	
		var franchiseId		=	$("#currentFranchisingId option:eq(1)").val();
		if(franchiseId != '')
		{
			$.ajax({
				type: 'post',
				 url: BASEURL+'login/set_cureent_franchise',
				data: {franchiseId:franchiseId},
			 success: function(response){
					if(response == 'SUCCESS')
					{
						location.reload();
					}
				}
			});
		}
		
	<?php elseif(!$this->session->userdata('SMS_LIBRARY_ADMIN_SCHOOL_ID') || !$this->session->userdata('SMS_LIBRARY_ADMIN_BRANCH_ID')): ?>
		
		var currentSBId		=	$("#currentSchoolBranchId option:eq(1)").val();
		if(currentSBId != '')
		{
			$.ajax({
				type: 'post',
				 url: BASEURL+'login/set_cureent_school_branch',
				data: {currentSBId:currentSBId},
			 success: function(response){
					if(response == 'SUCCESS')
					{
						location.reload();
					}
				}
			});
		}
		
	<?php elseif(!$this->session->userdata('SMS_ADMIN_BOARD_ID')): ?>
		
		var currentBoardId		=	$("#currentSchoolBranchBoardId option:eq(1)").val();
		if(currentBoardId != '')
		{
			$.ajax({
				type: 'post',
				 url: BASEURL+'login/set_cureent_board',
				data: {currentBoardId:currentBoardId},
			 success: function(response){
					if(response == 'SUCCESS')
					{
						location.reload();
					}
				}
			});
		}
		
	<?php endif; ?>
});
</script>