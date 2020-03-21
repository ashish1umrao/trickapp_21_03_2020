<link rel="stylesheet" type="text/css" href="{ASSET_URL}css/jquery.timepicker.min.css" />
<script type="text/javascript" src="{ASSET_URL}js/jquery.timepicker.min.js"></script>
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('routeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      route details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    route details</li>
  <li class="pull-right"><a href="<?php echo correctLink('routeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          route details</span> </header>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <div class="form-group">
                <label class="col-lg-3 control-label">Route name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="route_name" id="route_name" value="<?php if(set_value('route_name')): echo set_value('route_name'); else: echo stripslashes($EDITDATA['route_name']); endif; ?>" class="form-control required" placeholder="Route name">
                  <?php if(form_error('route_name')): ?>
                  <p for="route_name" generated="true" class="error"><?php echo form_error('route_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Route short name<span class="required">*</span></label>
                <div class="col-lg-6">
                  <input type="text" name="route_short_name" id="route_short_name" value="<?php if(set_value('route_short_name')): echo set_value('route_short_name'); else: echo stripslashes($EDITDATA['route_short_name']); endif; ?>" class="form-control required" placeholder="Route short name">
                  <?php if(form_error('route_short_name')): ?>
                  <p for="route_short_name" generated="true" class="error"><?php echo form_error('route_short_name'); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <hr style="background-color:#8B5C7E; height:2px;"/>
              <?php 
                if(set_value('TotalRoute')): $TotalRoute = set_value('TotalRoute'); elseif($RDETAILDATA <> ""): $TotalRoute = count($RDETAILDATA); else: $TotalRoute = 1; endif; ?>
                <input type="hidden" name="TotalRoute" id="TotalRoute" value="<?php echo $TotalRoute; ?>" />
                <input type="hidden" name="TotalRouteCount" id="TotalRouteCount" value="<?php echo $TotalRoute; ?>" />
                <?php if($suberror): echo '<p generated="true" class="error">'.$suberror.'</p>'; endif; ?>
              <div class="col-md-12 col-sm-12 col-xs-12 padding-none" id="Routelocation">
              <?php if(set_value('TotalRoute')){ 
              for($i=1; $i<= $TotalRoute; $i++){
              $route_detail_id_     = 	'route_detail_id_'.$i;
			  $stop_name_      		= 	'stop_name_'.$i;
			  $stop_latitude_      	= 	'stop_latitude_'.$i;
			  $stop_longitude_      = 	'stop_longitude_'.$i;
              $pickup_time_     	= 	'pickup_time_'.$i;
              $drop_time_      		= 	'drop_time_'.$i;
              $fee_        			= 	'fee_'.$i;
              ?>
              <span><?php if($i > 1){ echo '<hr />'; } ?>
                <div class="form-group">
					<input type="hidden" name="route_detail_id_<?php echo $i; ?>" id="route_detail_id_<?php echo $i; ?>" value="<?php echo set_value($$route_detail_id_); ?>" />
                  <div class="col-sm-4 col-xs-12 padd-b-10">
                      <label>Stop name<span class="required"> * </span></label>
                      <input type="text" name="stop_name_<?php echo $i; ?>" id="stop_name_<?php echo $i; ?>" onkeyup="findAddress('<?php echo $i; ?>');" placeholder="Stop name*" class="form-control col-md-7 col-xs-12 required" value="<?php echo set_value($$stop_name_); ?>" />
                      <?php if(form_error('stop_name_'.$i)): ?>
                      <p for="stop_name_<?php echo $i; ?>" generated="true" class="error"><?php echo form_error('stop_name_'.$i); ?></p>
                      <?php endif; ?>
                      <input type="hidden" name="stop_latitude_<?php echo $i; ?>" id="stop_latitude_<?php echo $i; ?>" value="<?php echo set_value($$stop_latitude_); ?>" />
                      <input type="hidden" name="stop_longitude_<?php echo $i; ?>" id="stop_longitude_<?php echo $i; ?>" value="<?php echo set_value($$stop_longitude_); ?>" />
                  </div>
                  <div class="col-sm-2 col-xs-12">
                      <label>Pickup time<span class="required"> * </span></label>
                      <input type="text" name="pickup_time_<?php echo $i; ?>" id="pickup_time_<?php echo $i; ?>" placeholder="Pickup time*" class="form-control col-md-7 col-xs-12 required pickupTimepicker" value="<?php echo set_value($$pickup_time_); ?>" />
                      <?php if(form_error('pickup_time_'.$i)): ?>
                      <p for="pickup_time_<?php echo $i; ?>" generated="true" class="error"><?php echo form_error('pickup_time_'.$i); ?></p>
                      <?php endif; ?>
                  </div>
                 <div class="col-sm-2 col-xs-12 plr-5">
                      <label>Drop time<span class="required"> * </span></label>
                      <input type="text" name="drop_time_<?php echo $i; ?>" id="drop_time_<?php echo $i; ?>" placeholder="Drop time*" class="form-control col-md-7 col-xs-12 required dropTimepicker" value="<?php echo set_value($$drop_time_); ?>" />
                      <?php if(form_error('drop_time_'.$i)): ?>
                      <p for="drop_time_<?php echo $i; ?>" generated="true" class="error"><?php echo form_error('drop_time_'.$i); ?></p>
                      <?php endif; ?>
                  </div>
                  <div class="col-sm-2 col-xs-12 plr-5">
                    <label>Fee<span class="required"> * </span></label>
                    <input type="text" name="fee_<?php echo $i; ?>" id="fee_<?php echo $i; ?>" placeholder="Fee*" class="form-control col-md-7 col-xs-12 required" value="<?php echo set_value($$fee_); ?>" />
                    <?php if(form_error('fee_'.$i)): ?>
                      <p for="fee_<?php echo $i; ?>" generated="true" class="error"><?php echo form_error('fee_'.$i); ?></p>
                      <?php endif; ?>
                  </div>
                  <div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;">
                     <label>&nbsp;</label><br clear="all" />
                     <?php if($i < $TotalRoute){ ?>
                     <a href="javascript:void(0);" class="removeMoreRoute" id="RemoveRoute_<?php echo $i; ?>" style="display:block;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current Route" /></a>
                     <a href="javascript:void(0);" class="addMoreRoute" id="AddRoute_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more schedule" /></a>
                     <?php }else{ ?>
                     <a href="javascript:void(0);" class="removeMoreRoute" id="RemoveRoute_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current Route" /></a>
                     <a href="javascript:void(0);" class="addMoreRoute" id="AddRoute_<?php echo $i; ?>"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more schedule" /></a>
                     <?php } ?>
                  </div>
                </div>
              </span>
            <?php }  ?>
          <?php  }elseif($RDETAILDATA <> ""){ 
              $i=1; foreach($RDETAILDATA as $RDETAILINFO){ ?>
              <span><?php if($i > 1): echo '<hr />'; endif; ?>
                <div class="form-group">
                  <input type="hidden" name="route_detail_id_<?php echo $i; ?>" id="route_detail_id_<?php echo $i; ?>" value="<?php echo $RDETAILINFO['encrypt_id']; ?>" />
                  <div class="col-sm-4 col-xs-12 padd-b-10">
                      <label>Stop name<span class="required"> * </span></label>
                      <input type="text" name="stop_name_<?php echo $i; ?>" id="stop_name_<?php echo $i; ?>" onkeyup="findAddress('<?php echo $i; ?>');" placeholder="Stop name*" class="form-control col-md-7 col-xs-12 required" value="<?php echo $RDETAILINFO['stop_name']; ?>" />
                      <input type="hidden" name="stop_latitude_<?php echo $i; ?>" id="stop_latitude_<?php echo $i; ?>" value="<?php echo $RDETAILINFO['stop_latitude']; ?>" />
                      <input type="hidden" name="stop_longitude_<?php echo $i; ?>" id="stop_longitude_<?php echo $i; ?>" value="<?php echo $RDETAILINFO['stop_longitude']; ?>" />
                  </div>
                  <div class="col-sm-2 col-xs-12">
                      <label>Pickup time<span class="required"> * </span></label>
                      <input type="text" name="pickup_time_<?php echo $i; ?>" id="pickup_time_<?php echo $i; ?>" placeholder="Pickup time*" class="form-control col-md-7 col-xs-12 required pickupTimepicker" value="<?php echo $RDETAILINFO['pickup_time']; ?>" />
                  </div>
                  <div class="col-sm-2 col-xs-12 plr-5">
                     <label>Drop time<span class="required"> * </span></label>
                     <input type="text" name="drop_time_<?php echo $i; ?>" id="drop_time_<?php echo $i; ?>" placeholder="Drop time*" class="form-control col-md-7 col-xs-12 required dropTimepicker" value="<?php echo $RDETAILINFO['drop_time']; ?>" />
                   </div>
                   <div class="col-sm-2 col-xs-12 plr-5">
                      <label>Fee<span class="required"> * </span></label>
                      <input type="text" name="fee_<?php echo $i; ?>" id="fee_<?php echo $i; ?>" placeholder="Fee*" class="form-control col-md-7 col-xs-12 required" value="<?php echo $RDETAILINFO['fee']; ?>" />
                   </div>
                    <div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;">
                       <label>&nbsp;</label><br clear="all" />
                       <?php if($i < $TotalRoute): ?>
                       <a href="javascript:void(0);" class="addMoreRoute" id="AddRoute_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Route" /></a>
                       <?php else: ?>
                       <a href="javascript:void(0);" class="addMoreRoute" id="AddRoute_<?php echo $i; ?>"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Route" /></a>
                       <?php endif; ?>
                    </div>
                </div>
              </span>
            <?php $i++; } 
            }else{   ?>
                <span>
                <div class="form-group">
				  <input type="hidden" name="route_detail_id_1" id="route_detail_id_1" value="" />
                  <div class="col-sm-4 col-xs-12 padd-b-10">
                      <label>Stop name<span class="required"> * </span></label>
                      <input type="text" name="stop_name_1" id="stop_name_1" onkeyup="findAddress('1');" placeholder="Stop name*" class="form-control col-md-7 col-xs-12 required googleAutoSuggation" value="" />
                      <input type="hidden" name="stop_latitude_1" id="stop_latitude_1" value="" />
                      <input type="hidden" name="stop_longitude_1" id="stop_longitude_1" value="" />
                  </div>
                  <div class="col-sm-2 col-xs-12">
                      <label>Pickup time<span class="required"> * </span></label>
                      <input type="text" name="pickup_time_1" id="pickup_time_1" placeholder="Pickup time*" class="form-control col-md-7 col-xs-12 required pickupTimepicker" value="" />
                  </div>
                 <div class="col-sm-2 col-xs-12 plr-5">
                      <label>Drop time<span class="required"> * </span></label>
                      <input type="text" name="drop_time_1" id="drop_time_1" placeholder="Drop time*" class="form-control col-md-7 col-xs-12 required dropTimepicker" value="" />
                  </div>
                  <div class="col-sm-2 col-xs-12 plr-5">
                    <label>Fee<span class="required"> * </span></label>
                    <input type="text" name="fee_1" id="fee_1" placeholder="Fee*" class="form-control col-md-7 col-xs-12 required" value="" />
                  </div>
                  <div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;">
                     <label>&nbsp;</label><br clear="all" />
                      <a href="javascript:void(0);" class="removeMoreRoute" id="RemoveRoute_<?php echo $i; ?>" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current Route" /></a>
                       <a href="javascript:void(0);" class="addMoreRoute" id="AddRoute_<?php echo $i; ?>"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Route" /></a>
                  </div>

                </div>
              </span>
            <?php } ?>
             </div>
            <div class="form-group">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Submit" class="btn btn-primary" />
                <a href="<?php echo correctLink('routeListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
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
  var scntDiv   =   $('#currentPageForm #Routelocation');
  var pi      	=   $('#currentPageForm #Routelocation > span').size(); 
  
  $(document).on('click', '#currentPageForm .addMoreRoute', function() { 
    if($('#currentPageForm #class_name').val() == '')
    {
      alert('Please enter class name.');
      return false;
    }
    if($('#currentPageForm #class_short_name').val() == '')
    {
      alert('Please enter class short name.');
      return false;
    }
    var i     =   parseInt($('#currentPageForm #TotalRouteCount').val());
    i++;
    pi++;
    $('<span><hr /><div class="form-group"><input type="hidden" name="route_detail_id_'+i+'" id="route_detail_id_'+i+'" value="" /><div class="col-sm-4 col-xs-12 padd-b-10"><label>Stop name<span class="required">*</span></label><input type="text" name="stop_name_'+i+'" id="stop_name_'+i+'" onkeyup="findAddress('+i+');" placeholder="Stop name*" class="form-control col-md-7 col-xs-12 required" value="" /><input type="hidden" name="stop_latitude_'+i+'" id="stop_latitude_'+i+'" value="" /><input type="hidden" name="stop_longitude_'+i+'" id="stop_longitude_'+i+'" value="" /></div><div class="col-sm-2 col-xs-12"><label>Pickup time<span class="required">*</span></label><input type="text" name="pickup_time_'+i+'" id="pickup_time_'+i+'" placeholder="Pickup time*" class="form-control col-md-7 col-xs-12 required pickupTimepicker" value="" /></div><div class="col-sm-2 col-xs-12 plr-5"><label>Drop time<span class="required">*</span></label><input type="text" name="drop_time_'+i+'" id="drop_time_'+i+'" placeholder="Drop time*" class="form-control col-md-7 col-xs-12 required dropTimepicker" value="" /></div><div class="col-sm-2 col-xs-12 plr-5"><label>Fee<span class="required">*</span></label><input type="text" name="fee_'+i+'" id="fee_'+i+'" placeholder="Fee*" class="form-control col-md-7 col-xs-12 required" value="" /></div><div class="col-sm-2 col-xs-12 plr-5" style="text-align:center;"><label>&nbsp;</label><br clear="all" /><a href="javascript:void(0);" class="removeMoreRoute" id="RemoveRoute_'+i+'" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/cross.png" alt="Remove current Route" /></a><a href="javascript:void(0);" class="addMoreRoute" id="AddRoute_'+i+'"><img src="<?php echo base_url(); ?>assets/images/addmore.png" alt="Add more Grade" /></a></div></div></span>').appendTo(scntDiv);
    $('#currentPageForm #TotalRoute').val(pi);
    $('#currentPageForm #TotalRouteCount').val(i);
    
    $(this).closest('#Routelocation').find('a.removeMoreRoute').show();
    $(this).closest('#Routelocation').find('a.addMoreRoute').hide();
    $('#currentPageForm #RemoveRoute_'+i).hide();
    $('#currentPageForm #AddRoute_'+i).show();
	
	runTimePickerScript();
    return false;
  });
  
  $(document).on('click', '#currentPageForm .removeMoreRoute', function() {  
    if( pi > 1 ) {
      $(this).parents('span').remove();
      pi--;
      $('#currentPageForm #TotalRoute').val(pi);
    }
    return false;
  });
});
</script>  
<script>//AIzaSyCfN5cKm8i7H02FBzagmKAD5mEo-0jfNNk
function findAddress(curNum) {
	var input = document.getElementById('stop_name_'+curNum);
	var autocomplete = new google.maps.places.Autocomplete(input);
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		var place = autocomplete.getPlace();  
		document.getElementById('stop_latitude_'+curNum).value = place.geometry.location.lat();
		document.getElementById('stop_longitude_'+curNum).value = place.geometry.location.lng();
	});
}</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQORpXzrBoh92Ryk8qWJx-s3OHBLGZRQQ&libraries=places" async defer></script>           
<script>
function runTimePickerScript(){
	$('.pickupTimepicker').timepicker({
		'minTime': '6:00am',
		'maxTime': '12:00pm',
		'step': 5,
		'showDuration': true
	});
	$('.dropTimepicker').timepicker({
		'minTime': '10:00am',
		'maxTime': '04:00pm',
		'step': 5,
		'showDuration': true
	});
}	
$(function() {
	runTimePickerScript();
});
</script>