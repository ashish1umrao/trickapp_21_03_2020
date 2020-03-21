<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentListAdminDatastudentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      student details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    student details</li>
  <li class="pull-right"><a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <?php if($EDITDATA <> ""): ?>
    <ul class="nav nav-tabs blue_tab">
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata/<?php echo $studentId; ?>">Personal</a></li>
      <li class="active"><a href="javascript:void(0);">Parents</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditaddress/<?php echo $studentId; ?>">Address</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addedithealth/<?php echo $studentId; ?>">Health</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addedittransport/<?php echo $studentId; ?>">Transport</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditqrcode/<?php echo $studentId; ?>">Student QRCode</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdoc/<?php echo $studentId; ?>">Upload Documents</a></li>
    </ul>
  <?php endif; ?>
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          student  Parent details</span> </header>
               <?php 
       echo   $studentDetails ;  ?>
        <div class="panel-body">
          <form id="Current_page_form_schadmin" method="post" class="form-horizontal" role="form" action="">

                   <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$FEDITDATA['parent_id']?>"  />
                    <input type="hidden" name="StudentID" id="StudentID" value="<?php echo $studentId; ?>"  />
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
            <fieldset>
            <legend>Father Detail</legend>
            <?php if($FEDITDATA == ''): ?>
            <div class="col-lg-12 class-parent">
              <div class="col-lg-6">
                    <div class="form-group">
                  <label class="col-lg-4 control-label">Enter Father's name or Email</label>
                  <div class="col-lg-8">
                     <input type="text" id="searchtext" name="searchtext" placeholder="Enter Parent's name or Email" value="<?php echo $searchKey;?>" class="search-form form-control searchfields" maxlength="128"  autocomplete="off">
                    <div class="searchres"></div>
                  </div>
                </div>
              </div>
            </div>
            <?php endif ;  ?>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">First Name<span class="required"> * </span></label>
                  <div class="col-lg-8">   <input type="text" name="f_fname" id="f_fname" placeholder="First Name*" class="form-control col-md-7 col-xs-12" required="required" value="<?php if(set_value('f_fname')): echo set_value('f_fname'); else: echo $FEDITDATA['user_f_name']; endif; ?>" />
                    <?php if(form_error('f_fname')): ?>
                    <p for="f_fname" generated="true" class="error"><?php echo form_error('f_fname'); ?></p>
                    <?php endif; ?>
                   
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Middle Name</label>
                  <div class="col-lg-8">  
                    <input type="text" name="f_mname" id="f_mname" placeholder="Middle Name" class="form-control col-md-7 col-xs-12" value="<?php if(set_value('f_mname')): echo set_value('f_mname'); else: echo $FEDITDATA['user_m_name']; endif; ?>" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Last Name</label>
                  <div class="col-lg-8">
                    <input type="text" name="f_lname" id="f_lname" placeholder="Last Name" class="form-control col-md-7 col-xs-12" value="<?php if(set_value('f_lname')): echo set_value('f_lname'); else: echo $FEDITDATA['user_l_name']; endif; ?>" />
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Occupation</label>
                  <div class="col-lg-8">
                     <input type="text" name="f_occupation" id="f_occupation" placeholder="Occupation" class="form-control col-md-7 col-xs-12" value="<?php if(set_value('f_occupation')): echo set_value('f_occupation'); else: echo $FEDITDATA['user_occupation']; endif; ?>" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Income</label>
                  <div class="col-lg-8">
                
                   
                       <input type="text" name="f_income" id="f_income" placeholder="Income" class="form-control col-md-7 col-xs-12" value="<?php if(set_value('f_income')): echo set_value('f_income'); else: echo $FEDITDATA['user_income']; endif; ?>" />
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Education</label>
                  <div class="col-lg-8">
                
                   
                     <input type="text" name="f_education" id="f_education" placeholder="Education" class="form-control " value="<?php if(set_value('f_education')): echo set_value('f_education'); else: echo $FEDITDATA['user_education']; endif; ?>" />
                
                  </div>
                </div>
              </div>
            </div>
            
            
            
             <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Adhaar Number<span class="required"> * </span></label>
                  <div class="col-lg-8">
                
                   
                       <input type="text" name="f_adhaar_card" id="f_adhaar_card" placeholder="Adhaar Number" class="form-control col-md-7 col-xs-12"  required="required" value="<?php if(set_value('m_adhaar_card')): echo set_value('f_adhaar_card'); else: echo $MEDITDATA['user_adhaar_card']; endif; ?>"   />
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Pan Number</label>
                  <div class="col-lg-8">
                
                   
                     <input type="text" name="f_pan_card" id="f_pan_card" placeholder="Pan Number" class="form-control " value="<?php if(set_value('f_pan_card')): echo set_value('f_pan_card'); else: echo $MEDITDATA['user_pan_card']; endif; ?>" />
                
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Age (Years)</label>
                  <div class="col-lg-8">
                
                   
                    <input type="text" name="f_age" id="f_age" placeholder="Age (Years)" class="form-control col-md-7 col-xs-12" maxlength="10" value="<?php if(set_value('f_age')): echo set_value('f_age'); else: echo$FEDITDATA['user_age']; endif; ?>" />
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Email<span class="required"> * </span></label>
                  <div class="col-lg-8">
                     <input type="text" name="f_email" id="f_email" placeholder="Email" class="form-control col-md-7 col-xs-12 email" required="required" value="<?php if(set_value('f_email')): echo set_value('f_email'); else: echo $FEDITDATA['user_email']; endif; ?>" />
                    <?php if(form_error('f_email')): ?>
                    <p for="f_email" generated="true" class="error"><?php echo form_error('f_email'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
             <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Mobile<span class="required"> * </span></label>
                  <div class="col-lg-8">
                
                   
                  <input type="text" name="f_mobile" id="f_mobile" placeholder="Mobile*" class="form-control col-md-7 col-xs-12" minlength="10" maxlength="15" required="required" value="<?php if(set_value('f_mobile')): echo set_value('f_mobile'); else: echo $FEDITDATA['user_mobile']; endif; ?>" />
                    <?php if(form_error('f_mobile')): ?>
                    <p for="f_mobile" generated="true" class="error"><?php echo form_error('f_mobile'); ?></p>
                    <?php endif; ?>
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Phone</label>
                  <div class="col-lg-8">
                       <input type="text" name="f_phone" id="f_phone" placeholder="Phone" class="form-control col-md-7 col-xs-12" minlength="10" maxlength="15" value="<?php if(set_value('f_phone')): echo set_value('f_phone'); else: echo $FEDITDATA['user_phone']; endif; ?>" />
                  </div>
                </div>
              </div>
            </div>
                 <?php if($FEDITDATA <> ""): ?>
            <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Password</label>
                  <div class="col-lg-8">
                
                   <input id="f_password" class="form-control" name="f_password" type="password" value="<?php if(set_value('f_password')): echo set_value('f_password'); endif; ?>" placeholder="password">
                    <?php if(form_error('f_password')): ?>
                    <p for="f_password" generated="true" class="error"><?php echo form_error('f_password'); ?></p>
                    <?php endif; ?>
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Confirm Password</label>
                  <div class="col-lg-8">
                          <input id="f_conf_password" class="form-control" name="f_conf_password" type="password" value="<?php if(set_value('f_conf_password')): echo set_value('f_conf_password'); endif; ?>" placeholder="Confirm Password">
                    <?php if(form_error('f_conf_password')): ?>
                    <p for="f_conf_password" generated="true" class="error"><?php echo form_error('f_conf_password'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
                  <?php else: ?>
             <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Password<span class="required"> * </span></label>
                  <div class="col-lg-8">
                
                   
                   <input id="f_password" class="form-control" required="required" name="f_password" type="password" value="<?php if(set_value('f_password')): echo set_value('f_password'); endif; ?>" placeholder="password">
                    <?php if(form_error('f_password')): ?>
                    <p for="f_password" generated="true" class="error"><?php echo form_error('f_password'); ?></p>
                    <?php endif; ?>
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Confirm Password<span class="required">*</span></label>
                  <div class="col-lg-8">
                          <input id="f_conf_password" class="form-control" required="required" name="f_conf_password" type="password" value="<?php if(set_value('f_conf_password')): echo set_value('f_conf_password'); endif; ?>" placeholder="Confirm Password">
                    <?php if(form_error('f_conf_password')): ?>
                    <p for="f_conf_password" generated="true" class="error"><?php echo form_error('f_conf_password'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
                 </div>
                    <?php  endif;   ?>
                 
                  <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                   
                  <label class="col-lg-4 control-label">Profile picture</label>
                  <div class="col-lg-8"> <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                    <input type="text" id="uploadimage0" name="uploadimage0" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($FEDITDATA['user_pic']); endif; ?>" class="browseimageclass" />
                    <br clear="all">
                    <?php if(form_error('uploadimage0')): ?>
                    <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label>
                    <?php endif; ?>
                    <label id="uploadstatus0" class="error"></label>
                    <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                    <span id="uploadphoto0" style="float:left;">
                    <?php if(set_value('uploadimage0')):?>
                    <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php elseif($FEDITDATA['user_pic']):?>
                    <img src="<?php echo stripslashes($FEDITDATA['user_pic'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($FEDITDATA['user_pic'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php endif; ?>
                    </span> </div>
                </div>
              </div>
            </div>
          
           
              <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
               </div>
           
         </fieldset>
             </form>
          <br clear="all" />
             <form id="Current_page_form_mother" method="post" class="form-horizontal" role="form" action="">
            
                 
                    <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$MEDITDATA['parent_id']?>"  />
                    <input type="hidden" name="StudentID" id="StudentID" value="<?php echo $studentId; ?>"  />
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
            <fieldset>
            <legend>Mother Detail</legend>
              <?php if($MEDITDATA == ''): ?>
            <div class="col-lg-12 class-parent">
              <div class="col-lg-6">
                    <div class="form-group">
                  <label class="col-lg-4 control-label">Enter Mother's name or Email</label>
                  <div class="col-lg-8">
                 
                     <input type="text" id="searchtext" name="searchtext" placeholder="Enter Parent's name or Email" value="<?php echo $searchKey;?>" class="search-form form-control searchfields" maxlength="128"  autocomplete="off">
                    <div class="searchres"></div>
                  </div>
                </div>
              </div>
               
             
            </div>
             <?php endif ;?>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">First Name<span class="required"> * </span></label>
                  <div class="col-lg-8">   <input type="text" name="m_fname" id="m_fname" placeholder="First Name*" class="form-control col-md-7 col-xs-12" required="required" value="<?php if(set_value('m_fname')): echo set_value('m_fname'); else: echo $MEDITDATA['user_f_name']; endif; ?>" />
                    <?php if(form_error('m_fname')): ?>
                    <p for="m_fname" generated="true" class="error"><?php echo form_error('m_fname'); ?></p>
                    <?php endif; ?>
                   
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Middle Name</label>
                  <div class="col-lg-8">  
                    <input type="text" name="m_mname" id="m_mname" placeholder="Middle Name" class="form-control col-md-7 col-xs-12" value="<?php if(set_value('')): echo set_value('m_mname'); else: echo $MEDITDATA['user_m_name']; endif; ?>" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Last Name</label>
                  <div class="col-lg-8">
                    <input type="text" name="m_lname" id="m_lname" placeholder="Last Name" class="form-control col-md-7 col-xs-12" value="<?php if(set_value('m_lname')): echo set_value('m_lname'); else: echo $MEDITDATA['user_l_name']; endif; ?>" />
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Occupation</label>
                  <div class="col-lg-8">
                     <input type="text" name="m_occupation" id="m_occupation" placeholder="Occupation" class="form-control col-md-7 col-xs-12" value="<?php if(set_value('m_occupation')): echo set_value('m_occupation'); else: echo $MEDITDATA['user_occupation']; endif; ?>" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Income</label>
                  <div class="col-lg-8">
                
                   
                       <input type="text" name="m_income" id="m_income" placeholder="Income" class="form-control col-md-7 col-xs-12" value="<?php if(set_value('m_income')): echo set_value('m_income'); else: echo $MEDITDATA['user_income']; endif; ?>" />
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Education</label>
                  <div class="col-lg-8">
                
                   
                     <input type="text" name="m_education" id="m_education" placeholder="Education" class="form-control " value="<?php if(set_value('m_education')): echo set_value('m_education'); else: echo $MEDITDATA['user_education']; endif; ?>" />
                
                  </div>
                </div>
              </div>
            </div>
            
            
            
            
            
            
            <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Adhaar Number<span class="required"> * </span></label>
                  <div class="col-lg-8">
                
                   
                       <input type="text" name="m_adhaar_card" id="m_adhaar_card" placeholder="Adhaar Number" class="form-control col-md-7 col-xs-12"  required="required" value="<?php if(set_value('m_adhaar_card')): echo set_value('m_adhaar_card'); else: echo $MEDITDATA['user_adhaar_card']; endif; ?>"   />
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Pan Number</label>
                  <div class="col-lg-8">
                
                   
                     <input type="text" name="m_pan_card" id="m_pan_card" placeholder="Pan Number" class="form-control " value="<?php if(set_value('m_pan_card')): echo set_value('m_pan_card'); else: echo $MEDITDATA['user_pan_card']; endif; ?>" />
                
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Age (Years)</label>
                  <div class="col-lg-8">
                
                   
                    <input type="text" name="m_age" id="m_age" placeholder="Age (Years)" class="form-control col-md-7 col-xs-12" maxlength="10" value="<?php if(set_value('m_age')): echo set_value('m_age'); else: echo$MEDITDATA['user_age']; endif; ?>" />
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Email<span class="required"> * </span></label>
                  <div class="col-lg-8">
                     <input type="text" name="m_email" id="m_email" placeholder="Email" class="form-control col-md-7 col-xs-12 email" required="required" value="<?php if(set_value('m_email')): echo set_value('m_email'); else: echo $MEDITDATA['user_email']; endif; ?>" />
                    <?php if(form_error('m_email')): ?>
                    <p for="m_email" generated="true" class="error"><?php echo form_error('m_email'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
             <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Mobile<span class="required"> * </span></label>
                  <div class="col-lg-8">
                
                   
                  <input type="text" name="m_mobile" id="m_mobile" placeholder="Mobile*" class="form-control col-md-7 col-xs-12" minlength="10" maxlength="15" required="required" value="<?php if(set_value('m_mobile')): echo set_value('m_mobile'); else: echo $MEDITDATA['user_mobile']; endif; ?>" />
                    <?php if(form_error('m_mobile')): ?>
                    <p for="m_mobile" generated="true" class="error"><?php echo form_error('m_mobile'); ?></p>
                    <?php endif; ?>
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Phone</label>
                  <div class="col-lg-8">
                       <input type="text" name="m_phone" id="m_phone" placeholder="Phone" class="form-control col-md-7 col-xs-12" minlength="10" maxlength="15" value="<?php if(set_value('m_phone')): echo set_value('m_phone'); else: echo $MEDITDATA['user_phone']; endif; ?>" />
                  </div>
                </div>
              </div>
            </div>
                 <?php  
                 if($MEDITDATA <> ""): ?>
            <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Password</label>
                  <div class="col-lg-8">
                
                   
                   <input id="m_password" class="form-control" name="m_password" type="password" value="<?php if(set_value('m_password')): echo set_value('m_password'); endif; ?>" placeholder="password">
                    <?php if(form_error('m_password')): ?>
                    <p for="m_password" generated="true" class="error"><?php echo form_error('m_password'); ?></p>
                    <?php endif; ?>
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Confirm Password</label>
                  <div class="col-lg-8">
                          <input id="m_conf_password" class="form-control" name="m_conf_password" type="password" value="<?php if(set_value('m_conf_password')): echo set_value('m_conf_password'); endif; ?>" placeholder="Confirm Password">
                    <?php if(form_error('m_conf_password')): ?>
                    <p for="m_conf_password" generated="true" class="error"><?php echo form_error('m_conf_password'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
                  <?php else: ?>
             <div class="col-lg-12">
             <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Password<span class="required"> * </span></label>
                  <div class="col-lg-8">
                
                   
                   <input id="m_password" class="form-control" required="required" name="m_password" type="password" value="<?php if(set_value('m_password')): echo set_value('m_password'); endif; ?>" placeholder="password">
                    <?php if(form_error('m_password')): ?>
                    <p for="m_password" generated="true" class="error"><?php echo form_error('m_password'); ?></p>
                    <?php endif; ?>
                
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Confirm Password<span class="required">*</span></label>
                  <div class="col-lg-8">
                          <input id="m_conf_password" class="form-control" required="required" name="m_conf_password" type="password" value="<?php if(set_value('m_conf_password')): echo set_value('m_conf_password'); endif; ?>" placeholder="Confirm Password">
                    <?php if(form_error('m_conf_password')): ?>
                    <p for="m_conf_password" generated="true" class="error"><?php echo form_error('m_conf_password'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
                 </div>
                    <?php  endif;   ?>
                 <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Profile picture</label>
                  <div class="col-lg-8"> <span style="display:inline-block;" id="uploadIds1"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                    <input type="text" id="uploadimage1" name="uploadimage1" value="<?php if(set_value('uploadimage1')): echo set_value('uploadimage1'); else: echo stripslashes($MEDITDATA['user_pic']); endif; ?>" class="browseimageclass" />
                    <br clear="all">
                    <?php if(form_error('uploadimage1')): ?>
                    <label for="uploadimage1" generated="true" class="error"><?php echo form_error('uploadimage1'); ?></label>
                    <?php endif; ?>
                    <label id="uploadstatus1" class="error"></label>
                    <div id="uploadloader1" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                    <span id="uploadphoto1" style="float:left;">
                    <?php if(set_value('uploadimage1')):?>
                    <img src="<?php echo stripslashes(set_value('uploadimage1'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage1'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php elseif($MEDITDATA['user_pic']):?>
                    <img src="<?php echo stripslashes($MEDITDATA['user_pic'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($MEDITDATA['user_pic'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php endif; ?>
                    </span> </div>
                </div>
              </div>
            </div>
            
            
            
           
           
              <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                   <input type="submit" name="SaveChanges1" id="SaveChanges1" value="Submit" class="btn btn-primary" />
    
                <a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
         
              </fieldset>
            </form>
             </div>
         
        </div>
      </section>
    </div>
  </div>
</div>

<script>
$(function(){
  UploadImage('0');
   UploadImage('1');
  
});
</script>
<script>
    
$(document).ready(function(){
  $('#Current_page_form_schadmin').validate({ 
    rules: {
      f_password: { minlength: 6, maxlength: 25 },
      f_conf_password: { minlength: 6, equalTo: "#f_password", },  
      f_mobile : { numberandsign : true }, 
      f_phone : { numberandsign : true }
    },
    messages: {
      f_password: { minlength: "Password at least 6 chars!" },
      f_conf_password: { equalTo: "Password fields have to match !!", minlength: "Confirm password at least 6 chars!" }
    }
  });
});
</script>
<script>  
    
$(document).on('keyup','#Current_page_form_schadmin .searchfields',function(){
	var search_string 	= $(this).val();
        
		if(search_string == ''){
		$("#Current_page_form_schadmin .searchres").html('');
	}else{
		$(".fadeshado").show();
		$(this).parent().addClass('fadeshadoZOomup');
		postdata = {csrf_api_key:csrf_api_value,'string':search_string,'type':'Father'}
               
		$.post(FULLSITEURL+CURRENTCLASS+'/search_ajax',postdata,function(data){
			if(data == 'ERROR')
			{
                          
				$("#Current_page_form_schadmin .searchres").html('');
				$("#Current_page_form_schadmin .searchres").hide();
				$(".fadeshado").hide();
				$('#Current_page_form_schadmin .searchfields').parent().removeClass('fadeshadoZOomup');
			}
			else
			{
				$("#Current_page_form_schadmin .searchres").show();
				$("#Current_page_form_schadmin .searchres").html(data);
			} 
		});
	}
});
</script>
<script>  
   
$(document).on('click','#Current_page_form_schadmin .searchres li p',function(){
 
	var	curgdata	=	$(this).html();
	var	curgid		=	$(this).attr('data-id');
      
	if(curgid != ''){
		$("#Current_page_form_schadmin .searchres").html('');
		$("#Current_page_form_schadmin .searchres").hide();
		$(".fadeshado").hide();
		$('#Current_page_form_schadmin .searchfields').val(curgdata);
		$('#Current_page_form_schadmin .searchfields').parent().removeClass('fadeshadoZOomup');
              
		$.ajax({
		   type: 'post',
			url: FULLSITEURL+CURRENTCLASS+'/same_parent_data_ajax',
		   data: {csrf_api_key:csrf_api_value,curgid:curgid,type:'Father'},
		success:function(response){
				$("#Current_page_form_schadmin #CurrentDataID").val(response.encrypt_id);
				$("#Current_page_form_schadmin #f_fname").val(response.user_f_name);
				$("#Current_page_form_schadmin #f_mname").val(response.user_m_name);
				$("#Current_page_form_schadmin #f_lname").val(response.user_l_name);
				$("#Current_page_form_schadmin #f_occupation").val(response.user_occupation);
				$("#Current_page_form_schadmin #f_income").val(response.user_income);
                                $("#Current_page_form_schadmin #f_age").val(response.user_age);
				$("#Current_page_form_schadmin #f_education").val(response.user_education);
				$("#Current_page_form_schadmin #f_email").val(response.user_email);
				$("#Current_page_form_schadmin #f_mobile").val(response.user_mobile);
				$("#Current_page_form_schadmin #f_phone").val(response.user_phone);
				 $("#Current_page_form_schadmin #f_pan_card").val(response.user_pan_card);
				$("#Current_page_form_schadmin #f_adhaar_card").val(response.user_adhaar_card);
			$("#Current_page_form_schadmin #f_password").val('ManojVajpayee');
				$("#Current_page_form_schadmin #f_conf_password").val('ManojVajpayee');
				
				$("#Current_page_form_schadmin #uploadimage0").val(response.user_pic);
				$("#Current_page_form_schadmin #uploadphoto0").html('<img src="'+response.user_pic+'" width="100" border="0" alt="" />');
			}
		});
	}
});
</script>
<script>
    
$(document).ready(function(){
  $('#Current_page_form_mother').validate({ 
    rules: {
      m_password: { minlength: 6, maxlength: 25 },
      m_conf_password: { minlength: 6, equalTo: "#m_password", },  
      m_mobile : { numberandsign : true }, 
      m_phone : { numberandsign : true }
    },
    messages: {
      m_password: { minlength: "Password at least 6 chars!" },
      m_conf_password: { equalTo: "Password fields have to match !!", minlength: "Confirm password at least 6 chars!" }
    }
  });
});
</script>
<script>  
$(document).on('keyup','#Current_page_form_mother .searchfields',function(){ 
	var search_string 	= $(this).val();
		if(search_string == ''){
		$("#Current_page_form_mother .searchres").html('');
	}else{
		$(".fadeshado").show();
		$(this).parent().addClass('fadeshadoZOomup');
		postdata = {csrf_api_key:csrf_api_value,'string':search_string,'type':'Mother'}
		$.post(FULLSITEURL+CURRENTCLASS+'/search_ajax',postdata,function(data){
			if(data == 'ERROR')
			{     
				$("#Current_page_form_mother .searchres").html('');
				$("#Current_page_form_mother .searchres").hide();
				$(".fadeshado").hide();
				$('#Current_page_form_mother .searchfields').parent().removeClass('fadeshadoZOomup');
			}
			else
			{
                         
				$("#Current_page_form_mother .searchres").show();
				$("#Current_page_form_mother .searchres").html(data);
                               
			} 
		});
	}
});
</script>
<script>  
$(document).on('click','#Current_page_form_mother .searchres li p',function(){
  
	var	curgdata	=	$(this).html();
	var	curgid		=	$(this).attr('data-id');
       
    
	if(curgid != ''){
		$("#Current_page_form_mother .searchres").html('');
		$("#Current_page_form_mother .searchres").hide();
		$(".fadeshado").hide();
		$('#Current_page_form_mother .searchfields').val(curgdata);
		$('#Current_page_form_mother .searchfields').parent().removeClass('fadeshadoZOomup');
             
          
		 $.ajax({
		   type: 'post',
			url: FULLSITEURL+CURRENTCLASS+'/same_parent_data_ajax',
		   data: {csrf_api_key:csrf_api_value,curgid:curgid,type:'Mother'},
		
		success:function(response){
                               $("#Current_page_form_mother #CurrentDataID").val(response.encrypt_id);
				
				$("#Current_page_form_mother #m_fname").val(response.user_f_name);
				$("#Current_page_form_mother #m_mname").val(response.user_m_name);
				$("#Current_page_form_mother #m_lname").val(response.user_l_name);
				$("#Current_page_form_mother #m_occupation").val(response.user_occupation);
				$("#Current_page_form_mother #m_income").val(response.user_income);
                                $("#Current_page_form_mother #m_age").val(response.user_age);
				$("#Current_page_form_mother #m_education").val(response.user_education);
				$("#Current_page_form_mother #m_email").val(response.user_email);
				$("#Current_page_form_mother #m_mobile").val(response.user_mobile);
				$("#Current_page_form_mother #m_phone").val(response.user_phone);
                                $("#Current_page_form_mother #m_pan_card").val(response.user_pan_card);
				$("#Current_page_form_mother #m_adhaar_card").val(response.user_adhaar_card);
				
				
				$("#Current_page_form_mother #m_password").val('ManojVajpayee');
				$("#Current_page_form_mother #m_conf_password").val('ManojVajpayee');
				
				$("#Current_page_form_mother #uploadimage1").val(response.user_pic);
				$("#Current_page_form_mother #uploadphoto1").html('<img src="'+response.user_pic+'" width="100" border="0" alt="" />');
				
			},
                      
		});
              
	}
       
});
</script>
<script>
$(document).on('click','.fadeshado',function(){
	$("#Current_page_form_schadmin .searchres").html('');
	$("#Current_page_form_schadmin .searchres").hide();
	$(".fadeshado").hide();
	$('#Current_page_form_schadmin .searchfields').parent().removeClass('fadeshadoZOomup');
	$("#Current_page_form_mother .searchres").html('');
	$("#Current_page_form_mother .searchres").hide();
	$(".fadeshado").hide();
	$('#Current_page_form_mother .searchfields').parent().removeClass('fadeshadoZOomup');
});
$( window ).scroll(function() {
 	$("#Current_page_form_schadmin .searchres").html('');
	$("#Current_page_form_schadmin .searchres").hide();
	$(".fadeshado").hide();
	$('#Current_page_form_schadmin .searchfields').parent().removeClass('fadeshadoZOomup');
	$("#Current_page_form_mother .searchres").html('');
	$("#Current_page_form_mother .searchres").hide();
	$(".fadeshado").hide();
	$('#Current_page_form_mother .searchfields').parent().removeClass('fadeshadoZOomup');
});
</script>