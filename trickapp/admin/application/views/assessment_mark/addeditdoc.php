<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentListAdminDatastudentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      Upload Documents </a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
     Upload  Documents </li>
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
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditqrcode/<?php echo $studentId; ?>">Student QRCode</a></li>
      <li class="active"><a href="javascript:void(0);">Upload Documents</a></li>
    </ul>
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
         Upload Document</span> </header>
              <?php 
       echo   $studentDetails ;  ?>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
         
            </fieldset>
            <br clear="all" />
            <fieldset>
            <legend> Upload Document</legend>
            <div>
              <div class="col-lg-12">
          <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Document Type<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('doc_type_id')): $docid = set_value('doc_type_id'); else: $docid  = $EDITDATA['doc_type_id'];  endif; ?>
                    <select name="doc_type_id" id="doc_type_id" class="form-control required">
                      <option value="">Select Document Type</option>
                      <?php if($DOCDATA <> ""): foreach($DOCDATA as $DOCINFO): ?>
                      <option value="<?php echo $DOCINFO['encrypt_id']; ?>" <?php if($DOCINFO['encrypt_id'] == $docid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($DOCINFO['doc_type']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('doc_type_id')): ?>
                    <p for="doc_type_id" generated="true" class="error"><?php echo form_error('doc_type_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
                  
                 
                  
              </div>
                </br>
                </br>
              <div class="col-lg-12">
              
                
                   <div class="col-lg-6">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Browse Document<span class="required">*</span></label>
                  <div class="col-lg-8">
                         
                   <input type="file" name="uploadfile" id="uploadfile" class="required"    size="20" />
                   
                   
                 </div>
                </div>
              </div>
               </div>
                <br>
                <br>
                <br>
                    <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Upload" class="btn btn-primary" />
                <a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
                  
           
            </fieldset>
       
          </form>
        </div>
      </section>
    </div>
  </div>

      <?php if($ALLDATA):   ?>
      
      <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
         
        Uploaded Documents </span> </header>
        <div class="panel-body">
      
      
      
      
    
    
    
 
    <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
   
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Sr.No.</th>
            <th>Document Type</th>
           
           
            <th>--</th>
        
          </tr>
        </thead>
        <tbody>
            
          <?php if($ALLDATA <> ""): $i=1; foreach($ALLDATA as $ALLDATAINFO): ?>
          <tr class="<?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
            <td><?=$i++?></td>
          <?php 
        $path_info = pathinfo( $ALLDATAINFO['document']);
       if(($path_info['extension'] == jpg) or ($path_info['extension'] == jpeg) or ($path_info['extension'] == png) ) :
           
           $icon = '<i class="fa fa-file-photo-o "style="color: #d19a98;" ></i>';
       elseif($path_info['extension'] == pdf):
            $icon = '<i class="fa fa-file-pdf-o" style="color: #d19a98;"></i>';
        elseif($path_info['extension'] == doc):
            $icon = '<i class="fa fa-file-alt" style="color: #d19a98;"></i>';
        elseif($path_info['extension'] == xls):
            $icon = '<i class="fa fa-file-excel-o" style="color: #d19a98;"></i>';
        else:
            $icon = '';
       endif;
      
        ?>
         
            <td>  <?=stripslashes($ALLDATAINFO['doc_type'])?>&nbsp;&nbsp;&nbsp;<?php echo $icon ; ?></td>
        
          
            <td><div class="btn-group"> <a class="btn btn-success "  href="<?php echo $ALLDATAINFO['document'] ;  ?>" download>Download<i class="fa fa-download" aria-hidden="true"></i></a>
               
                </div>
            </td>
        
          </tr>
          <?php endforeach; endif; ?>
       
        </tbody>
      </table>
    </div>
    
    </form>
  </div>
</div>

</div>
   <?php endif;  ?>