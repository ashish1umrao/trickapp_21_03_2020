<link href="{ASSET_FRONT_URL}calendar/fullcalendar.min.css" rel="stylesheet" />
<link href="{ASSET_FRONT_URL}calendar/fullcalendar.print.min.css" rel="stylesheet" media="print" />
<script src="{ASSET_FRONT_URL}calendar/lib/moment.min.js"></script>
<script src="{ASSET_FRONT_URL}calendar/lib/jquery.min.js"></script>
<script src="{ASSET_FRONT_URL}calendar/fullcalendar.min.js"></script>
<style type="text/css">
    .mail-contnet {width:100%!important;}
    .table td, .table th {padding: 0.30rem!important;}
</style>
<script>
    $(document).ready(function() {
       $("#calendar").fullCalendar({
            header: {

               
                left: 'prev,next today',
                center: 'title',
                right: 'month',
                leftBackgroundColor: '#030327'

            },
            defaultView: 'month',

            eventTextColor: '#F000CC',
            defaultDate: '<?php echo date("Y-m-d");?>',
            minTime: '06:00:00',
            maxTime: '24:00:00',
            navLinks: true, // can click day/week names to navigate views
            selectable: false,//true,
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            eventOverlap: false,
            selectHelper: true,
            select: function(start, end) {},
            eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) { },
            dayClick: function(start, end) {
                getScheduleData(start.format('YYYY-MM-DD')); 
            },
            eventClick: function(calEvent, jsEvent, view) {
                getScheduleData(calEvent.start.format('YYYY-MM-DD'));
            },
            events:{
                //url: FRONTFULLSITEURL+'get_event',
                url: FULLSITEURL+CURRENTCLASS+'/get_event',
                type: 'POST', // Send post data
                error: function() {
                    alert('There was an error while fetching events.');
                }
            }
       });
    });
</script>


<!-- <script>
    $(document).ready(function() {
       $("#calendar").fullCalendar({
            header: {

               
                left: 'prev,next today',
                center: 'title',
                right: 'month',
                leftBackgroundColor: '#030327'

            },
            defaultView: 'month',

            eventTextColor: '#F000CC',
            defaultDate: '<?php echo date("Y-m-d");?>',
            minTime: '06:00:00',
            maxTime: '24:00:00',
            navLinks: true, // can click day/week names to navigate views
            selectable: false,//true,
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            eventOverlap: false,
            selectHelper: true,
            select: function(start, end) {},
            eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) { },
            dayClick: function(start, end) {
              getScheduleDataevent(start.format('YYYY-MM-DD')); 
            },
            eventClick: function(calEvent, jsEvent, view) {
                getScheduleDataevent(calEvent.start.format('YYYY-MM-DD'));
            },
            events:{
                //url: FRONTFULLSITEURL+'get_event',
                url: FULLSITEURL+CURRENTCLASS+'/get_event_exam',
                type: 'POST', // Send post data
                error: function() {
                    alert('There was an error while fetching events.');
                }
            }
       });
    });
</script> -->

<?php
/*function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
     foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}
$data[0] = array('volume' => '2018-02-02 12:07:43', 'edition' => 2);
$data[1] = array('volume' => '2018-02-02 12:09:43', 'edition' => 1);
$data[2] = array('volume' => '2018-02-05 12:07:43', 'edition' => 6);
$data[3] = array('volume' => '2018-02-07 12:07:43', 'edition' => 2);
$data[4] = array('volume' => '2018-04-02 12:07:43', 'edition' => 6);
$data[5] = array('volume' => '2019-01-08 12:07:43', 'edition' => 7);

// Pass the array, followed by the column names and sort flags
$sorted = array_orderby($data, 'volume', SORT_DESC, 'edition', SORT_ASC);
echo '<pre>'; print_r($sorted); die;*/
?>
{message}
<style>
  .market-update-right i{
    font-size: 3em;
    color: #fff;
    text-align: left;
}
.number-strip{
      background: #fff;
    width: 64px;
    right: 0;
    margin-right: 15px;
    padding: 5px 10px;
    position: absolute;
    top: 0;
}
.number-strip p{
  text-align:center;
  color:#000;
  font-family: 'Roboto', sans-serif;
  font-weight:600;
  font-size:20px;
}
</style>
<section class="wrapper">
  <div class="market-updates">
    <a href="studentlist/index">
    <div class="col-md-3 market-update-gd">
      <div class="market-update-block clr-block-2">
        <div class="col-md-4 market-update-right"> <i class="fa fa-users"> </i> </div>
        <div class="col-md-8 market-update-left">
          <h4>Student</h4>
          <h3><?=$ALLSTUDENTCOUNT; ?></h3>

         <!--<p>Other hand, we denounce</p>-->
        </div>
        <div class="clearfix"> </div>
      </div>
     
    </div>
  </a>
  <a href="teacherlist/index">
    <div class="col-md-3 market-update-gd">
      <div class="market-update-block clr-block-1">
        <div class="col-md-4 market-update-right"> <i class="fa fa-user"></i> </div>
        <div class="col-md-8 market-update-left">
           <h4>Employee</h4>
          <h3><?=$ALLSTEACHERCOUNT; ?></h3>
         <!--<p>Other hand, we denounce</p>-->
        </div>
        <div class="clearfix"> </div>
      </div>
     
    </div>
  </a>
  <a href="adminclasswisepayfee/index">
    <div class="col-md-3 market-update-gd">
      <div class="market-update-block clr-block-3">
        <div class="col-md-4 market-update-right"> <i class="fa fa-inr"></i> </div>
        <div class="col-md-8 market-update-left">
          <h4>Fees Due</h4>
          <h3>0</h3>
         <!--  <p>Other hand, we denounce</p> -->
        </div>
        <div class="clearfix"> </div>
      </div>
     
    </div>
  </a>
  <a href="adminclasswisepayfee/index">
    <div class="col-md-3 market-update-gd">
      <div class="market-update-block clr-block-4">
        <div class="col-md-4 market-update-right">  <i class="fa fa-inr"></i> </div>
        <div class="col-md-8 market-update-left">
          <h4>Received Fees</h4>
          <h3>0</h3>
         <!--  <p>Other hand, we denounce</p> -->
        </div>
        <div class="clearfix"> </div>
      </div>
     
    </div>
</a>

    <div class="clearfix"> </div>
  </div>

  <div class="market-updates">
    <a href="notification/index">
    <div class="col-md-3 market-update-gd">
      <div class="market-update-block clr-block-2">
        <div class="col-md-4 market-update-right"> <i class="fa fa-bell"></i> </div>
        <div class="col-md-8 market-update-left">
           <h4>Notification</h4>
          <h3><?=$ALLNOTIFICATIONCOUNT; ?></h3>
         <!--  <p>Other hand, we denounce</p> -->
        </div>
        <div class="clearfix"> </div>
      </div>
     
    </div>
  </a>
  <a href="Adminsendstudentsms/index">
    <div class="col-md-3 market-update-gd">
      <div class="market-update-block clr-block-1">
        <div class="col-md-4 market-update-right"> <i class="fa fa-comment"></i> </div>
        <div class="col-md-8 market-update-left">
           <h4>SENT SMS</h4>
          <h3><?=$ALLSMSCOUNT; ?></h3>
         <!--  <p>Other hand, we denounce</p> -->
        </div>
        <div class="clearfix"> </div>
      </div>
     
    </div>
  </a>
    <a href="noticeboard/index">
    <div class="col-md-3 market-update-gd">
      <div class="market-update-block clr-block-3">
        <div class="col-md-4 market-update-right"> <i class="fa fa-file"></i> </div>
        <div class="col-md-8 market-update-left">
           <h4>Noticeboard</h4>
          <h3><?=$ALLNOTICEBOARDCOUNT; ?></h3>
         <!--  <p>Other hand, we denounce</p> -->
        </div>
        <div class="clearfix"> </div>
      </div>
     
    </div>
  </a>
  <a href="holidaylist/index">
    <div class="col-md-3 market-update-gd">
      <div class="market-update-block clr-block-4">
        <div class="col-md-4 market-update-right"> <i class="fa fa-sun-o"></i> </div>
        <div class="col-md-8 market-update-left">
           <h4>Holiday</h4>
          <h3><?=$ALLHOLIDAYCOUNT; ?></h3>
         <!--  <p>Other hand, we denounce</p> -->
        </div>
        <div class="clearfix"> </div>
      </div>
     
    </div>
</a>    
    <div class="clearfix"> </div>
  </div>
  <?php if(sessionData('SMS_ADMIN_TYPE') == 'Super admin'): ?>
        <div class="market-updates">
          <?php //if($ALLADMINSMSCOUNT <> ""): $i=1; foreach($ALLADMINSMSCOUNT as $branchsmsdata): ?>
              <a href="adminsmsusers/index">
                <div class="col-md-3 market-update-gd">
                  <div class="market-update-block clr-block-3">
                    <div class="col-md-4 market-update-right"> <i class="fa fa-sun-o"></i> </div>
                    <div class="col-md-8 market-update-left">
                      <h4>Total Sms</h4>
                      <h3><?=$ALLSUPERADMINSMSCOUNT; ?></h3>
                    <!--  <p>Other hand, we denounce</p> -->
                    </div>
                    <div class="clearfix"> </div>
                  </div>
                </div>
              </a>
          <?php //endforeach; endif;?>
            <div class="clearfix"> </div>
          </div>
          <?php else:?>
            <div class="market-updates">
          <?php //if($ALLADMINSMSCOUNT <> ""): $i=1; foreach($ALLADMINSMSCOUNT as $branchsmsdata): ?>
              <a href="adminsmsusers/index">
                <div class="col-md-3 market-update-gd">
                  <div class="market-update-block clr-block-3">
                    <div class="col-md-4 market-update-right"> <i class="fa fa-sun-o"></i> </div>
                    <div class="col-md-8 market-update-left">
                      <h4>Total Sms</h4>
                      <h3><?=$ALLADMINSMSCOUNT; ?></h3>
                    <!--  <p>Other hand, we denounce</p> -->
                    </div>
                    <div class="clearfix"> </div>
                  </div>
                </div>
              </a>
          <?php //endforeach; endif;?>
            <div class="clearfix"> </div>
          </div>


          <?php endif; ?>
 <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12 padding-none">
            
            <div class="row">
            
              <div class="col-md-12" id="calendersection">
                <div class="panel panel-default panal-comman">
                  <div class="panel-heading">
                    <h3 class="panel-title">Calendar</h3>
                  </div>
                  <div class="panel-body">
                  		<div class="drag-calbtn">
                        	<a href="{FULL_SITE_URL}dashboard?caltype=Examination" class="drag-drag <?php if($showtypeinc =='Examination'): echo 'active'; endif; ?>">Examination</a>
                          <!-- <a href="{FULL_SITE_URL}dashboard?caltype=Holidays" class="drag-drag <?php if($showtypeinc =='Holidays'): echo 'active'; endif; ?>">Holidays</a>
                          <a href="{FULL_SITE_URL}dashboard?caltype=Events" class="drag-drag <?php if($showtypeinc =='Events'): echo 'active'; endif; ?>">Events</a> -->
                      </div>
                  	  <div id='calendar'></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<!-- <div class="modal fade" id="SchedulerCalendarModal" tabindex="-1" role="dialog" aria-labelledby="myeventCal" aria-hidden="true">
  <div class="modal-dialog" role="document">&nbsp;</div> -->
</div>
<script src="{ASSET_FRONT_URL}js/bootstrap.min.js"></script>
<script src="{ASSET_FRONT_URL}js/jquery.slimscroll.js"></script>
<div id="eventDataPopup" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="row modal-content p-t-20" id="eventDataPopupHTML">
            <form class="form-horizontal">
                 <div class="form-group row m-b-5">
                    <label class="col-sm-2 text-right control-label col-form-label">Loading...</label>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function getScheduleData(date){
        $.ajax({
            type: 'post',
            url: FULLSITEURL+CURRENTCLASS+'/get_event_data',
            data: {date:date},
         success: function(response){
                $('#eventDataPopup').modal('show');
                $('#eventDataPopupHTML').html(response);
               //alert(response);
            }
        });
    }
</script>
<!-- <script type="text/javascript">
    function getScheduleDataevent(date){
      var eventtype = "Examination"; 
		    alert(eventtype);
        $.ajax({
            type: 'post',
            url: FULLSITEURL+CURRENTCLASS+'/get_event_data_exam',
            data: {date:date,eventtype:eventtype},
         success: function(response){
                $('#eventDataPopup').modal('show');
                $('#eventDataPopupHTML').html(response);
               //alert(response);
            }
        });
    }
</script> -->
