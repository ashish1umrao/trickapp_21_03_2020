<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<script>
$(function(){
  $("#student_admission_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_relieving_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_dob").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});

  $("#student_admission_date_1").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_relieving_date_1").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_dob_1").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  
  $("#student_admission_date_2").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_relieving_date_2").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_dob_2").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  
  $("#student_admission_date_3").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_relieving_date_3").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_dob_3").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});

  $("#student_admission_date_4").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_relieving_date_4").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_dob_4").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});

  $("#student_admission_date_5").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_relieving_date_5").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#student_dob_5").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});

});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
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
      <li class="active"><a href="javascript:void(0);">Personal</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditparents/<?php echo $studentId; ?>">Parents</a></li>
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
                student details</span> </header>
				<!-- bada bhai-->
       <?php if($EDITDATA):
       echo   $studentDetails ; endif; ?>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID[]" value="<?=$EDITDATA['student_qunique_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />

            <fieldset id="1a" class="tab-pane">
                      <legend>Student details</legend>
                       <?php  if($EDITDATA <>""): ?>
                        <span>
              <div class="col-md-12 col-sm-12 col-xs-12 form-space"> <?php //echo "<pre>"; print_r($EDITDATA); die; ?>
                <div class="col-lg-12 class-parent">
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
                          <?php if(set_value('section_id')): $sectionInfo = set_value('section_id'); else: $sectionInfo  = $EDITDATA['section_id'];  endif; ?>
                          <select name="section_id" id="section_id" class="form-control required">
                            <option value="">Select section name</option>
                          </select>
                          <?php if(form_error('section_id')): ?>
                          <p for="section_id" generated="true" class="error"><?php echo form_error('section_id'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">Roll no<span class="required">*</span></label>
                        <div class="col-lg-8">
                          <input type="text" name="student_roll_no" id="student_roll_no" value="<?php if(set_value('student_roll_no')): echo set_value('student_roll_no'); else: echo stripslashes($EDITDATA['student_roll_no']);endif; ?>" class="form-control required" placeholder="Roll no">
                          <?php if(form_error('student_roll_no')): ?>
                          <p for="student_roll_no" generated="true" class="error"><?php echo form_error('student_roll_no'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">Registration no<span class="required">*</span></label>
                        <div class="col-lg-8">
                          <input type="text" name="student_registration_no" id="student_registration_no" value="<?php if(set_value('student_registration_no')): echo set_value('student_registration_no'); else: echo stripslashes($EDITDATA['student_registration_no']);endif; ?>" class="form-control required" placeholder="Registration no">
                          <?php if(form_error('student_registration_no')): ?>
                          <p for="student_registration_no" generated="true" class="error"><?php echo form_error('student_registration_no'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                  </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">Admission date<span class="required">*</span></label>
                        <div class="col-lg-8">
                          <input type="text" name="student_admission_date" id="student_admission_date" value="<?php if(set_value('student_admission_date')): echo set_value('student_admission_date'); else: echo YYMMDDtoDDMMYY($EDITDATA['student_admission_date']);endif; ?>" class="form-control required" placeholder="Admission date" autocomplete="off">
                          <?php if(form_error('student_admission_date')): ?>
                          <p for="student_admission_date" generated="true" class="error"><?php echo form_error('student_admission_date'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">Relieving date</label>
                        <div class="col-lg-8">
                          <input type="text" name="student_relieving_date" id="student_relieving_date" value="<?php if(set_value('student_relieving_date')): echo set_value('student_relieving_date'); else: echo YYMMDDtoDDMMYY($EDITDATA['student_relieving_date']);endif; ?>" class="form-control" placeholder="Relieving date" autocomplete="off">
                          <?php if(form_error('student_relieving_date')): ?>
                          <p for="student_relieving_date" generated="true" class="error"><?php echo form_error('student_relieving_date'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">First name<span class="required">*</span></label>
                        <div class="col-lg-8">
                          <input type="text" name="student_f_name" id="student_f_name" value="<?php if(set_value('student_f_name')): echo set_value('student_f_name'); else: echo stripslashes($EDITDATA['student_f_name']);endif; ?>" class="form-control required" placeholder="First name">
                          <?php if(form_error('student_f_name')): ?>
                          <p for="student_f_name" generated="true" class="error"><?php echo form_error('student_f_name'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">Middle name</label>
                        <div class="col-lg-8">
                          <input type="text" name="student_m_name" id="student_m_name" value="<?php if(set_value('student_m_name')): echo set_value('student_m_name'); else: echo stripslashes($EDITDATA['student_m_name']);endif; ?>" class="form-control" placeholder="Middle name">
                          <?php if(form_error('student_m_name')): ?>
                          <p for="student_m_name" generated="true" class="error"><?php echo form_error('student_m_name'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">Last name</label>
                        <div class="col-lg-8">
                          <input type="text" name="student_l_name" id="student_l_name" value="<?php if(set_value('student_l_name')): echo set_value('student_l_name'); else: echo stripslashes($EDITDATA['student_l_name']);endif; ?>" class="form-control" placeholder="Last name">
                          <?php if(form_error('student_l_name')): ?>
                          <p for="student_l_name" generated="true" class="error"><?php echo form_error('student_l_name'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">Date of birth<span class="required">*</span></label>
                        <div class="col-lg-8">
                          <input type="text" name="student_dob" id="student_dob" value="<?php if(set_value('student_dob')): echo set_value('student_dob'); else: echo YYMMDDtoDDMMYY($EDITDATA['student_dob']);endif; ?>" class="form-control required" placeholder="Date of birth" autocomplete="off">
                          <?php if(form_error('student_dob')): ?>
                          <p for="student_dob" generated="true" class="error"><?php echo form_error('student_dob'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">Profile picture</label>
                        <div class="col-lg-8"> <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                          <input type="text" id="uploadimage0_<?php echo $i; ?>" name="uploadimage0_<?php echo $i; ?>" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($EDITDATA['student_image']); endif; ?>" class="browseimageclass" />
                          <br clear="all">
                          <?php if(form_error('uploadimage0')): ?>
                          <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label>
                          <?php endif; ?>
                          <label id="uploadstatus0" class="error"></label>
                          <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                          <span id="uploadphoto0" style="float:left;">
                          <?php if(set_value('uploadimage0')):?>
                          <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                          <?php elseif($EDITDATA['student_image']):?>
                          <img src="<?php echo stripslashes($EDITDATA['student_image'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($EDITDATA['student_image'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                          <?php endif; ?>
                          </span> </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">Gender</label>
                        <div class="col-lg-8">
                          <?php if(set_value('student_gender')): $studentgenders  = explode('___',set_value('student_gender')); $studentgender  = $studentgenders[0]?$studentgenders[0]:''; elseif($EDITDATA['student_gender']): $studentgender = $EDITDATA['student_gender']; else: $studentgender = ''; endif; ?>
                          <select name="student_gender" id="student_gender" class="form-control">
                            <option value="">Select gender</option>
                            <?php if($GENDERDATA <> ""): foreach($GENDERDATA as $GENDERINFO): ?>
                            <option value="<?php echo $GENDERINFO['user_gender_name'].'___'.$GENDERINFO['user_gender_short_name']; ?>" <?php if($GENDERINFO['user_gender_name'] == $studentgender): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($GENDERINFO['user_gender_name']); ?></option>
                            <?php endforeach; endif; ?>
                          </select>
                          <?php if(form_error('student_gender')): ?>
                          <p for="student_gender" generated="true" class="error"><?php echo form_error('student_gender'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">Visible mark (if any)</label>
                        <div class="col-lg-8">
                          <input type="text" name="student_visible_mark" id="student_visible_mark" value="<?php if(set_value('student_visible_mark')): echo set_value('student_visible_mark'); else: echo stripslashes($EDITDATA['student_visible_mark']);endif; ?>" class="form-control" placeholder="Visible mark">
                          <?php if(form_error('student_visible_mark')): ?>
                          <p for="student_visible_mark" generated="true" class="error"><?php echo form_error('student_visible_mark'); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="col-md-4 col-sm-4 col-xs-4">
						  <div class="form-group ">
							<label class="fancy-checkbox form-headings">&nbsp;</label>
							<button class="btn btn-success addMoredetail" type="button" id="Adddetail_1"><i class="fa fa-plus"></i></button>
						  </div>
						</div>
					  </div>
					</span>
                       <?php endif; ?>
					   <!-- bada bhai khatam-->
                      <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
                        <?php
                          if(set_value('TotalmoredetailsCount')):
                            $TotalmoredetailsCount = set_value('TotalmoredetailsCount');
                          elseif($SIBEDITDATA <> ""):
                            $TotalmoredetailsCount = count($SIBEDITDATA);
                            //echo "<pre>"; print_r($TotalmoredetailsCount); die;
                          else:
                            $TotalmoredetailsCount = 1;
                          endif;
                        ?>
                        <input type="hidden" name="TotalmoredetailsCount" id="TotalmoredetailsCount" value="<?php echo $TotalmoredetailsCount;?>">
                        <input type="hidden" name="TotalmoredetailsCountData" id="TotalmoredetailsCountData" value="<?php echo $TotalmoredetailsCount;?>">
                        <?php 
                          if($suberror <> ""):
                            echo '<span generated="true" class="error">'.$suberror.'</span>';
                          endif;
                        ?>
                        <div id="TOtalmoredetailsMainDiv">
                          <?php
                          if(set_value('TotalmoredetailsCount')):
                            for($i=1;$i<=$TotalmoredetailsCount;$i++):
                              $student_roll_no_           =   'student_roll_no_'.$i;
                              $student_f_name_             =   'student_f_name'.$i;
                              $section_id_                =   'section_id'.$i;
                              $student_registration_no_   =   'student_registration_no_'.$i;
                              $student_admission_date_    =   'student_admission_date_'.$i;
                              $student_relieving_date_    =   'student_relieving_date_'.$i;
                              $student_m_name_            =   'student_m_name_'.$i;
                              $student_l_name_            =   'student_l_name_'.$i;
                              $student_dob_                =   'student_dob'.$i;
                              $student_image_              =   'student_image'.$i;
                              $student_gender_             =   'student_gender'.$i;
                              $student_visible_mark_       =   'student_visible_mark'.$i;
                          ?>
                          <span><?php if($i>2):?><legend class="legendcss">&nbsp;</legend> <?php endif;?>
                          <div class="col-md-12 col-sm-12 col-xs-12 form-space"> 
                          <div class="col-lg-6">
                                    <div class="form-group">
                                      <label class="col-lg-4 control-label">Roll no<span class="">*</span></label>
                                      <div class="col-lg-8">
                                        <input type="text" name="student_roll_no_<?php echo $i; ?>" id="student_roll_no_<?php echo $i; ?>" value="<?php echo set_value($student_roll_no_);?>" class="form-control" placeholder="Roll no">
                                        <?php if(form_error('student_roll_no')): ?>
                                        <p for="student_roll_no" generated="true" class="error"><?php echo form_error('student_roll_no'); ?></p>
                                        <?php endif; ?>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                  <div class="form-group">
                                    <label class="col-lg-4 control-label">First name<span class="required">*</span></label>
                                    <div class="col-lg-8">
                                      <input type="text" name="student_f_name" id="student_f_name" value="<?php if(set_value('student_f_name')): echo set_value('student_f_name'); else: echo stripslashes($EDITDATA['student_f_name']);endif; ?>" class="form-control required" placeholder="First name">
                                      <?php if(form_error('student_f_name')): ?>
                                      <p for="student_f_name" generated="true" class="error"><?php echo form_error('student_f_name'); ?></p>
                                      <?php endif; ?>
                                    </div>
                                  </div>
                                </div>
                            <!-- Add remove buttons -->
                              <div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                                  <label>&nbsp;</label><br clear="all">
                                  <?php if($i<$TotalmoredetailsCount):?>
                                  <button class="btn btn-success addMoredetail" type="button" id="Adddetail_<?php echo $i; ?>" style="display:none;"><i class="fa fa-plus"></i></button>
                                  <?php if($i>1): ?>
                                  <button class="btn btn-danger removeMoredetail" type="button" id="Removedetail_<?php echo $i; ?>"><i class="fa fa-minus"></i></button>
                                  <?php endif; ?>
                                  <?php else:?>
                                  <button class="btn btn-success addMoredetail" type="button" id="Adddetail_<?php echo $i; ?>" ><i class="fa fa-plus"></i></button>
                                  <?php endif;?>
                                </div>
                                <!-- Add remove buttons -->
                          </div>
                          </span>
                          <?php endfor;?>
                          <?php elseif($SIBEDITDATA <> ""): //echo count($SIBEDITDATA);die;?>
                            <?php $i=1;foreach($SIBEDITDATA as $STUSIBDATAINFO): if($i==1):echo "";else:?>
                          <span><?php if($i>1):?><legend class="legendcss">&nbsp;</legend> <?php endif;?>
                            <div class="col-md-12 col-sm-12 col-xs-12 form-space"> 
                                <div class="col-lg-12 class-parent">
                                    <div class="col-lg-6">
                                      <div class="form-group">
                                        <label class="col-lg-4 control-label">Class name<span class="required">*</span></label>
                                        <div class="col-lg-8">
                                          <?php if(set_value('class_id')): $classid = set_value('class_id'); else: $classid  = $EDITDATA['class_id'];  endif; ?>
                                          <select name="class_id_<?php echo $i;?>" id="class_id_<?php echo $i;?>" class="form-control required class_id">
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
                                          <select name="section_id_<?php echo $i;?>" id="section_id_<?php echo $i;?>" class="form-control required">
                                            <option value="">Select section name</option>
                                          </select>
                                          <?php if(form_error('section_id')): ?>
                                          <p for="section_id" generated="true" class="error"><?php echo form_error('section_id'); ?></p>
                                          <?php endif; ?>
                                        </div>
                                      </div>
                                    </div>
                                </div>							
                                    <div class="col-lg-6">
                                      <div class="form-group">
                                        <label class="col-lg-4 control-label">Roll no<span class="">*</span></label>
                                          <div class="col-lg-8">
                                              <input type="hidden" name="student_id" id="student_id" value="<?php echo $STUSIBDATAINFO['student_parent_id'] ?>">
                                              <input type="hidden" name="student_child_id_<?php echo $i; ?>" id="student_child_id_<?php echo $i; ?>" value="<?php echo $STUSIBDATAINFO['student_sibling_id'] ?>">
                                              <input type="text" name="student_roll_no_<?php echo $i; ?>" id="student_roll_no_<?php echo $i; ?>" value="<?php if(set_value('student_roll_no')): echo set_value('student_roll_no'); else: echo stripslashes($STUSIBDATAINFO['student_roll_no']);endif; ?>" class="form-control " placeholder="Roll no">
                                              <?php if(form_error('student_roll_no')): ?>
                                              <p for="student_roll_no" generated="true" class="error"><?php echo form_error('student_roll_no'); ?></p>
                                              <?php endif; ?>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-6">
                                      <div class="form-group">
                                          <label class="col-lg-4 control-label">Registration no<span class="required">*</span></label>
                                          <div class="col-lg-8">
                                            <input type="hidden" name="student_child_id" id="student_child_id" value="<?php echo $STUSIBDATAINFO['student_qunique_id'] ?>">
                                            <input type="text" name="student_registration_no_<?php echo $i; ?>" id="student_registration_no_<?php echo $i; ?>" value="<?php if(set_value('student_registration_no')): echo set_value('student_registration_no'); else: echo stripslashes($STUSIBDATAINFO['student_registration_no']);endif; ?>" class="form-control required" placeholder="Registration no">
                                            <?php if(form_error('student_registration_no')): ?>
                                            <p for="student_registration_no" generated="true" class="error"><?php echo form_error('student_registration_no'); ?></p>
                                            <?php endif; ?>
                                          </div>
                                      </div>
                                    </div>
                                  <!-- </div> -->
                                  <div class="col-lg-12">
                                    <div class="col-lg-6">
                                      <div class="form-group">
                                          <label class="col-lg-4 control-label">Admission date<span class="required">*</span></label>
                                          <div class="col-lg-8">
                                            <input type="hidden" name="student_child_id" id="student_child_id" value="<?php echo $STUSIBDATAINFO['student_qunique_id'] ?>">
                                            <input type="text" name="student_admission_date_<?php echo $i; ?>" id="student_admission_date_<?php echo $i; ?>" value="<?php if(set_value('student_admission_date')): echo set_value('student_admission_date'); else: echo YYMMDDtoDDMMYY($STUSIBDATAINFO['student_admission_date']);endif; ?>" class="form-control required" placeholder="Admission date" autocomplete="off">
                                            <?php if(form_error('student_admission_date')): ?>
                                            <p for="student_admission_date" generated="true" class="error"><?php echo form_error('student_admission_date'); ?></p>
                                            <?php endif; ?>
                                          </div>
                                      </div>
                                  </div>
                                    <div class="col-lg-6">
                                      <div class="form-group">
                                        <label class="col-lg-4 control-label">Relieving date</label>
                                          <div class="col-lg-8">
                                            <input type="hidden" name="student_child_id" id="student_child_id" value="<?php echo $STUSIBDATAINFO['student_qunique_id'] ?>">
                                            <input type="text" name="student_relieving_date_<?php echo $i; ?>" id="student_relieving_date_<?php echo $i; ?>" value="<?php if(set_value('student_relieving_date')): echo set_value('student_relieving_date'); else: echo YYMMDDtoDDMMYY($STUSIBDATAINFO['student_relieving_date']);endif; ?>" class="form-control" placeholder="Relieving date" autocomplete="off">
                                            <?php if(form_error('student_relieving_date')): ?>
                                            <p for="student_relieving_date" generated="true" class="error"><?php echo form_error('student_relieving_date'); ?></p>
                                            <?php endif; ?>
                                          </div>
                                      </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-lg-6">
                                      <div class="form-group">
                                        <label class="col-lg-4 control-label">First name<span class="required">*</span></label>
                                          <div class="col-lg-8">
                                            <input type="hidden" name="student_child_id" id="student_child_id" value="<?php echo $STUSIBDATAINFO['student_qunique_id'] ?>">
                                            <input type="text" name="student_f_name_<?php echo $i; ?>" id="student_f_name_<?php echo $i; ?>" value="<?php if(set_value('student_f_name')): echo set_value('student_f_name'); else: echo stripslashes($STUSIBDATAINFO['student_f_name']);endif; ?>" class="form-control required" placeholder="First name">
                                            <?php if(form_error('student_f_name')): ?>
                                            <p for="student_f_name" generated="true" class="error"><?php echo form_error('student_f_name'); ?></p>
                                            <?php endif; ?>
                                          </div>
                                      </div>
                                    </div>
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                      <label class="col-lg-4 control-label">Middle name</label>
                                        <div class="col-lg-8">
                                          <input type="hidden" name="student_child_id" id="student_child_id" value="<?php echo $STUSIBDATAINFO['student_qunique_id'] ?>">
                                          <input type="text" name="student_m_name_<?php echo $i; ?>" id="student_m_name_<?php echo $i; ?>" value="<?php if(set_value('student_m_name')): echo set_value('student_m_name'); else: echo stripslashes($STUSIBDATAINFO['student_m_name']);endif; ?>" class="form-control" placeholder="Middle name">
                                          <?php if(form_error('student_m_name')): ?>
                                          <p for="student_m_name" generated="true" class="error"><?php echo form_error('student_m_name'); ?></p>
                                          <?php endif; ?>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="col-lg-6">
                                  <div class="form-group">
                                    <label class="col-lg-4 control-label">Last name</label>
                                    <div class="col-lg-8">
                                    <input type="hidden" name="student_child_id" id="student_child_id" value="<?php echo $STUSIBDATAINFO['student_qunique_id'] ?>">
                                    <input type="text" name="student_l_name_<?php echo $i; ?>" id="student_l_name_<?php echo $i; ?>" value="<?php if(set_value('student_l_name')): echo set_value('student_l_name'); else: echo stripslashes($STUSIBDATAINFO['student_l_name']);endif; ?>" class="form-control" placeholder="Last name">
                                    <?php if(form_error('student_l_name')): ?>
                                    <p for="student_l_name" generated="true" class="error"><?php echo form_error('student_l_name'); ?></p>
                                    <?php endif; ?>
                                    </div>
                                  </div>
                                  </div>
                                  <div class="col-lg-6">
                                  <div class="form-group">
                                    <label class="col-lg-4 control-label">Date of birth<span class="required">*</span></label>
                                    <div class="col-lg-8">
                                    <input type="hidden" name="student_child_id" id="student_child_id" value="<?php echo $STUSIBDATAINFO['student_qunique_id'] ?>">
                                    <input type="text" name="student_dob_<?php echo $i; ?>" id="student_dob_<?php echo $i; ?>" value="<?php if(set_value('student_dob')): echo set_value('student_dob'); else: echo YYMMDDtoDDMMYY($STUSIBDATAINFO['student_dob']);endif; ?>" class="form-control required" placeholder="Date of birth" autocomplete="off">
                                    <?php if(form_error('student_dob')): ?>
                                    <p for="student_dob" generated="true" class="error"><?php echo form_error('student_dob'); ?></p>
                                    <?php endif; ?>
                                    </div>
                                  </div>
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                      <label class="col-lg-4 control-label">Profile picture</label>
                                      <div class="col-lg-8"> <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                                      <input type="text" id="uploadimage0_<?php echo $i; ?>" name="uploadimage0_<?php echo $i; ?>" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($EDITDATA['student_image']); endif; ?>" class="browseimageclass" />
                                      <br clear="all">
                                      <?php if(form_error('uploadimage0')): ?>
                                      <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label>
                                      <?php endif; ?>
                                      <label id="uploadstatus0" class="error"></label>
                                      <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                                      <span id="uploadphoto0" style="float:left;">
                                      <?php if(set_value('uploadimage0')):?>
                                      <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                                      <?php elseif($EDITDATA['student_image']):?>
                                      <img src="<?php echo stripslashes($EDITDATA['student_image'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($EDITDATA['student_image'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                                      <?php endif; ?>
                                      </span> </div>
                                    </div>
                                    </div>
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                    <label class="col-lg-4 control-label">Gender</label>
                                    <div class="col-lg-8">
                                      <?php if(set_value('student_gender')): $studentgenders  = explode('___',set_value('student_gender')); $studentgender  = $studentgenders[0]?$studentgenders[0]:''; elseif($EDITDATA['student_gender']): $studentgender = $EDITDATA['student_gender']; else: $studentgender = ''; endif; ?>
                                      <select name="student_gender" id="student_gender" class="form-control">
                                      <option value="">Select gender</option>
                                      <?php if($GENDERDATA <> ""): foreach($GENDERDATA as $GENDERINFO): ?>
                                      <option value="<?php echo $GENDERINFO['user_gender_name'].'___'.$GENDERINFO['user_gender_short_name']; ?>" <?php if($GENDERINFO['user_gender_name'] == $studentgender): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($GENDERINFO['user_gender_name']); ?></option>
                                      <?php endforeach; endif; ?>
                                      </select>
                                      <?php if(form_error('student_gender')): ?>
                                      <p for="student_gender" generated="true" class="error"><?php echo form_error('student_gender'); ?></p>
                                      <?php endif; ?>
                                    </div>
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <div class="form-group">
                                      <label class="col-lg-4 control-label">Visible mark (if any)</label>
                                      <div class="col-lg-8">
                                      <input type="text" name="student_visible_mark" id="student_visible_mark" value="<?php if(set_value('student_visible_mark')): echo set_value('student_visible_mark'); else: echo stripslashes($EDITDATA['student_visible_mark']);endif; ?>" class="form-control" placeholder="Visible mark">
                                      <?php if(form_error('student_visible_mark')): ?>
                                      <p for="student_visible_mark" generated="true" class="error"><?php echo form_error('student_visible_mark'); ?></p>
                                      <?php endif; ?>
                                      </div>
                                    </div>
                                    </div>
                                  </div>
                                  <!-- Add remove buttons -->
                                  <div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                    <div class="form-group-inner col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                                      <label>&nbsp;</label><br clear="all">
                                      <?php if($i>=1): ?>
                                      <button class="btn btn-danger removeMoredetail" type="button" id="Removedetail_<?php echo $i; ?>"><i class="fa fa-minus"></i></button>
                                      <?php endif; ?>
                                    </div>
                                  <!-- Add remove buttons -->
                                  </div>

                                  </div>
                                  </span>
                                  <?php endif;$i++;endforeach;?>
                                  <button class="btn btn-success addMoredetail" type="button" id="Adddetail_<?php echo $i; ?>" ><i class="fa fa-plus"></i></button>
                                     
                          
                                       
                            <?php else:?>
                            <span>
                              <div class="col-md-12 col-sm-12 col-xs-12 form-space"> <?php //echo "<pre>"; print_r($EDITDATA); die; ?>
                                    <div class="col-lg-12 class-parent">
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
                                      </div>
                                      <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">Roll no<span class="required">*</span></label>
                                            <div class="col-lg-8">
                                              <input type="text" name="student_roll_no" id="student_roll_no" value="<?php if(set_value('student_roll_no')): echo set_value('student_roll_no'); else: echo stripslashes($EDITDATA['student_roll_no']);endif; ?>" class="form-control required" placeholder="Roll no">
                                              <?php if(form_error('student_roll_no')): ?>
                                              <p for="student_roll_no" generated="true" class="error"><?php echo form_error('student_roll_no'); ?></p>
                                              <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">Registration no<span class="required">*</span></label>
                                            <div class="col-lg-8">
                                              <input type="text" name="student_registration_no" id="student_registration_no" value="<?php if(set_value('student_registration_no')): echo set_value('student_registration_no'); else: echo stripslashes($EDITDATA['student_registration_no']);endif; ?>" class="form-control required" placeholder="Registration no">
                                              <?php if(form_error('student_registration_no')): ?>
                                              <p for="student_registration_no" generated="true" class="error"><?php echo form_error('student_registration_no'); ?></p>
                                              <?php endif; ?>
                                            </div>
                                          </div>
                                      </div>
                                      </div>
                                      <div class="col-lg-12">
                                        <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">Admission date<span class="required">*</span></label>
                                            <div class="col-lg-8">
                                              <input type="text" name="student_admission_date" id="student_admission_date" value="<?php if(set_value('student_admission_date')): echo set_value('student_admission_date'); else: echo YYMMDDtoDDMMYY($EDITDATA['student_admission_date']);endif; ?>" class="form-control required" placeholder="Admission date" autocomplete="off">
                                              <?php if(form_error('student_admission_date')): ?>
                                              <p for="student_admission_date" generated="true" class="error"><?php echo form_error('student_admission_date'); ?></p>
                                              <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">Relieving date</label>
                                            <div class="col-lg-8">
                                              <input type="text" name="student_relieving_date" id="student_relieving_date" value="<?php if(set_value('student_relieving_date')): echo set_value('student_relieving_date'); else: echo YYMMDDtoDDMMYY($EDITDATA['student_relieving_date']);endif; ?>" class="form-control" placeholder="Relieving date" autocomplete="off">
                                              <?php if(form_error('student_relieving_date')): ?>
                                              <p for="student_relieving_date" generated="true" class="error"><?php echo form_error('student_relieving_date'); ?></p>
                                              <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-lg-12">
                                        <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">First name<span class="required">*</span></label>
                                            <div class="col-lg-8">
                                              <input type="text" name="student_f_name" id="student_f_name" value="<?php if(set_value('student_f_name')): echo set_value('student_f_name'); else: echo stripslashes($EDITDATA['student_f_name']);endif; ?>" class="form-control required" placeholder="First name">
                                              <?php if(form_error('student_f_name')): ?>
                                              <p for="student_f_name" generated="true" class="error"><?php echo form_error('student_f_name'); ?></p>
                                              <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">Middle name</label>
                                            <div class="col-lg-8">
                                              <input type="text" name="student_m_name" id="student_m_name" value="<?php if(set_value('student_m_name')): echo set_value('student_m_name'); else: echo stripslashes($EDITDATA['student_m_name']);endif; ?>" class="form-control" placeholder="Middle name">
                                              <?php if(form_error('student_m_name')): ?>
                                              <p for="student_m_name" generated="true" class="error"><?php echo form_error('student_m_name'); ?></p>
                                              <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-lg-12">
                                        <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">Last name</label>
                                            <div class="col-lg-8">
                                              <input type="text" name="student_l_name" id="student_l_name" value="<?php if(set_value('student_l_name')): echo set_value('student_l_name'); else: echo stripslashes($EDITDATA['student_l_name']);endif; ?>" class="form-control" placeholder="Last name">
                                              <?php if(form_error('student_l_name')): ?>
                                              <p for="student_l_name" generated="true" class="error"><?php echo form_error('student_l_name'); ?></p>
                                              <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">Date of birth<span class="required">*</span></label>
                                            <div class="col-lg-8">
                                              <input type="text" name="student_dob" id="student_dob" value="<?php if(set_value('student_dob')): echo set_value('student_dob'); else: echo YYMMDDtoDDMMYY($EDITDATA['student_dob']);endif; ?>" class="form-control required" placeholder="Date of birth" autocomplete="off">
                                              <?php if(form_error('student_dob')): ?>
                                              <p for="student_dob" generated="true" class="error"><?php echo form_error('student_dob'); ?></p>
                                              <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-lg-12">
                                        <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">Profile picture</label>
                                            <div class="col-lg-8"> <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                                              <input type="text" id="uploadimage0_<?php echo $i; ?>" name="uploadimage0_<?php echo $i; ?>" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($EDITDATA['student_image']); endif; ?>" class="browseimageclass" />
                                              <br clear="all">
                                              <?php if(form_error('uploadimage0')): ?>
                                              <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label>
                                              <?php endif; ?>
                                              <label id="uploadstatus0" class="error"></label>
                                              <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                                              <span id="uploadphoto0" style="float:left;">
                                              <?php if(set_value('uploadimage0')):?>
                                              <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                                              <?php elseif($EDITDATA['student_image']):?>
                                              <img src="<?php echo stripslashes($EDITDATA['student_image'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($EDITDATA['student_image'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                                              <?php endif; ?>
                                              </span> </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">Gender</label>
                                            <div class="col-lg-8">
                                              <?php if(set_value('student_gender')): $studentgenders  = explode('___',set_value('student_gender')); $studentgender  = $studentgenders[0]?$studentgenders[0]:''; elseif($EDITDATA['student_gender']): $studentgender = $EDITDATA['student_gender']; else: $studentgender = ''; endif; ?>
                                              <select name="student_gender" id="student_gender" class="form-control">
                                                <option value="">Select gender</option>
                                                <?php if($GENDERDATA <> ""): foreach($GENDERDATA as $GENDERINFO): ?>
                                                <option value="<?php echo $GENDERINFO['user_gender_name'].'___'.$GENDERINFO['user_gender_short_name']; ?>" <?php if($GENDERINFO['user_gender_name'] == $studentgender): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($GENDERINFO['user_gender_name']); ?></option>
                                                <?php endforeach; endif; ?>
                                              </select>
                                              <?php if(form_error('student_gender')): ?>
                                              <p for="student_gender" generated="true" class="error"><?php echo form_error('student_gender'); ?></p>
                                              <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-6">
                                          <div class="form-group">
                                            <label class="col-lg-4 control-label">Visible mark (if any)</label>
                                            <div class="col-lg-8">
                                              <input type="text" name="student_visible_mark" id="student_visible_mark" value="<?php if(set_value('student_visible_mark')): echo set_value('student_visible_mark'); else: echo stripslashes($EDITDATA['student_visible_mark']);endif; ?>" class="form-control" placeholder="Visible mark">
                                              <?php if(form_error('student_visible_mark')): ?>
                                              <p for="student_visible_mark" generated="true" class="error"><?php echo form_error('student_visible_mark'); ?></p>
                                              <?php endif; ?>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                      <div class="form-group ">
                                        <label class="fancy-checkbox form-headings">&nbsp;</label>
                                        <button class="btn btn-success addMoredetail" type="button" id="Adddetail_1"><i class="fa fa-plus"></i></button>
                                      </div>
                                    </div>
                                  </div>
                                </span>
                          <?php endif;?>
                        </div>
                      </div>
          </fieldset>
      <?php /*?><fieldset>
            <legend>Student details</legend>
            <div class="col-lg-12 class-parent">
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
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Roll no<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="student_roll_no" id="student_roll_no" value="<?php if(set_value('student_roll_no')): echo set_value('student_roll_no'); else: echo stripslashes($EDITDATA['student_roll_no']);endif; ?>" class="form-control required" placeholder="Roll no">
                    <?php if(form_error('student_roll_no')): ?>
                    <p for="student_roll_no" generated="true" class="error"><?php echo form_error('student_roll_no'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Registration no<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="student_registration_no" id="student_registration_no" value="<?php if(set_value('student_registration_no')): echo set_value('student_registration_no'); else: echo stripslashes($EDITDATA['student_registration_no']);endif; ?>" class="form-control required" placeholder="Registration no">
                    <?php if(form_error('student_registration_no')): ?>
                    <p for="student_registration_no" generated="true" class="error"><?php echo form_error('student_registration_no'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Admission date<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="student_admission_date" id="student_admission_date" value="<?php if(set_value('student_admission_date')): echo set_value('student_admission_date'); else: echo YYMMDDtoDDMMYY($EDITDATA['student_admission_date']);endif; ?>" class="form-control required" placeholder="Admission date">
                    <?php if(form_error('student_admission_date')): ?>
                    <p for="student_admission_date" generated="true" class="error"><?php echo form_error('student_admission_date'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Relieving date</label>
                  <div class="col-lg-8">
                    <input type="text" name="student_relieving_date" id="student_relieving_date" value="<?php if(set_value('student_relieving_date')): echo set_value('student_relieving_date'); else: echo YYMMDDtoDDMMYY($EDITDATA['student_relieving_date']);endif; ?>" class="form-control" placeholder="Relieving date">
                    <?php if(form_error('student_relieving_date')): ?>
                    <p for="student_relieving_date" generated="true" class="error"><?php echo form_error('student_relieving_date'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">First name<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="student_f_name" id="student_f_name" value="<?php if(set_value('student_f_name')): echo set_value('student_f_name'); else: echo stripslashes($EDITDATA['student_f_name']);endif; ?>" class="form-control required" placeholder="First name">
                    <?php if(form_error('student_f_name')): ?>
                    <p for="student_f_name" generated="true" class="error"><?php echo form_error('student_f_name'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Middle name</label>
                  <div class="col-lg-8">
                    <input type="text" name="student_m_name" id="student_m_name" value="<?php if(set_value('student_m_name')): echo set_value('student_m_name'); else: echo stripslashes($EDITDATA['student_m_name']);endif; ?>" class="form-control" placeholder="Middle name">
                    <?php if(form_error('student_m_name')): ?>
                    <p for="student_m_name" generated="true" class="error"><?php echo form_error('student_m_name'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Last name</label>
                  <div class="col-lg-8">
                    <input type="text" name="student_l_name" id="student_l_name" value="<?php if(set_value('student_l_name')): echo set_value('student_l_name'); else: echo stripslashes($EDITDATA['student_l_name']);endif; ?>" class="form-control" placeholder="Last name">
                    <?php if(form_error('student_l_name')): ?>
                    <p for="student_l_name" generated="true" class="error"><?php echo form_error('student_l_name'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Date of birth<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <input type="text" name="student_dob" id="student_dob" value="<?php if(set_value('student_dob')): echo set_value('student_dob'); else: echo YYMMDDtoDDMMYY($EDITDATA['student_dob']);endif; ?>" class="form-control required" placeholder="Date of birth">
                    <?php if(form_error('student_dob')): ?>
                    <p for="student_dob" generated="true" class="error"><?php echo form_error('student_dob'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Profile picture</label>
                  <div class="col-lg-8"> <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                    <input type="text" id="uploadimage0" name="uploadimage0" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($EDITDATA['student_image']); endif; ?>" class="browseimageclass" />
                    <br clear="all">
                    <?php if(form_error('uploadimage0')): ?>
                    <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label>
                    <?php endif; ?>
                    <label id="uploadstatus0" class="error"></label>
                    <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                    <span id="uploadphoto0" style="float:left;">
                    <?php if(set_value('uploadimage0')):?>
                    <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php elseif($EDITDATA['student_image']):?>
                    <img src="<?php echo stripslashes($EDITDATA['student_image'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($EDITDATA['student_image'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                    <?php endif; ?>
                    </span> </div>
                </div>
              </div>
            </div>
          </fieldset>
          <br clear="all" />
      <fieldset><?php */?>
            <legend>Other details</legend>
            <div class="col-lg-12">
            
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Religion</label>
                  <div class="col-lg-8">
                    <?php if(set_value('student_religion')): $studentreligion = set_value('student_religion'); elseif($EDITDATA['student_religion']): $studentreligion = $EDITDATA['student_religion']; else: $studentreligion = ''; endif; ?>
                    <select name="student_religion" id="student_religion" class="form-control">
                      <option value="">Select religion</option>
                      <?php if($RERIGIONDATA <> ""): foreach($RERIGIONDATA as $RERIGIONINFO): ?>
                      <option value="<?php echo $RERIGIONINFO['user_religion_name']; ?>" <?php if($RERIGIONINFO['user_religion_name'] == $studentreligion): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($RERIGIONINFO['user_religion_name']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('student_religion')): ?>
                    <p for="student_religion" generated="true" class="error"><?php echo form_error('student_religion'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Category</label>
                  <div class="col-lg-8">
                    <?php if(set_value('student_category')): $studentcategory = set_value('student_category'); elseif($EDITDATA['student_category']): $studentcategory = $EDITDATA['student_category']; else: $studentcategory = ''; endif; ?>
                    <select name="student_category" id="student_category" class="form-control">
                      <option value="">Select category</option>
                      <?php if($CATEGORYDATA <> ""): foreach($CATEGORYDATA as $CATEGORYINFO): ?>
                      <option value="<?php echo $CATEGORYINFO['user_category_name']; ?>" <?php if($CATEGORYINFO['user_category_name'] == $studentcategory): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CATEGORYINFO['user_category_name']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('student_category')): ?>
                    <p for="student_category" generated="true" class="error"><?php echo form_error('student_category'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
			</fieldset>
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
$(document).on('change','#class_id',function(){
  var curobj      =   $(this);
  var classid     =   $(this).val();
  var sectionid   =   '';
  var particularid=   '0';
  get_section_data(curobj,classid,sectionid,particularid);
});
</script>
<script>
<?php if($EDITDATA <> "" || $_POST): ?> 
$(document).ready(function(){ 
  var curobj      =   $('#class_id');
  var classid     =   '<?php echo $classid; ?>';
  var sectionid   =   '<?php echo $sectionid; ?>';
  var arr = classid.split('_');
  var particularid = arr['2'];
  //alert(sectionid);
  get_section_data(curobj,classid,sectionid,particularid);
});
<?php endif; ?>
</script> 
<script>
$(function(){
  UploadImage('0');
});
</script>
<script>
$(document).on('change','.class_id',function(){
  var pclasses    =   $(this).attr('id');
  var classid     =   $('#'+pclasses).val();
  //alert(classid);
  var curobj      =   $('#'+pclasses);
  var sectionid   =   '';
  var arr = pclasses.split('_');
  var particularid = arr['2'];
  //alert(particularid);
  get_section_data(curobj,classid,sectionid,particularid);  	
  
});
</script>
<script type="text/javascript">
  $(function(){
    var PriceDiv     = $('#currentPageForm #TOtalmoredetailsMainDiv');
    var CountPrice   = $('#currentPageForm #TOtalmoredetailsMainDiv > span').length;
    $(document).on('click','#currentPageForm .addMoredetail',function(){
       var i         = parseInt($('#currentPageForm #TotalmoredetailsCountData').val());
	     var ec        = i-1;
       i++;
       CountPrice++;
	   //alert(i);
       $('<span><div class="col-lg-12 class-parent"><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Class name<span class="">*</span></label><div class="col-lg-8"><?php if(set_value('class_id')): $classid = set_value('class_id'); else: $classid  = $SIBEDITDATA['class_id'];  endif; ?><select name="class_id_'+i+'" id="class_id_'+i+'" class="class_id form-control"><option value="">Select class name</option><?php if($CLASSDATA <> ""): foreach($CLASSDATA as $CLASSINFO): ?><option value="<?php echo $CLASSINFO['encrypt_id']; ?>" <?php if($CLASSINFO['encrypt_id'] == $classid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CLASSINFO['class_name']); ?></option><?php endforeach; endif; ?></select></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Section name<span class="">*</span></label><div class="col-lg-8"><select name="section_id_'+i+'" id="section_id_'+i+'" class="class form-control"><option value="">Select section name</option></select></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Roll no<span class="required">*</span></label><div class="col-lg-8"><input type="text" name="student_roll_no_'+i+'" id="student_roll_no_'+i+'" value="" class="form-control required" placeholder="Roll no"></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Registration no<span class="required">*</span></label><div class="col-lg-8"><input type="text" name="student_registration_no_'+i+'" id="student_registration_no_'+i+'" value="" class="form-control required" placeholder="Registration no"></div></div></div><div class="col-lg-12"><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Admission date<span class="required">*</span></label><div class="col-lg-8"><input type="text" name="student_admission_date_'+i+'" id="student_admission_date_'+i+'" value="" class="form-control required" placeholder="Admission date" autocomplete="off"></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Relieving date</label><div class="col-lg-8"><input type="text" name="student_relieving_date_'+i+'" id="student_relieving_date_'+i+'" value="" class="form-control" placeholder="Relieving date" autocomplete="off"></div></div></div></div><div class="col-lg-12"> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">First name<span class="required">*</span></label> <div class="col-lg-8"> <input type="text" name="student_f_name_'+i+'" id="student_f_name_'+i+'" value="<?php if(set_value('student_f_name')): echo set_value('student_f_name'); else: echo stripslashes($SIBEDITDATA['student_f_name']);endif; ?>" class="form-control required" placeholder="First name"> <?php if(form_error('student_f_name')): ?> <p for="student_f_name" generated="true" class="error"><?php echo form_error('student_f_name'); ?></p> <?php endif; ?> </div> </div> </div> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Middle name</label> <div class="col-lg-8"> <input type="text" name="student_m_name_'+i+'" id="student_m_name_'+i+'" value="<?php if(set_value('student_m_name')): echo set_value('student_m_name'); else: echo stripslashes($SIBEDITDATA['student_m_name']);endif; ?>" class="form-control" placeholder="Middle name"> <?php if(form_error('student_m_name')): ?> <p for="student_m_name" generated="true" class="error"><?php echo form_error('student_m_name'); ?></p> <?php endif; ?> </div> </div> </div> </div> <div class="col-lg-12"> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Last name</label> <div class="col-lg-8"> <input type="text" name="student_l_name_'+i+'" id="student_l_name_'+i+'" value="<?php if(set_value('student_l_name')): echo set_value('student_l_name'); else: echo stripslashes($SIBEDITDATA['student_l_name']);endif; ?>" class="form-control" placeholder="Last name"> <?php if(form_error('student_l_name')): ?> <p for="student_l_name" generated="true" class="error"><?php echo form_error('student_l_name'); ?></p> <?php endif; ?> </div> </div> </div> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Date of birth<span class="required">*</span></label> <div class="col-lg-8"> <input type="text" name="student_dob_'+i+'" id="student_dob_'+i+'" value="<?php if(set_value('student_dob')): echo set_value('student_dob'); else: echo YYMMDDtoDDMMYY($SIBEDITDATA['student_dob']);endif; ?>" class="form-control required" placeholder="Date of birth" autocomplete="off"> <?php if(form_error('student_dob')): ?> <p for="student_dob" generated="true" class="error"><?php echo form_error('student_dob'); ?></p> <?php endif; ?> </div> </div> </div> </div> <div class="col-lg-12"> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Profile picture</label> <div class="col-lg-8"> <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" /> <input type="text" id="uploadimage0_<?php echo $i; ?>" name="uploadimage0_'+i+'" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($SIBEDITDATA['student_image']); endif; ?>" class="browseimageclass" /> <br clear="all"> <?php if(form_error('uploadimage0')): ?> <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label> <?php endif; ?> <label id="uploadstatus0" class="error"></label> <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div> <span id="uploadphoto0" style="float:left;"> <?php if(set_value('uploadimage0')):?> <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a> <?php elseif($SIBEDITDATA['student_image']):?> <img src="<?php echo stripslashes($SIBEDITDATA['student_image'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($SIBEDITDATA['student_image'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a> <?php endif; ?> </span> </div> </div> </div> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Gender</label> <div class="col-lg-8"> <?php if(set_value('student_gender')): $studentgenders = explode('___',set_value('student_gender')); $studentgender = $studentgenders[0]?$studentgenders[0]:''; elseif($SIBEDITDATA['student_gender']): $studentgender = $SIBEDITDATA['student_gender']; else: $studentgender = ''; endif; ?> <select name="student_gender_'+i+'" id="student_gender_'+i+'" class="form-control"> <option value="">Select gender</option> <?php if($GENDERDATA <> ""): foreach($GENDERDATA as $GENDERINFO): ?> <option value="<?php echo $GENDERINFO['user_gender_name'].'___'.$GENDERINFO['user_gender_short_name']; ?>" <?php if($GENDERINFO['user_gender_name'] == $studentgender): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($GENDERINFO['user_gender_name']); ?></option> <?php endforeach; endif; ?> </select> <?php if(form_error('student_gender')): ?> <p for="student_gender" generated="true" class="error"><?php echo form_error('student_gender'); ?></p> <?php endif; ?> </div> </div> </div> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Visible mark (if any)</label> <div class="col-lg-8"> <input type="text" name="student_visible_mark_'+i+'" id="student_visible_mark_'+i+'" value="<?php if(set_value('student_visible_mark')): echo set_value('student_visible_mark'); else: echo stripslashes($SIBEDITDATA['student_visible_mark']);endif; ?>" class="form-control" placeholder="Visible mark"> <?php if(form_error('student_visible_mark')): ?> <p for="student_visible_mark" generated="true" class="error"><?php echo form_error('student_visible_mark'); ?></p> <?php endif; ?> </div> </div> </div></div> <div class="form-group "><label class="fancy-checkbox form-headings">&nbsp;</label><button class="btn btn-success addMoredetail" type="button" id="Adddetail_'+i+'"><i class="fa fa-plus"></i></button><button class="btn btn-danger removeMoredetail" type="button" id="Removedetail_'+i+'"><i class="fa fa-minus"></i></button></div></div></div></span>').appendTo(PriceDiv);
	   /* $('<span><div class="col-lg-12 class-parent"><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Class name<span class="required">*</span></label><div class="col-lg-8"><?php if(set_value('class_id')): $classid = set_value('class_id'); else: $classid  = $EDITDATA['class_id'];  endif; ?><select name="class_id_'+i+'" id="class_id_'+i+'" class="class_id form-control required"><option value="">Select class name</option><?php if($CLASSDATA <> ""): foreach($CLASSDATA as $CLASSINFO): ?><option value="<?php echo $CLASSINFO['encrypt_id']; ?>" <?php if($CLASSINFO['encrypt_id'] == $classid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CLASSINFO['class_name']); ?></option><?php endforeach; endif; ?></select></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Section name<span class="required">*</span></label><div class="col-lg-8"><select name="section_id_'+i+'" id="section_id_'+i+'" class="form-control required"><option value="">Select section name</option></select></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Roll no<span class="required">*</span></label><div class="col-lg-8"><input type="text" name="student_roll_no_'+i+'" id="student_roll_no_'+i+'" value="" class="form-control required" placeholder="Roll no"></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Registration no<span class="required">*</span></label><div class="col-lg-8"><input type="text" name="student_registration_no_'+i+'" id="student_registration_no_'+i+'" value="" class="form-control required" placeholder="Registration no"></div></div></div><div class="col-lg-12"><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Admission date<span class="required">*</span></label><div class="col-lg-8"><input type="text" name="student_admission_date_'+i+'" id="student_admission_date_'+i+'" value="" class="form-control required" placeholder="Admission date" autocomplete="off"></div></div></div><div class="col-lg-6"><div class="form-group"><label class="col-lg-4 control-label">Relieving date</label><div class="col-lg-8"><input type="text" name="student_relieving_date_'+i+'" id="student_relieving_date_<?php echo $i; ?>" value="" class="form-control" placeholder="Relieving date" autocomplete="off"></div></div></div></div><div class="col-lg-12"> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">First name<span class="required">*</span></label> <div class="col-lg-8"> <input type="text" name="student_f_name_'+i+'" id="student_f_name_'+i+'" value="<?php if(set_value('student_f_name')): echo set_value('student_f_name'); else: echo stripslashes($EDITDATA['student_f_name']);endif; ?>" class="form-control required" placeholder="First name"> <?php if(form_error('student_f_name')): ?> <p for="student_f_name" generated="true" class="error"><?php echo form_error('student_f_name'); ?></p> <?php endif; ?> </div> </div> </div> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Middle name</label> <div class="col-lg-8"> <input type="text" name="student_m_name_'+i+'" id="student_m_name_'+i+'" value="<?php if(set_value('student_m_name')): echo set_value('student_m_name'); else: echo stripslashes($EDITDATA['student_m_name']);endif; ?>" class="form-control" placeholder="Middle name"> <?php if(form_error('student_m_name')): ?> <p for="student_m_name" generated="true" class="error"><?php echo form_error('student_m_name'); ?></p> <?php endif; ?> </div> </div> </div> </div> <div class="col-lg-12"> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Last name</label> <div class="col-lg-8"> <input type="text" name="student_l_name_'+i+'" id="student_l_name_'+i+'" value="<?php if(set_value('student_l_name')): echo set_value('student_l_name'); else: echo stripslashes($EDITDATA['student_l_name']);endif; ?>" class="form-control" placeholder="Last name"> <?php if(form_error('student_l_name')): ?> <p for="student_l_name" generated="true" class="error"><?php echo form_error('student_l_name'); ?></p> <?php endif; ?> </div> </div> </div> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Date of birth<span class="required">*</span></label> <div class="col-lg-8"> <input type="text" name="student_dob_<?php echo $i; ?>" id="student_dob_<?php echo $i; ?>" value="<?php if(set_value('student_dob')): echo set_value('student_dob'); else: echo YYMMDDtoDDMMYY($EDITDATA['student_dob']);endif; ?>" class="form-control required" placeholder="Date of birth" autocomplete="off"> <?php if(form_error('student_dob')): ?> <p for="student_dob" generated="true" class="error"><?php echo form_error('student_dob'); ?></p> <?php endif; ?> </div> </div> </div> </div> <div class="col-lg-12"> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Profile picture</label> <div class="col-lg-8"> <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" /> <input type="text" id="uploadimage0_<?php echo $i; ?>" name="uploadimage0_'+i+'" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($EDITDATA['student_image']); endif; ?>" class="browseimageclass" /> <br clear="all"> <?php if(form_error('uploadimage0')): ?> <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label> <?php endif; ?> <label id="uploadstatus0" class="error"></label> <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div> <span id="uploadphoto0" style="float:left;"> <?php if(set_value('uploadimage0')):?> <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a> <?php elseif($EDITDATA['student_image']):?> <img src="<?php echo stripslashes($EDITDATA['student_image'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($EDITDATA['student_image'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a> <?php endif; ?> </span> </div> </div> </div> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Gender</label> <div class="col-lg-8"> <?php if(set_value('student_gender')): $studentgenders = explode('___',set_value('student_gender')); $studentgender = $studentgenders[0]?$studentgenders[0]:''; elseif($EDITDATA['student_gender']): $studentgender = $EDITDATA['student_gender']; else: $studentgender = ''; endif; ?> <select name="student_gender_'+i+'" id="student_gender_'+i+'" class="form-control"> <option value="">Select gender</option> <?php if($GENDERDATA <> ""): foreach($GENDERDATA as $GENDERINFO): ?> <option value="<?php echo $GENDERINFO['user_gender_name'].'___'.$GENDERINFO['user_gender_short_name']; ?>" <?php if($GENDERINFO['user_gender_name'] == $studentgender): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($GENDERINFO['user_gender_name']); ?></option> <?php endforeach; endif; ?> </select> <?php if(form_error('student_gender')): ?> <p for="student_gender" generated="true" class="error"><?php echo form_error('student_gender'); ?></p> <?php endif; ?> </div> </div> </div> <div class="col-lg-6"> <div class="form-group"> <label class="col-lg-4 control-label">Visible mark (if any)</label> <div class="col-lg-8"> <input type="text" name="student_visible_mark_'+i+'" id="student_visible_mark_'+i+'" value="<?php if(set_value('student_visible_mark')): echo set_value('student_visible_mark'); else: echo stripslashes($EDITDATA['student_visible_mark']);endif; ?>" class="form-control" placeholder="Visible mark"> <?php if(form_error('student_visible_mark')): ?> <p for="student_visible_mark" generated="true" class="error"><?php echo form_error('student_visible_mark'); ?></p> <?php endif; ?> </div> </div> </div></div> <div class="form-group "><label class="fancy-checkbox form-headings">&nbsp;</label><button class="btn btn-success addMoredetail" type="button" id="Adddetail_'+i+'"><i class="fa fa-plus"></i></button><button class="btn btn-danger removeMoredetail" type="button" id="Removedetail_'+i+'"><i class="fa fa-minus"></i></button></div></div></div></span>').appendTo(PriceDiv); */
	   $('#currentPageForm #TotalmoredetailsCountData').val(i);
       $(this).closest('#TOtalmoredetailsMainDiv').find('button.removeMoredetail').show();
       $(this).closest('#TOtalmoredetailsMainDiv').find('button.addMoredetail').hide();
       $('#currentPageForm #Removedetail_'+i).hide();
       $('#currentPageForm #Adddetail_'+i).show();
       return false;
    });
    $(document).on('click', '#currentPageForm .removeMoredetail', function() {  
      if( CountPrice > 1 ) {
        $(this).parents('span').remove();
        CountPrice--;
        $('#currentPageForm #TotalmoredetailsCountData').val(CountPrice);
      }
      return false;
    });
    });
</script>