<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<script>
$(function(){
  $("#expiry_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
});
</script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('smsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      Sms User Details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    Sms User Details</li>
  <li class="pull-right"><a href="<?php echo correctLink('smsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
<?php if($EDITDATA <> ""): 
  $user_id  = $EDITDATA['user_id'];
  ?>
    <ul class="nav nav-tabs blue_tab">
      <li class="active"><a href="javascript:void(0);">Personal</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditUserBalence/<?php echo $user_id; ?>">Update User Balance</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addedittransctionhistory/<?php echo $user_id; ?>">Transaction History</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditbalancehistory/<?php echo $user_id; ?>">Check User Balance</a></li>
    </ul>
  <?php endif; ?>
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          Sms User Details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['user_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <fieldset>
              <legend>School Details</legend>
              <div class="col-md-12 col-sm-12 col-xs-12 form-space"> <?php //echo "<pre>"; print_r($EDITDATA); die; ?>
                <div class="col-lg-12 class-parent">
                 <div class="col-sm-6">
                  <div class="form-group">
                  <label class="col-lg-4 control-label">Select Franchsing<span class="required">*</span></label>
                    <div class="col-lg-8">
                    <?php if(set_value('franchies_id')): $franchiesid = set_value('franchies_id'); else: $franchiesid  = $EDITDATA['franchise_id'];  endif; ?>

                        <select name="franchies_id" id="franchies_id" class="input form-control">
                          <option value="">Select Franchsing</option>
                          <?php if($FRANCHISING <> ""): foreach($FRANCHISING as $FRANCHISINGINFO): ?>
                          <option value="<?php echo $FRANCHISINGINFO['encrypt_id']; ?>" <?php if($FRANCHISINGINFO['encrypt_id'] == $franchiesid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($FRANCHISINGINFO['admin_name']); ?></option>
                          <?php endforeach; endif; ?>
                        </select>
                      </div>
                    </div>
                    </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="col-lg-4 control-label">Select School<span class="required">*</span></label>
                          <div class="col-lg-8">
                          <?php if(set_value('school_id')): $schoolid = set_value('school_id'); else: $schoolid  = $EDITDATA['school_id'];  endif; ?>
                          <select name="school_id" id="school_id" class="input form-control">
                            <option value="">Select School name</option>
                          </select>
                          </div>
                        </div>
                      </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                              <label class="col-lg-4 control-label">Select Branch<span class="required">*</span></label>
                                  <div class="col-lg-8">
                                  <?php if(set_value('branch_id')): $branchid = set_value('branch_id'); else: $branchid  = $EDITDATA['branch_id'];  endif; ?>
                                    <select name="branch_id" id="branch_id" class="input form-control">
                                      <option value="">Select Branch</option>
                                    </select>
                                    <?php if(form_error('branch_id')): ?>
                                    <p for="branch_id" generated="true" class="error"><?php echo form_error('branch_id'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                        <div class="form-group">
                          <label class="col-lg-4 control-label">Assign Message<span class="required">*</span></label>
                          <div class="col-lg-8">
                            <input type="text" name="assign_message" id="assign_message"value="<?php if(set_value('assign_message')): echo set_value('assign_message'); else: echo stripslashes($EDITDATA['assign_message']);endif; ?>" class="form-control required" placeholder="Assign No. Of Message">
                            <?php if(form_error('assign_message')): ?>
                            <p for="assign_message" generated="true" class="error"><?php echo form_error('assign_message'); ?></p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    </fieldset>
                  <fieldset>
                    <legend>User Details</legend>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="col-lg-4 control-label">Name<span class="required">*</span></label>
                          <div class="col-lg-8">
                            <input type="text" name="fullname" id="fullname"value="<?php if(set_value('fullname')): echo set_value('fullname'); else: echo stripslashes($EDITDATA['full_name']);endif; ?>" class="form-control required" placeholder="Full Name">
                            <?php if(form_error('fullname')): ?>
                            <p for="fullname" generated="true" class="error"><?php echo form_error('fullname'); ?></p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="col-lg-4 control-label">Username<span class="required">*</span></label>
                          <div class="col-lg-8">
                            <input type="text" name="username" id="username"value="<?php if(set_value('username')): echo set_value('username'); else: echo stripslashes($EDITDATA['username']);endif; ?>" class="form-control required" placeholder="Display name" <?php if($EDITDATA['username']<>""): echo "readonly"; endif; ?>>
                            <?php if(form_error('username')): ?>
                            <p for="username" generated="true" class="error"><?php echo form_error('username'); ?></p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="col-lg-4 control-label">Mobile<span class="required">*</span></label>
                          <div class="col-lg-8">
                            <input type="text" name="mobile" id="mobile"value="<?php if(set_value('mobile')): echo set_value('mobile'); else: echo stripslashes($EDITDATA['mobile']);endif; ?>" class="form-control required number" placeholder="Mobile Number" min="10" <?php if($EDITDATA['mobile']<>""): echo "readonly"; endif; ?>>
                            <?php if(form_error('mobile')): ?>
                            <p for="mobile" generated="true" class="error"><?php echo form_error('mobile'); ?></p>
                            <?php endif; if($b_mobileerror):  ?>
                            <p for="mobile" generated="true" class="error"><?php echo $b_mobileerror; ?></p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="col-lg-4 control-label">Email Id<span class="required">*</span></label>
                          <div class="col-lg-8">
                            <input type="text" name="email" id="email"value="<?php if(set_value('email')): echo set_value('email'); else: echo stripslashes($EDITDATA['email']);endif; ?>" class="form-control required email" placeholder="Email Id" >
                            <?php if(form_error('email')): ?>
                            <p for="email" generated="true" class="error"><?php echo form_error('email'); ?></p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="col-lg-4 control-label">Company<span class="required">*</span></label>
                          <div class="col-lg-8">
                            <input type="text" name="company" id="company"value="<?php if(set_value('company')): echo set_value('company'); else: echo stripslashes($EDITDATA['company']);endif; ?>" class="form-control required" placeholder="Company">
                            <?php if(form_error('company')): ?>
                            <p for="company" generated="true" class="error"><?php echo form_error('company'); ?></p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="col-lg-4 control-label">Industry<span class="required">*</span></label>
                          <div class="col-lg-8">
                            <input type="text" name="industry" id="industry"value="<?php if(set_value('industry')): echo set_value('industry'); else: echo stripslashes($EDITDATA['industry']);endif; ?>" class="form-control required" placeholder="Industry" >
                            <?php if(form_error('industry')): ?>
                            <p for="industry" generated="true" class="error"><?php echo form_error('industry'); ?></p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="col-lg-4 control-label">Expiry Date<span class="required">*</span></label>
                          <div class="col-lg-8">
                            <input type="text" name="expiry_date" id="expiry_date" value="<?php if(set_value('expiry_date')): echo set_value('expiry_date'); else: echo ($EDITDATA['expirydate']);endif; ?>" class="form-control required" placeholder="Expiry date" autocomplete="off" >
                            <?php if(form_error('expiry_date')): ?>
                            <p for="expiry_date" generated="true" class="error"><?php echo form_error('expiry_date'); ?></p>
                            <?php endif; ?>
                          </div>
                        </div>
                    </div>
                    </fieldset>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('smsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
  $(document).on('change','#franchies_id',function(){ 
    var curobj          =   $(this);
    var franchiesid     =   $(this).val();
    var schoolid        =   '';
    get_school_data(curobj,franchiesid,schoolid);
  });
</script>
<script>

  <?php if($schoolid <> "" || $_POST): ?> 
    $(document).ready(function(){ 
      var curobj      =   $('#franchies_id');
      var franchiesid     =   '<?php echo $franchiesid; ?>';
      var schoolid   =   '<?php echo $schoolid; ?>';
      get_school_data(curobj,franchiesid,schoolid);
    });
  <?php endif; ?>
</script> 
<script>
  $(document).on('change','#school_id',function(){
    var curobj      =   $(this);
    var franchiesid =   $('#franchies_id').val();
    var schoolid    =   $(this).val();
    var sectionid   =   '';
    //alert(franchiesid); alert(schoolid); alert(sectionid);
    get_branch_data(curobj,franchiesid,schoolid,sectionid);
  });
</script>
<script>
  <?php if($branchid <> "" || $_POST): ?> 
    $(document).ready(function(){ 
      var curobj      =   $('#school_id');
      var franchiesid     =   '<?php echo $franchiesid; ?>';
      var schoolid   =   '<?php echo $schoolid; ?>';
      var branchid   =   '<?php echo $branchid; ?>';
      get_branch_data(curobj,franchiesid,schoolid,branchid);
    });
  <?php endif; ?>
</script> 