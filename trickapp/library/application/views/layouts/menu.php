
<aside>
  <div id="sidebar" class="nav-collapse <?php if(get_cookie("smsLIBRARYAdminSidebarToggleBox") == 'YES'): echo 'hide-left-bar'; endif; ?>">
    <div class="leftside-navigation">
      <ul class="sidebar-menu" id="nav-accordion">
        <li> <a href="{FULL_SITE_URL}dashboard"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a> </li>
        
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
		  <?php endforeach; endif; ?>
      </ul>
    </div>
  </div>
</aside>
