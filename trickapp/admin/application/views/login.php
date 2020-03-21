<div class="log-w3">
  <div class="w3layouts-main">
    <h2>Sign In Now</h2>
    <form name="LoginForm" id="LoginForm" action="" method="post">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
      <fieldset>
      <?php if($error): ?>
      <div class="alert alert-danger">
        <?=$error?>
      </div>
      <?php endif; ?>
      <div class="form-group">
        <input type="text" name="userEmail" id="userEmail" class="ggg required" value="<?php if(set_value('userEmail')): echo set_value('userEmail'); else: echo get_cookie('SMSADMINUserEmail');endif; ?>" placeholder="Email" autocomplete="off"/>
        <?php if(form_error('userEmail')): ?>
        <label for="userEmail" generated="true" class="error"><?php echo form_error('userEmail'); ?></label>
        <?php endif; ?>
      </div>
      <div class="form-group">
        <input type="password" name="userPassword" id="userPassword" class="ggg required" value="<?php if(set_value('userPassword')): echo set_value('userPassword'); else: echo get_cookie('SMSADMINUserPass');endif; ?>" placeholder="Password" autocomplete="off"/>
        <?php if(form_error('userPassword')): ?>
        <label for="userPassword" generated="true" class="error"><?php echo form_error('userPassword'); ?></label>
        <?php endif; ?>
      </div>
      <div class="checkbox">
        <label>
        <input type="checkbox" name="rememberMe" id="rememberMe" value="YES" <?php if(get_cookie('SMSADMINRememberMe')) echo 'checked="checked"';?> />
        Remember me </label>
        <?php /*?><h6><a href="javascript:void(0);">Forgot Password?</a></h6><?php */?>
      </div>
      <input type="submit" name="loginFormSubmit" id="loginFormSubmit" value="Sign In">
      </fieldset>
    </form>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#LoginForm").validate();
});
</script>
