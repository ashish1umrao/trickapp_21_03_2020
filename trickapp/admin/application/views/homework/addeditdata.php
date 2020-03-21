<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('newsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
     Home Work</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
   Home Work</li>
  <li class="pull-right"><a href="<?php echo correctLink('newsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          Home Work</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <div class="form-group">
              <label class="col-lg-3 control-label">News Title<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="news_title" id="news_title"value="<?php if(set_value('news_title')): echo set_value('news_title'); else: echo stripslashes($EDITDATA['news_title']);endif; ?>" class="form-control required" placeholder="News Title">
                <?php if(form_error('news_title')): ?>
                <p for="news_title" generated="true" class="error"><?php echo form_error('news_title'); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Content<span class="required">*</span></label>
              <div class="col-lg-6">
                <?php if(set_value('news_description')): $newsdescription = set_value('news_description'); elseif($EDITDATA['news_description']): $newsdescription = stripslashes($EDITDATA['news_description']); else: $newsdescription = ''; endif; ?>
                  <textarea name="news_description" id="news_description" ><?php echo $newsdescription; ?></textarea>
                  <?php if(form_error('news_description')): ?>
                  <p for="news_description" generated="true" class="error"><?php echo form_error('news_description'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Picture<span class="required">*</span></label>
              <div class="col-lg-8"> <span style="display:inline-block;" id="uploadIds0"><img class="img-responsive" src="{ASSET_URL}images/browse-white.png" border="0" alt="" /></span><br clear="all" />
                <input type="text" id="uploadimage0" name="uploadimage0" value="<?php if(set_value('uploadimage0')): echo set_value('uploadimage0'); else: echo stripslashes($EDITDATA['news_image']); endif; ?>" class="browseimageclass" />
                <br clear="all">
                <?php if(form_error('uploadimage0')): ?>
                <label for="uploadimage0" generated="true" class="error"><?php echo form_error('uploadimage0'); ?></label>
                <?php endif; ?>
                <label id="uploadstatus0" class="error"></label>
                <div id="uploadloader0" style="margin:0 0 10px 0px; float:left;display:none;"><img src="{ASSET_URL}images/ajax-loader.gif" border="0" /></div>
                <span id="uploadphoto0" style="float:left;">
                <?php if(set_value('uploadimage0')):?>
                <img src="<?php echo stripslashes(set_value('uploadimage0'))?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes(set_value('uploadimage0'))?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                <?php elseif($EDITDATA['news_image']):?>
                <img src="<?php echo stripslashes($EDITDATA['news_image'])?>" width="100" border="0" alt="" /> <a href="javascript:void(0);" onClick="DeleteImage('<?php echo stripslashes($EDITDATA['news_image'])?>','0');"> <img src="{ASSET_URL}images/cross.png" border="0" alt="" /> </a>
                <?php endif; ?>
                </span> </div>
            </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('newsAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
  create_editor_for_textarea('news_description');
});
</script>
<script>
$(function(){
  UploadImage('0');
});
</script>
