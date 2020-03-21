<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDQORpXzrBoh92Ryk8qWJx-s3OHBLGZRQQ&libraries=places"></script>
<ol class="breadcrumb">
  <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
  <li><a href="<?php echo correctLink('studentListAdminDatastudentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>">Manage
      student details</a></li>
  <li class="active">
    <?=$EDITDATA?'Edit':'Add'?>
    student transport details</li>
  <li class="pull-right"><a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Back</a></li>
</ol>
{message}
<div class="form-w3layouts">
    <ul class="nav nav-tabs blue_tab">
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdata/<?php echo $studentId; ?>">Personal</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditparents/<?php echo $studentId; ?>">Parents</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditaddress/<?php echo $studentId; ?>">Address</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addedithealth/<?php echo $studentId; ?>">Health</a></li>
      <li class="active"><a href="javascript:void(0);">Transport</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditqrcode/<?php echo $studentId; ?>">Student QRCode</a></li>
      <li><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addeditdoc/<?php echo $studentId; ?>">Upload Documents</a></li>
    </ul>
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
        <header class="panel-heading"> <span class="tools pull-left">
          <?=$EDITDATA?'Edit':'Add'?>
          student transport details</span> </header>
            <?php 
       echo   $studentDetails ;  ?>
        <div class="panel-body">
          <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?=$EDITDATA['encrypt_id']?>"/>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
            <fieldset>
            <legend> Assign transport</legend>
            <div>
              <div class="col-lg-12">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label class="col-lg-2 control-label">Transport<span class="required">*</span></label>
                    <div class="col-lg-10">
                      <?php if(set_value('route_assign_id')): $routeassignid  = set_value('route_assign_id'); elseif($EDITDATA['route_assign_id']): $routeassignid = $EDITDATA['route_assign_id']; else: $routeassignid  = $routeAssignId; endif; ?>
                      <select name="route_assign_id" id="route_assign_id" class="form-control required">
                        <option value="">Select transport</option>
                        <?php if($TRANPORTDATA <> ""): foreach($TRANPORTDATA as $TRANPORTINFO): ?>
                        <option value="<?php echo $TRANPORTINFO['encrypt_id']; ?>" <?php if($TRANPORTINFO['encrypt_id'] == $routeassignid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($TRANPORTINFO['route_name'].' - '.$TRANPORTINFO['vehicle_type_name']); ?></option>
                        <?php endforeach; endif; ?>
                      </select>
                      <?php if(form_error('route_assign_id')): ?>
                      <p for="route_assign_id" generated="true" class="error"><?php echo form_error('route_assign_id'); ?></p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <?php if(in_array($EDITDATA['student_qunique_id'],$TASSIGNDATA)): ?>
              <div class="col-lg-12">
                <table class="table border-none" style="margin:20px 0px;">
                  <thead>
                    <tr>
                      <th align="left" colspan="4" style="background:#00FF00; color:#000;"><strong>This Bus assigned to you</strong></th>
                    </tr>
                  </thead>
                </table>
              </div>
              <?php elseif(($ASSIGNDATA['max_seat_extend'] && $TASSIGNDATA) && $ASSIGNDATA['max_seat_extend'] == count($TASSIGNDATA)): ?>
              <div class="col-lg-12">
                <table class="table border-none" style="margin:20px 0px;">
                  <thead>
                    <tr>
                      <th align="left" colspan="4" style="background:#FF0000; color:#000;"><strong>Seat not available in this bus</strong></th>
                    </tr>
                  </thead>
                </table>
              </div>
              <?php endif; ?>
              <?php if($ROUTEDDATA): ?>
              <div class="col-lg-12">
                <table class="table border-none">
                <tbody>
                  <tr>
                    <td colspan="4"><table class="table border-none">
                        <thead>
                          <tr>
                            <th align="left" colspan="4"><strong>Route details</strong></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td align="left" width="20%"><strong>Route name</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ROUTEDATA['route_name']); ?></td>
                            <td align="left" width="20%"><strong>Route short name</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ROUTEDATA['route_short_name']); ?></td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  <tr>
                    <td colspan="4"><table class="table border-none">
                        <thead>
                          <tr>
                            <th align="left" colspan="4"><strong>Assign details</strong></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if($ASSIGNDATA <> ""): ?>
                          <tr>
                            <td align="left" width="20%"><strong>Vehicle no</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ASSIGNDATA['vehicle_no']); ?></td>
                            <td align="left" width="20%"><strong>Vehicle type</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ASSIGNDATA['vehicle_type_name']); ?></td>
                          </tr>
                          <tr>
                            <td align="left" width="20%"><strong>No of seat</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ASSIGNDATA['no_of_seat']); ?></td>
                            <td align="left" width="20%"><strong>Maximum seat extend</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ASSIGNDATA['max_seat_extend']); ?></td>
                          </tr>
                          <tr>
                            <td align="left" width="20%"><strong>Driver name</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ASSIGNDATA['driver_name']); ?></td>
                            <td align="left" width="20%"><strong>Driver email</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ASSIGNDATA['driver_email']); ?></td>
                          </tr>
                          <tr>
                            <td align="left" width="20%"><strong>Conductor name</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ASSIGNDATA['conductor_name']); ?></td>
                            <td align="left" width="20%"><strong>Conductor email</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ASSIGNDATA['conductor_email']); ?></td>
                          </tr>
                          <tr>
                            <td align="left" width="20%"><strong>Attendant name</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ASSIGNDATA['attendant_name']); ?></td>
                            <td align="left" width="20%"><strong>Attendant email</strong></td>
                            <td align="left" width="30%"><?php echo stripslashes($ASSIGNDATA['attendant_email']); ?></td>
                          </tr>
                          <?php else: ?>
                          <tr>
                            <th align="left" colspan="4">Assign data not available</th>
                          </tr>
                          <?php endif; ?>
                        </tbody>
                      </table></td>
                  </tr>
                  <tr>
                    <td colspan="4"><table class="table border-none">
                        <thead>
                          <tr>
                            <th align="left" colspan="4"><strong>Stop details</strong></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td align="left" width="40%"><strong>Stop name</strong></td>
                            <td align="left" width="20%"><strong>Pickup time</strong></td>
                            <td align="left" width="20%"><strong>Drop time</strong></td>
                            <td align="left" width="20%"><strong>Fee</strong></td>
                          </tr>
                          <?php if($ROUTEDDATA <> ""): foreach($ROUTEDDATA as $ROUTEDINFO): ?>
                          <tr>
                            <td align="left" width="40%"><?php echo stripslashes($ROUTEDINFO['stop_name']); ?></td>
                            <td align="left" width="20%"><?php echo stripslashes($ROUTEDINFO['pickup_time']); ?></td>
                            <td align="left" width="20%"><?php echo stripslashes($ROUTEDINFO['drop_time']); ?></td>
                            <td align="left" width="20%"><?php echo stripslashes($ROUTEDINFO['fee']); ?></td>
                          </tr>
                          <?php endforeach; ?>
                          <tr>
                            <td colspan="4"><div id="map_canvas" style="width:100%;height:500px;"></div></td>
                          </tr>
                          <?php  endif; ?>
                        </tbody>
                      </table></td>
                  </tr>
                </tbody>
              </table>
              </div>
              <?php endif; ?>
            </fieldset>
            <?php if($ROUTEDDATA && !in_array($EDITDATA['student_qunique_id'],$TASSIGNDATA) && $ASSIGNDATA['max_seat_extend'] > count($TASSIGNDATA)):  ?>
            <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                <input type="submit" name="SaveChanges" id="SaveChanges" value="Assign" class="btn btn-primary" />
                <a href="<?php echo correctLink('studentListAdminData','{FULL_SITE_URL}{CURRENT_CLASS}/index'); ?>" class="btn btn-default">Cancel</a> <span class="tools pull-right"> <span class="btn btn-outline btn-default">Note
                :- <strong><span style="color:#FF0000;">*</span> Indicates required
                fields</strong> </span> </span> </div>
            </div>
            <?php endif; ?>
          </form>
        </div>
      </section>
    </div>
  </div>
</div>
<script>
$(document).on('change','#route_assign_id',function(){
	var routeAssignId		=	$(this).val();
	window.location.href 	= 	'<?php echo $changeUrl; ?>'+routeAssignId;
});
</script>
<?php if($ROUTEDDATA <> ""): ?>
<script type="text/javascript">
function initialize()
{
	var map = new	google.maps.Map(
						document.getElementById('map_canvas'),
						{
							scaleControl: true,
							zoomControl: true,
							mapTypeControl: true,
							streetViewControl: true,
							rotateControl: true,
							fullscreenControl: true,
							/*center: new google.maps.LatLng(37.4419, -122.1419),
							zoom: 4,*/
							mapTypeId: 'roadmap'
						}
					);
	var PLOTS=[
	 <?php $i=1; foreach($ROUTEDDATA as $ROUTEDINFO1): ?>
	 <?php if($i>1): echo ','; endif; ?>{line_count:"<?php echo $i; ?>",lat:"<?php echo $ROUTEDINFO1['stop_latitude']; ?>",lng:"<?php echo $ROUTEDINFO1['stop_longitude']; ?>",note:"<?php echo $ROUTEDINFO1['stop_name']; ?>"}
	 <?php $i++; endforeach; ?>
	 /*{line_count:"2",lat:"28.570317",lng:"77.32181960000003",note:"City of Angels"},
	 {line_count:"6",lat:"28.6310034",lng:"77.3820045",note:"Degrees Work!"}*/
	];
	var latlngbounds = new google.maps.LatLngBounds(); /* Used to set boundary (for map zooming/centering) */
	// Markers for plots...
	for(var i=0; i < PLOTS.length; i++)
	{
		LatLng={lat:Number(PLOTS[i]['lat']),lng:Number(PLOTS[i]['lng'])};
		latlngbounds.extend(LatLng); /* Extend map boundary */
		//alert(PLOTS[i]['lat']);
		var marker = new	google.maps.Marker(
							{
								position: LatLng,
								map: map,
								/*icon: {
									path: google.maps.SymbolPath.CIRCLE,
									fillColor: '#F00',
									fillOpacity: 0.7,
									strokeColor: '#00A',
									strokeOpacity: 0.9,
									strokeWeight: 1,
									scale: 8
								},*/
								title: "Line: "+PLOTS[i]['line_count']+"\nLat/Lng: "+PLOTS[i]['lat']+","+PLOTS[i]['lng']+"\nComment: "+PLOTS[i]['note']
							}
						);
	
	//var infowindow = new google.maps.InfoWindow({content: "<b>Line:</b> "+PLOTS[i]['line_count']+"<div><b>Lat/Lng:</b> "+PLOTS[i]['lat']+","+PLOTS[i]['lng']+"<br><b>Comment:</b> "+PLOTS[i]['note']+"</div>"});
	//infowindow.open(map, marker);
	}
	map.setCenter(latlngbounds.getCenter(), map.fitBounds(latlngbounds));
	var lineCords = [
		<?php $j=1; foreach($ROUTEDDATA as $ROUTEDINFO2): ?>
		<?php if($j>1): echo ','; endif; ?>{lat:<?php echo $ROUTEDINFO2['stop_latitude']; ?>,lng:<?php echo $ROUTEDINFO2['stop_longitude']; ?>}
		<?php $j++; endforeach; ?>
		];
	var line = new google.maps.Polyline({
		path: lineCords,
		geodesic: true,
		strokeColor: '#FF0000',
		strokeOpacity: 1.0,
		strokeWeight: 2
	});
	line.setMap(map);
}
initialize();
</script>
<?php  endif; ?>