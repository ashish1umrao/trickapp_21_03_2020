<aside>
  <div id="sidebar" class="nav-collapse <?php if(get_cookie("smsAdminSidebarToggleBox") == 'YES'): echo 'hide-left-bar'; endif; ?>">
    <div class="leftside-navigation">
      <ul class="sidebar-menu" id="nav-accordion">
        <li> <a href="{FULL_SITE_URL}dashboard"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a> </li>
        <?php if(sessionData('SMS_ADMIN_TYPE') == 'Super admin'): ?>
          <li><a href="{FULL_SITE_URL}mobileapp/index"><i aria-hidden="true" class="fa fa-mobile" style="font-size:20px;"></i>&nbsp;&nbsp;Manage APP module</a></li>
          <li><a href="{FULL_SITE_URL}franchising/index"><i aria-hidden="true" class="fa fa-users"></i>&nbsp;&nbsp;Manage Franchisee</a></li>
          <li><a href="{FULL_SITE_URL}adminsmsusers/index"><i aria-hidden="true" class="fa fa-comments"></i>&nbsp;&nbsp;Manage Sms Module</a></li>
        <?php endif; //if(sessionData('SMS_ADMIN_TYPE') == 'Super admin' || sessionData('SMS_ADMIN_TYPE') == 'Franchising'): 
          if(sessionData('SMS_ADMIN_TYPE') == 'Franchising'): ?>
          <li><a href="{FULL_SITE_URL}school/index"><i aria-hidden="true" class="fa fa-users"></i>&nbsp;&nbsp;Manage School</a></li>
        <?php endif; //if(sessionData('SMS_ADMIN_TYPE') == 'Super admin' || sessionData('SMS_ADMIN_TYPE') == 'Franchising' || sessionData('SMS_ADMIN_TYPE') == 'School'):
          if(sessionData('SMS_ADMIN_TYPE') == 'School'): ?>
          <li><a href="{FULL_SITE_URL}branch/index"><i aria-hidden="true" class="fa fa-users"></i>&nbsp;&nbsp;Manage Branch</a></li>
        <?php endif; //if(sessionData('SMS_ADMIN_TYPE') == 'Super admin' || sessionData('SMS_ADMIN_TYPE') == 'Franchising' || sessionData('SMS_ADMIN_TYPE') == 'School' || sessionData('SMS_ADMIN_TYPE') == 'Branch'): 
          if(sessionData('SMS_ADMIN_TYPE') == 'School' || sessionData('SMS_ADMIN_TYPE') == 'Branch'): ?>
          <li> <a href="javascript:void(0);"><i aria-hidden="true" class="fa fa-users"></i>&nbsp;&nbsp;Manage Sub Admin<span class="fa arrow"></span> </a>
			      <ul class="nav nav-second-level">
              <li><a href="{FULL_SITE_URL}department/index"><i class="fa fa-circle-o"></i>Department</a></li>
              <li><a href="{FULL_SITE_URL}designation/index"><i class="fa fa-circle-o"></i>Designation</a></li>
              <li><a href="{FULL_SITE_URL}subadmin/index"><i class="fa fa-circle-o"></i>Sub Admin List</a></li>
            </ul>
          </li>
          <li> <a href="http://localhost/trickapp/library/" target= _blank><i aria-hidden="true" class="fa fa-users"></i>&nbsp;&nbsp;Library Management<span class="fa arrow"></span> </a>
        <?php endif; if(sessionData('SMS_ADMIN_TYPE') !='Super admin' && sessionData('SMS_ADMIN_TYPE') !='Franchising'): ?>
         <?php 
		 	    $APURL	=	$this->admin_model->get_menu_module();
		  	if($APURL <> ""): foreach($APURL as $APURLinfo): 
		    $mchilddata	=	$this->admin_model->get_menu_child_module($APURLinfo['encrypt_id']);
		    if($mchilddata): ?>
		   <li> <a href="javascript:void(0);"> <?php echo stripslashes($APURLinfo['module_icone']); ?>&nbsp;&nbsp;<?php echo stripslashes($APURLinfo['module_display_name']); ?> <span class="fa arrow"></span> </a>
			<ul class="nav nav-second-level">
		   <?php foreach($mchilddata as $MCDinfo):  ?>
			  <li><a href="{FULL_SITE_URL}<?php echo stripslashes($MCDinfo['module_name']); ?>/index"><i class="fa fa-circle-o"></i> <?php echo stripslashes($MCDinfo['module_display_name']); ?></a></li>
		  <?php endforeach; ?>
			</ul>
		  </li>
		  <?php else: ?>
		  <li><a href="{FULL_SITE_URL}<?php echo stripslashes($APURLinfo['module_name']); ?>/index"><?php echo stripslashes($APURLinfo['module_icone']); ?>&nbsp;&nbsp;<?php echo stripslashes($APURLinfo['module_display_name']); ?></a></li>
		  <?php endif; ?>
		  <?php endforeach; endif; endif; ?>
      <li>&nbsp;</li>
      </ul>
    </div>
  </div>
</aside>