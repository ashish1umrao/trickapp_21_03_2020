<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('bookListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      book details</a></li>
  <li class="active">
    
   Add book copy</li>
  <li class="pull-right"><a href="<?php echo correctLink('bookListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
         Add copy and  download  barcode</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <br clear="all" />
         
        
            <br clear="all" />
         
            <div>
             
                
                 <div class="col-lg-12 class-parent">
              <div class="col-lg-5">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Shelf No.<span class="required">*</span></label>
                  <div class="col-lg-6">
                    <?php if(set_value('shelf_id')): $shelfid = set_value('shelf_id'); else: $shelfid  = $EDITDATA['shelf_id'];  endif; ?>
                    <select name="shelf_id" id="shelf_id" class="form-control required">
                      <option value="">Select shelf </option>
                      <?php if($SHELFDATA <> ""): foreach($SHELFDATA as $SHELFINFO): ?>
                      <option value="<?php echo $SHELFINFO['encrypt_id']; ?>" <?php if($SHELFINFO['encrypt_id'] == $shelfid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($SHELFINFO['shelf']); ?></option>
                      <?php endforeach; endif; ?>
                    </select>
                    <?php if(form_error('shelf_id')): ?>
                    <p for="shelf_id" generated="true" class="error"><?php echo form_error('shelf_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-7">
                <div class="form-group">
                  <label class="col-lg-4 control-label">Shelf Row No.<span class="required">*</span></label>
                  <div class="col-lg-8">
                    <?php if(set_value('shelf_row_id')): $shelfrowid = set_value('shelf_row_id'); else: $shelfrowid  = $EDITDATA['shelf_row_id'];  endif; ?>
                    <select name="shelf_row_id" id="shelf_row_id" class="form-control required">
                      <option value="">Select shelf row  ( Available Book slots )  </option>
                    </select>
                    <?php if(form_error('shelf_row_id')): ?>
                    <p for="shelf_row_id" generated="true" class="error"><?php echo form_error('shelf_row_id'); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
                
                      <div class="col-lg-12 class-parent">
                 <div class="col-lg-5">
                <div class="form-group">
              <label class="col-lg-4 control-label">Add Book Quantity<span class="required">*</span></label>
              <div class="col-lg-6">
                  <input type="number" name="barcode_no"  min="0"  id="barcode_no"value="<?php if(set_value('barcode_no')): echo set_value('barcode_no'); else: echo stripslashes($EDITDATA['barcode_no']);endif; ?>" class="form-control required" placeholder=" Quantity ">
                  <?php if(form_error('barcode_no')): ?>
                  <p for="barcode_no" generated="true" class="error"><?php echo form_error('barcode_no'); ?></p>
                  <?php endif; ?>
                   <?php if($quntyError): ?>
                  <p for="barcode_no" generated="true" class="error"><?php echo $quntyError ; ?></p>
                  <?php endif; ?>
                  
                  
              </div>
            </div>
              </div>
                          </div>
                </br>
                </br>
                </br>
               
                  </br>
                    </br>
      
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                  <?php if($edit_data == 'Y'):  ?>
                  <button type="submit" name="submit" value="submit"class="btn btn-success">
    <i class="fa fa-download"></i> Add Books & Download Barcodes
</button>     <?php endif;  ?>
             <a href="<?php echo correctLink('bookListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a>
                <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
$(document).on('change','#shelf_id',function(){
  var curobj      =   $(this);
  var shelfid     =   $(this).val();
  var shelfrowid   =   '';
  get_shelf_row_data(curobj,shelfid,shelfrowid);
});
</script>
<script>
<?php if($EDITDATA <> "" || $_POST): ?> 
$(document).ready(function(){ 
  var curobj      =   $('#shelf_id');
  var shelfid     =   '<?php echo $shelfid ; ?>';
  var shelfrowid   =   '<?php echo $shelfrowid ; ?>';
  get_shelf_row_data(curobj,shelfid,shelfrowid);
});
<?php endif; ?>
</script> 

