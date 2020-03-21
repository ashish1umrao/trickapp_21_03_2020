<header class="header fixed-top clearfix">
  <div class="brand"> <a href="{FULL_SITE_URL}dashboard" class="logo"><?php echo sessionData('SMS_LIBRARY_ADMIN_BRANCH_DIS_NAME'); ?> Lib</a>
    <div class="sidebar-toggle-box">
      <div class="fa fa-bars"></div>
    </div>
  </div>
  <div class="top-nav clearfix">
  <div class="center">
   
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
