<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentListAdminDatastudentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      student details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
     Student QRCode</li>
  <li class="pull-right"><a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
    <ul class="nav nav-tabs blue_tab">
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata/<?php echo $studentId; ?>">Personal</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditparents/<?php echo $studentId; ?>">Parents</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditaddress/<?php echo $studentId; ?>">Address</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addedithealth/<?php echo $studentId; ?>">Health</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addedittransport/<?php echo $studentId; ?>">Transport</a></li>
      <li class="active"><a href="javascript:void(0);">Student QRCode</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdoc/<?php echo $studentId; ?>">Upload Documents</a></li>
    </ul>
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          Student QRCode details</span> </header>
              <?php 
       echo   $studentDetails ;  ?>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
         
            </fieldset>
            <br clear="all" />
            <fieldset>
            <legend> QRCode details</legend>
            <div>
              <div class="col-lg-12">
          <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Level</label>
                  <div class="col-lg-8">
                   
                    <select name="qr_level" id="qr_level" class="form-control">
                     <option value=" ">Select Level</option>
                      
                      <option value="L" <?php if($EDITDATA['qr_level'] == 'L'): echo 'selected="selected"'; endif; ?>>L -smallest</option>
                        <option value="M" <?php if($EDITDATA['qr_level'] == 'M'): echo 'selected="selected"'; endif; ?>>M</option>
                          <option value="Q" <?php if($EDITDATA['qr_level'] == 'Q'): echo 'selected="selected"'; endif; ?>>Q</option>
                            <option value="H" <?php if($EDITDATA['qr_level'] == 'H'): echo 'selected="selected"'; endif; ?>>H - best</option>
                          
                    </select>
                   
                   
                 </div>
                </div>
              </div>
                  
                     <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Level</label>
                  <div class="col-lg-8">
                   
                    <select name="qr_size" id="qr_size" class="form-control">
                     <option value=" ">Select Size</option>
                     <?php  for($i=1;$i<=10;$i++) : ?>
                         <option value="<?php echo $i ;?>" <?php if($EDITDATA['qr_size'] == $i): echo 'selected="selected"'; endif; ?> ><?php echo $i ; ?></option>';
                     <?php endfor;  ?>
                      
                          
                    </select>
                   
                   
                 </div>
                </div>
              </div>
                  
              </div>
                </br>
                </br>
                </br>
                <?php   if($EDITDATA['qr_pic']):  ?>
              <div class="col-lg-12">
               <div class="form-group">
                <div class="col-lg-6">
                 <label class="col-lg-4 control-label">QRcode</label>
                  <img src="<?php echo base_url();?><?php echo stripslashes($EDITDATA['qr_pic'])?>" width="150" border="0" alt="" />   
                    
              </div>
               </div>
                      <?php endif ;   ?>
                  </br>
                  </br>
                  </br>
           
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

