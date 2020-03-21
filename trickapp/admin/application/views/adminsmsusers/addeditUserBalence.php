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
   User Balance Details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
     Update User Balance Details</li>
  <li class="pull-right"><a href="<?php echo correctLink('smsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">        
<?php if($EDITDATA <> ""): 
  $user_id  = $EDITDATA['user_id'];
  ?>
    <ul class="nav nav-tabs blue_tab">
      <li ><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata/<?php echo $user_id; ?>">Personal</a></li>
      <li class="active"><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditUserBalance/<?php echo $user_id; ?>">Update User Balance</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addedittransctionhistory/<?php echo $user_id; ?>">Transaction History</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditbalancehistory/<?php echo $user_id; ?>">Check User Balance</a></li>
    </ul>
  <?php endif; ?>
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          Sms Update User Balance Details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['user_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <div class="col-lg-6"><?php //echo "<pre>"; print_r($EDITDATA['transaction_type']); die; ?>
              <div class="form-group">
                <label class="col-lg-4 control-label">No Of Sms<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="sms" id="sms"value="<?php if(set_value('sms')): echo set_value('sms'); else: echo stripslashes($EDITDATA['noofsms']);endif; ?>" class="form-control required" placeholder="No Of Sms" <?php if($EDITDATA['sms']<>""): echo "readonly"; endif; ?>>
                  <?php if(form_error('sms')): ?>
                  <p for="sms" generated="true" class="error"><?php echo form_error('sms'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Account Type<span class="required">*</span></label>
                <div class="col-lg-8">
                <?php if(set_value('account_type')):  $acounttypeid	=	set_value('account_type'); elseif($EDITDATA['account_type']): $acounttypeid	=	stripslashes($EDITDATA['account_type']); else: $acounttypeid	=	''; endif; ?>
                  <select name="account_type" id="account_type" class="form-control required" > 
                  <option value="#">Account Type</option>
                    <option value="A"<?php if($EDITDATA['account_type'] == $acounttypeid): echo 'selected="selected"'; endif; ?>>A</option>
                    <option value="B" <?php if($EDITDATA['account_type'] == $acounttypeid): echo 'selected="selected"'; endif; ?>>B</option>
                    <option value="C"<?php if($EDITDATA['account_type'] == $acounttypeid): echo 'selected="selected"'; endif; ?>>C</option>
                    <option value="D"<?php if($EDITDATA['account_type'] == $acounttypeid): echo 'selected="selected"'; endif; ?>>D</option>
                    <option value="D"<?php if($EDITDATA['account_type'] == $acounttypeid): echo 'selected="selected"'; endif; ?>>D</option>

                  </select>
                  <?php if(form_error('account_type')): ?>
                  <p for="account_type" generated="true" class="error"><?php echo form_error('account_type'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Transaction Type<span class="required">*</span></label>
                <div class="col-lg-8">
                <?php if(set_value('transction_type')): $transctiontypeid	=	set_value('transction_type'); elseif($EDITDATA['transaction_type']): $transctiontypeid	=	stripslashes($EDITDATA['transction_type']); else: $transctiontypeid	=	''; endif; ?>
                <select name="transction_type" id="transction_type" class="form-control required">
                    <option value="#">Transaction Type</option>
                    <option value="Add"<?php if($EDITDATA['transaction_type'] ==  'Add'): echo 'selected="selected"'; endif; ?>>Add</option>
                    <option value="Reduce"<?php if($EDITDATA['transaction_type'] == $transctiontypeid): echo 'selected="selected"'; endif; ?>>Reduce</option>
                  </select>
                  <?php if(form_error('transction_type')): ?>
                  <p for="transction_type" generated="true" class="error"><?php echo form_error('transction_type'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Price<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="number" name="price" id="price"value="<?php if(set_value('price')): echo set_value('price'); else: echo stripslashes($EDITDATA['price']);endif; ?>" class="form-control required" placeholder="Price" >
                  <?php if(form_error('price')): ?>
                  <p for="price" generated="true" class="error"><?php echo form_error('price'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="col-lg-4 control-label">Description<span class="required">*</span></label>
                <div class="col-lg-8">
                <input type="text" name="description" id="description"value="<?php if(set_value('description')): echo set_value('description'); else: echo stripslashes($EDITDATA['description']);endif; ?>" class="form-control required" placeholder="Description" >
                  <?php if(form_error('description')): ?>
                  <p for="description" generated="true" class="error"><?php echo form_error('description'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
         </div>
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
$(document).ready(function(){
  create_editor_for_textarea('message');
});
</script>