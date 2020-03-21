<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script src="http://localhost/school-orissa/admin/assets/js/jquery.validate.js"></script>
<script>
$(function(){
	$("#start_date_attdn").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,maxDate: 0,yearRange: "1960:<?php echo date('Y'); ?>"});
	$("#end_date_attdn").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,maxDate: 0,yearRange: "1960:<?php echo date('Y'); ?>"});
  $("#date").datepicker({dateFormat:'dd-mm-yy',changeMonth: true,changeYear: true,maxDate: 0,yearRange: "1960:<?php echo date('Y'); ?>"});
  
});
</script>
<form id="report_Form" name="report_Form" method="post" action="">
  <ol class="breadcrumb">
    <li><a href="{FULL_SITE_URL}dashboard">Home</a></li>
    <li class="active">Manage student attendance details</li>
	<li class="pull-right"><a href="{FULL_SITE_URL}{CURRENT_CLASS}/addStudentAttendance" class="btn btn-default">Add student attendance</a></li>
  <li class="pull-right"><a href="{FULL_SITE_URL}{CURRENT_CLASS}/bulkStudentAttendance" class="btn btn-default">Upload Attendance data by CSV sheet</a></li>

  </ol>

  <span class="right ">
    <?php 
     if($edit_data == 'Y' && $ALLDATA <> ""):  ?>
      <input type="submit" name="report" id="SaveChanges" value="Attendance  Report" class="btn btn-default" />
    <?php endif;  ?>
  </span>
</form>
{message}
<div class="table-agile-info">
  <div class="panel panel-default">
    <?php  if($edit_data == 'Y'):  ?>        
       <!-- <form id="report_Form" method="post" role="form" action="" enctype="multipart/form-data" > -->
        <div class="row w3-res-tb">
          <div class="col-sm-8 m-b-xs">
            <span>Upload Attendance data by CSV sheet </span> &nbsp; &nbsp;   
            <a class="btn btn-success "  href="{ASSET_URL}demo_doc/student_attendance.csv" download>Demo Upload CSV<i class="fa fa-upload" aria-hidden="true"></i></a>
          </div>
          <!-- <div class="col-sm-3"  >
            <input type="file" name="studentFile" id="studentFile" accept=".csv" class="form-control required" style="height:auto;">
          </div>
          <div class="col-sm-1">
             <input type="submit" name="SaveStudentExcelUpload" id="SaveStudentExcelUpload" value="Submit" class="btn btn-sm btn-default UploadData" />
          </div> -->
        </div>
      </form> 
    <?php endif; ?>    
    <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
      <div class="row w3-res-tb">
        <div class="col-sm-6 m-b-xs class-parent">
          <select name="class_id" id="class_id" class="input-sm form-control  required w-sm inline v-middle  ">
            <option value="">Select class name</option>
            <?php if($CLASSDATA <> ""): foreach($CLASSDATA as $CLASSINFO): ?>
            <option value="<?php echo $CLASSINFO['encrypt_id']; ?>" <?php if($CLASSINFO['encrypt_id'] == $classid): echo 'selected="selected"'; endif; ?>><?php echo stripslashes($CLASSINFO['class_name']); ?></option>
            <?php endforeach; endif; ?>
          </select>
          <select name="section_id" id="section_id" class="input-sm form-control  required w-sm inline v-middle">
            <option value="">Select section name</option>
          </select>
          <select name="student_id" id="student_id" class="input-sm form-control  required w-sm inline v-middle">
            <option value="">Select student</option>
          </select>
        </div>
        <div class="col-sm-5"  >
          <div id='hide_date'>
            <input type="text" name="start_date" id="start_date_attdn" value="<?php echo $start_date; ?>" class=" input-sm form-control  w-sm inline v-middle"   placeholder="Start Date"> &nbsp;
            <input type="text" name="end_date" id="end_date_attdn" value="<?php echo $end_date; ?>" class="input-sm form-control  w-sm inline v-middle"    placeholder="End Date"> 
          </div>   
          <div id='show_date'>
            <input type="text" name="date" id="date" value="<?php echo $date; ?>" class=" input-sm form-control  w-sm inline v-middle"   placeholder="Date"> 
          </div>
        </div> 
        <div class="col-sm-1">
          <a class="btn btn-sm btn-default dateData">Apply</a> 
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th>Sr.No.</th>
              <th>Name</th>
              <th>Registration No</th>
              <th>Attendance Date</th>
              <th>Time</th>
              <th>Attendance</th>
            </tr>
          </thead>
          <tbody>
            <?php  if($ALLDATA <> ""): $i=1; foreach($ALLDATA as $ALLDATAINFO): //echo "<pre>"; print_r($ALLDATAINFO); die; ?>
            <tr class="<?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
              <td><?=$i++?></td>
              <td><?=stripslashes($ALLDATAINFO['student_f_name'])?>  <?=stripslashes($ALLDATAINFO['student_m_name'])?>  <?=stripslashes($ALLDATAINFO['student_l_name'])?></td>
              <td> <?=$ALLDATAINFO['student_registration_no'] ?></td>
              <td> <?=date('d-m-Y ', strtotime(stripslashes($ALLDATAINFO['date']))) ?></td>
              <td><?php  if($ALLDATAINFO['time']):
                       echo date("g:i ", strtotime($ALLDATAINFO['time'])) ; 
                             endif ;?></td>
              <td><?=attandanceStatus($ALLDATAINFO['attandance'])?></td>
            </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="5" style="text-align:center;">No data available in table</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    <?php if($ALLDATA <> ""): ?>
      <footer class="panel-footer">
        <div class="row">
          <div class="col-sm-5">
            <small class="text-muted inline m-t-sm m-b-sm"><?php echo $noOfContent; ?></small>
          </div>
          <div class="col-sm-7 text-right text-center-xs">                
            <?=$this->pagination->create_links()?>
          </div>
        </div>
      </footer>
    <?php endif; ?>
    </form>
  </div>
</div>
<script>
  var prevSerchValue	=	'<?php echo $searchValue; ?>';
</script>
<script>
  $('#start_date_attdn').on('change', function (ev) {
   var start_date = $(this).val();
  });
  $('#end_date_attdn').on('change', function (ev) {
     var end_date = $(this).val();
  });
  var studentid = "<?php echo $studentid ?>"; 
  if(studentid == 'All') {
      $("#hide_date").hide();
         $("#show_date").show(); 
     }else{
           $("#show_date").hide();
  } 
  $(function() {
    $('#student_id').change(function(){
        if($('#student_id').val() == 'All') {
          $("#hide_date").hide();
           $("#show_date").show();
        } else {
            $('#hide_date').show(); 
              $("#show_date").hide();
        }   
    });
  });      
  $(document).on('click','#Data_Form .dateData',function(){
    if($('#student_id').val() == ''){
         alert('Please select student');
    } else if($('#student_id').val() == 'All'){
      if($('#date').val() == '') {
        alert('Please enter date'); 
      } else {
        $('#Data_Form').submit();
	    }  
    } else{
      if($('#start_date_attdn').val() == '' || $('#end_date_attdn').val() == ''){
		  alert('Please enter start date and end date');
	    } else {
        start_date =$('#start_date_attdn').val();
        end_date =$(' #end_date_attdn').val();
        var startDate = start_date.split('-');
        start_date = new Date();
        start_date.setFullYear(startDate[2],startDate[1]-1,startDate[0]);
        var endDate = end_date.split('-');
        end_date = new Date();
        end_date.setFullYear(endDate[2],endDate[1]-1,endDate[0]);
        if (start_date  > end_date ) {
          alert('Start date should be equal to or less then  to End date ');
        }else{
            $('#Data_Form').submit();
        }
	    }
    }										 
  });
</script>
<script>
  $(document).on('change','#class_id',function(){
    var curobj      =   $(this);
    var classid     =   $(this).val();
    var sectionid   =   '';
    get_section_data(curobj,classid,sectionid);
  });
</script>
<script>
  <?php if($sectionid <> "" || $_POST): ?> 
    $(document).ready(function(){ 
      var curobj      =   $('#class_id');
      var classid     =   '<?php echo $classid; ?>';
      var sectionid   =   '<?php echo $sectionid; ?>';
      get_section_data(curobj,classid,sectionid);
    });
  <?php endif; ?>
</script> 
<script>
  $(document).on('change','#section_id',function(){
    var curobj      =   $(this);
    var classid     =   $('#class_id').val();
    var sectionid   =   $(this).val();
    var studentid   =   '';
    get_student_data(curobj,classid,sectionid,studentid);
  });
</script>
<script>
  <?php if($studentid <> "" || $_POST): ?> 
    $(document).ready(function(){ 
      var curobj      =   $('#class_id');
      var classid     =   '<?php echo $classid; ?>';
      var sectionid   =   '<?php echo $sectionid; ?>';
      var studentid   =   '<?php echo $studentid; ?>';
      get_student_data(curobj,classid,sectionid,studentid);
    });
  <?php endif; ?>
</script> 