
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<script>

$(function(){
	
  	$("#fee_start_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
	$("#fee_end_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
        $("#fee_due_date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,yearRange: "1960:<?php echo date('Y'); ?>"});
});
</script>

<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('feeheadListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      fee head</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    fee head</li>
  <li class="pull-right"><a href="<?php echo correctLink('feeheadListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
               
          fee head</span> </header>
       
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/><?php //echo "<pre>"; print_r($EDITDATA); die;?>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
              <div class="col-lg-6">
                <label class="col-lg-4 control-label">Fee Heading<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('fee_head_name')): $feeheadid	=	set_value('fee_head_name'); elseif($EDITDATA['fee_head_name']): $feeheadid	=	stripslashes($EDITDATA['fee_head_name']); else: $feeheadid	=	''; endif; ?>
                  <select name="fee_head_name" id="fee_head_name" class="form-control required">
                  	<option value="">Select Fee Heading</option>
                    <?php if($FEEHEADINGLIST <> ""):  foreach($FEEHEADINGLIST as $FEEHEAD): ?>
                    	<option value="<?php echo $FEEHEAD->encrypt_id; ?>" <?php if($FEEHEAD->encrypt_id == $feeheadid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($FEEHEAD->heading_type); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('fee_head_name')): ?>
                  <p for="fee_head_name" generated="true" class="error"><?php echo form_error('fee_head_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="col-lg-6">
                <label class="col-lg-4 control-label">Frequency<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('fee_frequency_id')): $feefrequencyid	=	set_value('fee_frequency_id'); elseif($EDITDATA['fee_frequency_id']): $feefrequencyid	=	stripslashes($EDITDATA['fee_frequency_id']); else: $feefrequencyid	=	''; endif; ?>
                  <select name="fee_frequency_id" id="fee_frequency_id" class="form-control required">
                  	<option value="">Select frequency</option>
                    <?php if($FREQUENCYDATA <> ""): foreach($FREQUENCYDATA as $FREQUENCYINFO): ?>
                    	<option value="<?php echo $FREQUENCYINFO['encrypt_id']; ?>" <?php if($FREQUENCYINFO['encrypt_id'] == $feefrequencyid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($FREQUENCYINFO['fee_frequency_name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('fee_frequency_id')): ?>
                  <p for="fee_frequency_id" generated="true" class="error"><?php echo form_error('fee_frequency_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              </div>
              <div class="form-group">
              <div class="col-lg-6">
                <label class="col-lg-4 control-label">Start date<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="fee_start_date" id="fee_start_date" value="<?php if(set_value('fee_start_date')): echo set_value('fee_start_date'); else: echo stripslashes($EDITDATA['fee_start_date']);endif; ?>" class="form-control required" placeholder="Start date">
                  <?php if(form_error('fee_start_date')): ?>
                  <p for="fee_start_date" generated="true" class="error"><?php echo form_error('fee_start_date'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
                 <div class="col-lg-6">
                <label class="col-lg-4 control-label">End date<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="fee_end_date" id="fee_end_date" value="<?php if(set_value('fee_end_date')): echo set_value('fee_end_date'); else: echo stripslashes($EDITDATA['fee_end_date']);endif; ?>" class="form-control required" placeholder="End date">
                  <?php if(form_error('fee_end_date')): ?>
                  <p for="fee_end_date" generated="true" class="error"><?php echo form_error('fee_end_date'); ?></p>
                  <?php endif; ?>
                </div>
                </div>
              </div>
              <div class="form-group">
              <div class="col-lg-6">
                <label class="col-lg-4 control-label">Due date<span class="required">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="fee_due_date" id="fee_due_date" value="<?php if(set_value('fee_due_date')): echo set_value('fee_due_date'); else: echo stripslashes($EDITDATA['fee_due_date']);endif; ?>" class="form-control required" placeholder="Due date">
                  <?php if(form_error('fee_due_date')): ?>
                  <p for="fee_due_date" generated="true" class="error"><?php echo form_error('fee_due_date'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
                 <div class="col-lg-6">
                <label class="col-lg-4 control-label">Refundable<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('fee_refundable')): $feerefundable	=	set_value('fee_refundable'); elseif($EDITDATA['fee_refundable']): $feerefundable	=	stripslashes($EDITDATA['fee_refundable']); else: $feerefundable	=	''; endif; ?>
                  <select name="fee_refundable" id="fee_refundable" class="form-control required">
                  	<option value="">Select refundable</option>
                    <option value="Yes" <?php if($feerefundable == 'Yes'): echo 'selected="selected"'; endif; ?>>Yes</option>
                    <option value="No" <?php if($feerefundable == 'No'): echo 'selected="selected"'; endif; ?>>No</option>
                  </select>
                  <?php if(form_error('fee_refundable')): ?>
                  <p for="fee_refundable" generated="true" class="error"><?php echo form_error('fee_refundable'); ?></p>
                  <?php endif; ?>
                </div>
                </div>
              </div>
              <div class="form-group">
                 <div class="col-lg-6">
                <label class="col-lg-4 control-label">Concession type<span class="required">*</span></label>
                <div class="col-lg-8">
                  <?php if(set_value('fee_concession_type')): $feeconcessiontype	=	set_value('fee_concession_type'); elseif($EDITDATA['fee_concession_type']): $feeconcessiontype	=	stripslashes($EDITDATA['fee_concession_type']); else: $feeconcessiontype	=	''; endif; ?>
                  <select name="fee_concession_type" id="fee_concession_type" class="form-control required">
                  	<option value="">Select concession type</option>
                    <option value="Percentage" <?php if($feeconcessiontype == 'Percentage'): echo 'selected="selected"'; endif; ?>>Percentage</option>
                    <option value="Fixed" <?php if($feeconcessiontype == 'Fixed'): echo 'selected="selected"'; endif; ?>>Fixed</option>
                  </select>
                  <?php if(form_error('fee_concession_type')): ?>
                  <p for="fee_concession_type" generated="true" class="error"><?php echo form_error('fee_concession_type'); ?></p>
                  <?php endif; ?>
                </div>
                </div>
              
              </div>
              <?php 
              if($STARTMONTH <> ""): $mc=0; $month	=	date("m", strtotime($STARTMONTH));
              $x = '1';
				  for ($i = ($month-1); $i < (12+($month-1)); ++$i):
                     if($x ==1):                  
                 if(set_value('month_id_'.$i)): $feeMonth	=	set_value('month_id_'.$i); elseif($EDITDATA['month_'.$x.'st_fee']): $feeMonth	=	stripslashes($EDITDATA['month_'.$x.'st_fee']); else: $feeMonth	=	''; endif;                           
			
                 elseif($x ==2):
                         if(set_value('month_id_'.$i)): $feeMonth	=	set_value('month_id_'.$i); elseif($EDITDATA['month_'.$x.'nd_fee']): $feeMonth	=	stripslashes($EDITDATA['month_'.$x.'nd_fee']); else: $feeMonth	=	''; endif;                           
                     elseif($x ==3):
                         if(set_value('month_id_'.$i)): $feeMonth	=	set_value('month_id_'.$i); elseif($EDITDATA['month_'.$x.'rd_fee']): $feeMonth	=	stripslashes($EDITDATA['month_'.$x.'rd_fee']); else: $feeMonth	=	''; endif;                           
                     else:
                           if(set_value('month_id_'.$i)): $feeMonth	=	set_value('month_id_'.$i); elseif($EDITDATA['month_'.$x.'th_fee']): $feeMonth	=	stripslashes($EDITDATA['month_'.$x.'th_fee']); else: $feeMonth	=	''; endif;                           
                 endif;
                 ?>
      
              <?php if($mc==0):?><div class="form-group"><?php endif; ?>
              <?php if($mc%4==0):?></div><div class="form-group"><?php endif; ?>
              <div class="col-lg-3">
               
                  <input type="checkbox" name="month_id_<?=$i?>" id="month_id_<?=$i?>" value="True" <?php  if($feeMonth == 'True') : echo 'checked="checked"'; endif; ?> />&nbsp;&nbsp;<?php echo date("F", strtotime("January +$i months")); ?>
                  <?php if(form_error('fee_start_date')): ?>
                  <p for="fee_start_date" generated="true" class="error"><?php echo form_error('fee_start_date'); ?></p>
                  <?php endif; ?>
              </div>
              <?php if($mc==11):?></div><?php endif; ?>
              <?php $mc++; $x++; endfor; endif; ?>
              
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('feeheadListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>