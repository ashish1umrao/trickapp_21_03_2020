<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('shelfRListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage shelf row</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
   shelf row details</li>
  <li class="pull-right"><a href="<?php echo correctLink('shelfRListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          shelf row details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Shelf No<span class="required">*</span></label>
              
               
                <div class="col-lg-6">
                  <input type="text" name="shelf" id="shelf" value="<?php if(set_value('shelf')): echo set_value('shelf'); else: echo stripslashes($EDITDATA['shelf']);endif; ?>" class="form-control required" placeholder="Select Shelf No.">
                  <?php if(form_error('shelf')): ?>
                  <p for="shelf" generated="true" class="error"><?php echo form_error('shelf'); ?></p>
                  <?php endif; ?>
                </div>
                
              </div>
          
              
              <hr style="background-color:#8B5C7E; height:2px;"/>
              <?php 
                if(set_value('TotalRow')): $TotalRow = set_value('TotalRow'); elseif($SDETAILDATA <> ""): $TotalRow = count($SDETAILDATA); else: $TotalRow = 1; endif; ?>
                <input type="hidden" name="TotalRow" id="TotalRow" value="<?php echo $TotalRow; ?>" />
                <input type="hidden" name="TotalRowCount" id="TotalRowCount" value="<?php echo $TotalRow; ?>" />
                <?php if($suberror): echo '<p generated="true" class="error">'.$suberror.'</p>'; endif; ?>
              <div class="col-md-12 col-sm-12 col-xs-12 padding-none" id="Rowlocation">
              <?php if(set_value('TotalRow')){ 
              for($i=1; $i<= $TotalRow; $i++){
              $shelf_row_id_     	= 'shelf_row_id_'.$i;
			  $shelf_row_     = 'shelf_row_'.$i;
              $max_books_        = 'max_books_'.$i;
              
              ?>
              <span><?php if($i > 1){ echo '<hr />'; } ?>
                
                <div class="form-group">
					<input type="hidden" name="shelf_row_id_<?php echo $i; ?>" id="shelf_row_id_<?php echo $i; ?>" value="<?php echo set_value($$shelf_row_id_); ?>" />
                  <div class="col-sm-4 col-xs-12 padd-b-10">
                      <label>Shelf Row  No.<span class="required"> * </span></label>
                      <input type="text" name="shelf_row_<?php echo $i; ?>" id="shelf_row_<?php echo $i; ?>" placeholder="Shelf Row " class="form-control" value="<?php echo set_value($$shelf_row_); ?>" />
                  </div>
                  <div class="col-sm-2 col-xs-12">
                      <label>Max books in row</label>
                      <input type="text" name="max_books_<?php echo $i; ?>" id="max_books_<?php echo $i; ?>" placeholder="Max books " class="form-control col-md-7 col-xs-12" value="<?php echo set_value($$max_books_); ?>" />
                  </div>
              
                  <div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;">
                     <label>&nbsp;</label><br clear="all" />
                     <?php if($i < $TotalRow){ ?>
                     <a href="javascript:void(0);" class="removeMoreRow" id="RemovePeriod_<?php echo $i; ?>" style="display:block;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current subject" /></a>
                     <a href="javascript:void(0);" class="addMoreRow" id="AddPeriod_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more schedule" /></a>
                     <?php }else{ ?>
                     <a href="javascript:void(0);" class="removeMoreRow" id="RemovePeriod_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current subject" /></a>
                     <a href="javascript:void(0);" class="addMoreRow" id="AddPeriod_<?php echo $i; ?>"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more schedule" /></a>
                     <?php } ?>
                  </div>
                </div>
              </span>
            <?php }  ?>

          <?php  }elseif($SDETAILDATA <> ""){ 
              $i=1; foreach($SDETAILDATA as $SDETAILINFO){ ?>
              <span><?php if($i > 1): echo '<hr />'; endif; ?>
                <div class="form-group">
                  
                  <input type="hidden" name="shelf_row_id_<?php echo $i; ?>" id="shelf_row_id_<?php echo $i; ?>" value="<?php echo $SDETAILINFO['encrypt_id']; ?>" />
                  
                  <div class="col-sm-4 col-xs-12 padd-b-10">
                      <label>Shelf Row<span class="required"> * </span></label>
                      <input type="text" name="shelf_row_<?php echo $i; ?>" id="shelf_row_<?php echo $i; ?>" placeholder="Shelf Row" class="form-control" value="<?php echo $SDETAILINFO['shelf_row']; ?>" />
                  </div>
                  <div class="col-sm-2 col-xs-12">
                      <label>Max books in row</label>
                      <input type="text" name="max_books_<?php echo $i; ?>" id="max_books_<?php echo $i; ?>" placeholder="Max Books" class="form-control col-md-7 col-xs-12" value="<?php echo $SDETAILINFO['max_books']; ?>" />
                  </div>
                
                    <div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;">
                       <label>&nbsp;</label><br clear="all" />
                       <?php if($i < $TotalRow): ?>
                       <a href="javascript:void(0);" class="addMoreRow" id="AddPeriod_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Period" /></a>
                       <?php else: ?>
                       <a href="javascript:void(0);" class="addMoreRow" id="AddPeriod_<?php echo $i; ?>"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Period" /></a>
                       <?php endif; ?>
                    </div>
                </div>
              </span>
            <?php $i++; } 
            }else{   ?>

                <span>
                <div class="form-group">
				  <input type="hidden" name="shelf_row_id_1" id="shelf_row_id_1" value="" />
                  <div class="col-sm-4 col-xs-12 padd-b-10">
                      <label>Shelf Row No<span class="required"> * </span></label>
                      <input type="text" name="shelf_row_1" id="shelf_row_1" placeholder="Shelf Row " class="form-control" value="" />
                  </div>
                  <div class="col-sm-2 col-xs-12">
                      <label>Max books in row</label>
                      <input type="text" name="max_books_1" id="max_books_1" placeholder="Max books" class="form-control col-md-7 col-xs-12" value="" />
                  </div>
                 
                  <div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;">
                     <label>&nbsp;</label><br clear="all" />
                      <a href="javascript:void(0);" class="removeMoreRow" id="RemovePeriod_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current Period" /></a>
                       <a href="javascript:void(0);" class="addMoreRow" id="AddPeriod_<?php echo $i; ?>"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Period" /></a>
                  </div>

                </div>
              </span>

            <?php } ?>
             </div>
              
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('shelfRListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
$(function(){ 
  var scntDiv   =   $('#currentPageForm #Rowlocation');
  var pi      =   $('#currentPageForm #Rowlocation > span').size(); 
  
  $(document).on('click', '#currentPageForm .addMoreRow', function() { 
    if($('#currentPageForm #shelf_id').val() == '')
    {
      alert('Please select Shelf No.');
      return false;
    }
    
    var i     =   parseInt($('#currentPageForm #TotalRowCount').val());
    i++;
    pi++;
    $('<span><hr /><div class="form-group"><input type="hidden" name="shelf_row_id_'+i+'" id="shelf_row_id_'+i+'" value="" /><div class="col-sm-4 col-xs-12 padd-b-10"><label>Shelf Row No</label><input type="text" name="shelf_row_'+i+'" id="shelf_row_'+i+'" placeholder="Shelf Row No" class="form-control col-md-7 col-xs-12" value="" /></div><div class="col-sm-2 col-xs-12"><label>Max books in row</label><input type="text" name="max_books_'+i+'" id="max_books_'+i+'" placeholder="Max books" class="form-control col-md-7 col-xs-12" value="" /></div><div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;"><label>&nbsp;</label><br clear="all" /><a href="javascript:void(0);" class="removeMoreRow" id="RemovePeriod_'+i+'" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current Period" /></a><a href="javascript:void(0);" class="addMoreRow" id="AddPeriod_'+i+'"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Grade" /></a></div></div></span>').appendTo(scntDiv);
    $('#currentPageForm #TotalRow').val(pi);
    $('#currentPageForm #TotalRowCount').val(i);
    
    $(this).closest('#Rowlocation').find('a.removeMoreRow').show();
    $(this).closest('#Rowlocation').find('a.addMoreRow').hide();
    $('#currentPageForm #RemovePeriod_'+i).hide();
    $('#currentPageForm #AddPeriod_'+i).show();
    return false;
  });
  
  $(document).on('click', '#currentPageForm .removeMoreRow', function() {  
    if( pi > 1 ) {
      $(this).parents('span').remove();
      pi--;
      $('#currentPageForm #TotalRow').val(pi);
    }
    return false;
  });
});
</script>