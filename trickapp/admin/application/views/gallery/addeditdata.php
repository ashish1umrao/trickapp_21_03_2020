<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('galleryAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      gallery details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    gallery details</li>
  <li class="pull-right"><a href="<?php echo correctLink('galleryAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          gallery details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <div class="form-group">
              <label class="col-lg-3 control-label">Name<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="gallery_name" id="gallery_name"value="<?php if(set_value('gallery_name')): echo set_value('gallery_name'); else: echo stripslashes($EDITDATA['gallery_name']);endif; ?>" class="form-control required" placeholder="Name">
                <?php if(form_error('gallery_name')): ?>
                <p for="gallery_name" generated="true" class="error"><?php echo form_error('gallery_name'); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">&nbsp;</label>
              <div class="col-lg-9">
                <div id="galleryImagePhoto" style="float:left;">
                <?php if($IMAGES): foreach($IMAGES as $IMAGEVal):?>
                <span id="<?php echo stripslashes($IMAGEVal['encrypt_id'])?>"><img src="<?php echo stripslashes($IMAGEVal['image_name'])?>" width="100" border="0" alt="" /><img src="{ASSET_URL}images/cross.png" border="0" alt="" class="deletePrevImage" /></span>
                <?php endforeach; endif; ?>
                </div> 
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Picture<span class="required">*</span></label>
              <div class="col-lg-8"> <span style="display:inline-block;" id="galleryImageId"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                <input type="text" id="image_name" name="image_name" value="" class="browseimageclass" />
                <br clear="all">
                <label id="galleryImageStatus" class="error"></label>
                <div id="galleryImageLoader" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div> 
              </div>
            </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('galleryAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
<script type="text/javascript">
  var imageData     = [];
  $(function(){
    var btnUpload=$('#galleryImageId');
    var status=$('#galleryImageStatus');
  
    new AjaxUpload(btnUpload, {
      action: FULLSITEURL+CURRENTCLASS+'/uplode_image',
      name: 'uploadfile',
      onSubmit: function(file, ext){
        if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
          status.text('Only JPG, PNG, GIF files are allowed');
          return false;
        }     
        status.text('Uploding.....');
      },
      onComplete: function(file, response){ 
        //alert(response);
        responsedata  = response.split('_____');
        if(responsedata[0] == "ERROR"){ 
          status.text(responsedata[1]);
          return false;
        }
        status.text('');
        if(responsedata[0] != "UPLODEERROR"){ 
          $('#galleryImagePhoto').append('<span><img src="'+responsedata[0]+'" border="0" width="100" /><img src="'+BASEURL+'assets/images/cross.png" border="0" alt="" class="deleteCurrentImage" /></span>');
          imageData.push(responsedata[0]);
          $('#image_name').val(imageData.join('_____'));
        }
      }
    });
  });
  $(document).on('click','.deleteCurrentImage',function(){
    if(confirm("Sure to delete?"))
    {   var curobj    = $(this);
        var imagename = curobj.prev('img').attr('src');
        $.ajax({
         type: 'post',
              url: FULLSITEURL+CURRENTCLASS+'/DeleteCurrentImage',
       data: {csrf_api_key:csrf_api_value,imagename:imagename},
              success: function(response) { 
                imageData = jQuery.grep(imageData, function(value) {
                  return value != imagename;
                });
                $('#image_name').val(imageData.join('_____'));
                curobj.parents('span').hide();
              }
            });
    }
  });
  $(document).on('click','.deletePrevImage',function(){
    if(confirm("Sure to delete?"))
    {   var curobj    = $(this);
        var imagename = curobj.prev('img').attr('src');
        var imageid   = curobj.parent('span').attr('id');
        $.ajax({
         type: 'post',
              url: FULLSITEURL+CURRENTCLASS+'/DeletePrevImage',
       data: {csrf_api_key:csrf_api_value,imagename:imagename,imageid:imageid},
              success: function(response) { 
                curobj.parents('span').hide();
              }
            });
    }
  });
</script>