<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('bookListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      book details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    book details</li>
  <li class="pull-right"><a href="<?php echo correctLink('bookListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> 
        <span class="tools pull-left"> <?=$EDITDATA?'Edit':'Add'?> book details</span> 
        </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
           
          
            <div class="form-group">
              <label class="col-lg-3 control-label">Book name<span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="book_name" id="book_name"value="<?php if(set_value('book_name')): echo set_value('book_name'); else: echo stripslashes($EDITDATA['book_name']);endif; ?>" class="form-control required" placeholder="Book name">
                  <?php if(form_error('book_name')): ?>
                  <p for="book_name" generated="true" class="error"><?php echo form_error('book_name'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
               <div class="form-group">
              <label class="col-lg-3 control-label">Book Author <span class="required">*</span></label>
              <div class="col-lg-6">
                <input type="text" name="book_author" id="book_name"value="<?php if(set_value('book_author')): echo set_value('book_author'); else: echo stripslashes($EDITDATA['book_author']);endif; ?>" class="form-control required" placeholder="Book author">
                  <?php if(form_error('book_author')): ?>
                  <p for="book_author" generated="true" class="error"><?php echo form_error('book_author'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
            
             <div class="form-group">         
                <label class="col-lg-3 control-label"> Book Category<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('book_category_id')): $bookCatid	=	set_value('book_category_id'); elseif($EDITDATA['book_category_id']): $bookCatid	=	stripslashes($EDITDATA['book_category_id']); else: $bookCatid	=	''; endif; ?>
                  <select name="book_category_id" id="book_category_id" class="form-control required">
                  	<option value="">Select Book Category</option>
                    <?php if($BookCatData <> ""): foreach($BookCatData as $CATINFO): 
						
					?>
                    	<option value="<?php echo $CATINFO['encrypt_id']; ?>" <?php if($CATINFO['encrypt_id'] == $bookCatid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CATINFO['category_name']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('book_category_id')): ?>
                  <p for="book_category_id" generated="true" class="error"><?php echo form_error('book_category_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
             <div class="form-group">         
                <label class="col-lg-3 control-label">Book Type<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('book_type_id')): $booktid	=	set_value('book_type_id'); elseif($EDITDATA['book_type_id']): $booktid	=	stripslashes($EDITDATA['book_type_id']); else: $booktid	=	''; endif; ?>
                  <select name="book_type_id" id="book_type_id" class="form-control required">
                  	<option value="">Select Book Type</option>
                    <?php if($BTYPEDATA <> ""): foreach($BTYPEDATA as $BTYPEINFO): 
						
					?>
                    	<option value="<?php echo $BTYPEINFO['encrypt_id']; ?>" <?php if($BTYPEINFO['encrypt_id'] == $booktid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($BTYPEINFO['book_type']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('book_type_id')): ?>
                  <p for="book_type_id" generated="true" class="error"><?php echo form_error('book_type_id'); ?></p>
                  <?php endif; ?>
                </div>
             </div>
              
              
                 <div class="form-group">         
                <label class="col-lg-3 control-label">Book Language<span class="required">*</span></label>
                <div class="col-lg-6">
                  <?php if(set_value('book_language_id')): $booklid	=	set_value('book_language_id'); elseif($EDITDATA['book_language_id']): $booklid	=	stripslashes($EDITDATA['book_language_id']); else: $booklid	=	''; endif; ?>
                  <select name="book_language_id" id="book_type_id" class="form-control required">
                  	<option value="">Select Book Language</option>
                    <?php if($BLDATA <> ""): foreach($BLDATA as $BLINFO): 
						
					?>
                    	<option value="<?php echo $BLINFO['encrypt_id']; ?>" <?php if($BLINFO['encrypt_id'] == $booklid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($BLINFO['book_language']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                  <?php if(form_error('book_language_id')): ?>
                  <p for="book_language_id" generated="true" class="error"><?php echo form_error('book_language_id'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
          
         
          
          
            
               <div class="form-group  ">
              <label class="col-lg-3 control-label">Price &#8377<span class="required">*</span></label>
              <div class="col-lg-6">
              
                <input type="text" name="book_price" id="product_price"value="<?php if(set_value('book_price')): echo set_value('book_price'); else: echo stripslashes($EDITDATA['book_price']);endif; ?>" class="form-control required number" placeholder="Price">
                  <?php if(form_error('book_price')): ?>
                  <p for="book_price" generated="true" class="error"><?php echo form_error('book_price'); ?></p>
                  <?php endif; ?>
              </div>
            </div>
            
               <div class="form-group">
                    
                      <label class="col-lg-3 control-label">Book Description</label>
                       <div class="col-lg-6">
                      <?php if(set_value('book_description')): $content	= set_value('book_description'); elseif($EDITDATA['book_description']): $content	= $EDITDATA['book_description']; else: $content	= ''; endif; ?>
                      <textarea style="
    height: 100px;
    width: 463px;
" name="book_description"  class="" placeholder="Description"><?php echo stripslashes($content); ?></textarea>
                      <?php if(form_error('book_description')): ?>
                      <label for="book_description" generated="true" class="error"><?php echo form_error('book_description'); ?></label>
                      <?php endif; ?>
                    </div>
                 </div>
          
            
            
            
            
            
            <div class="form-group" style="margin-top:30px;">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('bookListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a>
                <span class="tools pull-right">
                <span class="btn btn-outline btn-default">Note
                    :- <strong><span style="color:#FF0000;">*</span> Indicates
                    required fields</strong> </span>
                </span>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>

   
    
  

 
    