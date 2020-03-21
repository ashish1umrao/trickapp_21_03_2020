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
  <form id="Data_Form" name="Data_Form" method="get" action="">
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
          <select name="teacher_id" id="teacher_id" class="input-sm form-control  required w-sm inline v-middle">
            <option value="">Select Teacher</option>
          </select>
        </div>
        <div class="col-sm-5"  >
          <div id='hide_date'>
            <input type="text" name="start_date" id="start_date_attdn" value="<?php echo $start_date; ?>" class=" input-sm form-control  w-sm inline v-middle"   placeholder="Start Date" autocomplete="off"> &nbsp;
            <input type="text" name="end_date" id="end_date_attdn" value="<?php echo $end_date; ?>" class="input-sm form-control  w-sm inline v-middle"    placeholder="End Date" autocomplete="off"> 
          </div>   
          <div id='show_date'>
            <input type="text" name="date" id="date" value="<?php echo $date; ?>" class=" input-sm form-control  w-sm inline v-middle"   placeholder="Date" > 
          </div>
        </div> 
        <div class="col-sm-1">
          <a class="btn btn-sm btn-default dateData">Apply</a> 
        </div>
      </div>
      <div class="table-responsive">
        <div style="width:100%; overflow:auto">
       <span id="ContentPlaceHolder1_msg" style="color:#9E0508;font-weight:bold;"></span>
       <div>
		<table class="tbllist" cellspacing="0" rules="all" border="1" id="ContentPlaceHolder1_gvstudent" style="width:100%;border-collapse:collapse;">
    <thead>
			<tr>
				<th scope="col">SlNo.</th>
				<th scope="col">Roll No.</th>
				<th scope="col">First Name</th>
        <th scope="col">Class Name</th>
        <th scope="col">Section</th>
        <th scope="col">Teacher</th>
				<th scope="col">Attendance Status</th>
				<!-- <th scope="col">Absent</th>
				<th scope="col">Leave</th> -->
			</tr>
      </thead>
      </form>
      <!-- <form id="Data_Form" name="Data_Form" method="POST" action="<?php //echo $forAction; ?>http://localhost/trickapp/admin/gurunanakpublicschoolorgin/studattendance/addStudentAttendance/"> -->
        <form id="currentPageForm" method="post" class="form-horizontal" role="form" action="">
      <tbody>
      <?php  if($ALLAttEDANCE <> ""): $i=1; foreach($ALLAttEDANCE as $ATTENDANCEINFO): //echo "<pre>"; print_r($ATTENDANCEINFO); die;?>
            <tr class="<?php if($i%2 == 0): echo 'odd'; else: echo 'even'; endif; ?>">
        <td><?=$i++?></td>
        <td> <?=$ATTENDANCEINFO['student_roll_no'] ?></td>
        <td><input type="hidden" name="studentID[]" value="<?php echo $ATTENDANCEINFO['student_qunique_id']; ?>"><?=stripslashes($ATTENDANCEINFO['student_f_name'])?>  <?=stripslashes($ATTENDANCEINFO['student_m_name'])?>  <?=stripslashes($ATTENDANCEINFO['student_l_name'])?></td>
        <td><input type="hidden" name="class_id_<?=$ATTENDANCEINFO['student_qunique_id']; ?>" value="<?php echo $ATTENDANCEINFO['class_name']; ?>"><?php echo $ATTENDANCEINFO['class_name']; ?></td>
        <td><input type="hidden" name="section_id_<?=$ATTENDANCEINFO['student_qunique_id']; ?>" value="<?php echo $ATTENDANCEINFO['class_section_name'];?>"><?php echo $ATTENDANCEINFO['class_section_name'];?></td>

        <td><input type="hidden" name="teacherID_<?=$ATTENDANCEINFO['student_qunique_id']; ?>" value="<?php echo $ATTENDANCEINFO['teacherID']; ?>"> <?=$ATTENDANCEINFO['user_f_name'] ?> <?=$ATTENDANCEINFO['user_m_name'] ?> <?=$ATTENDANCEINFO['user_l_name'] ?> </td>
        <td><center><select name = 'leaverequest_<?=$ATTENDANCEINFO['student_qunique_id']; ?>' id="leaverequest" class="leaverequest">   
                <option type="radio" value = 'Present'>Present</option> 
                <option value = 'Absent'>Absent</option> 
                <option value = 'leave'>Leave</option> 
            </select> </center></td>
        <?php endforeach; else: ?>
              <tr>
                <td colspan="5" style="text-align:center;">No data available in table</td>
              </tr>
            <?php endif; ?>
              </tbody>
              </table>
              </div>
              <center>
           </center>
           <!-- <input type="submit" name="ctl00$ContentPlaceHolder1$btnsave" value="Save" id="ContentPlaceHolder1_btnsave"> -->
           <input type="submit" name="SaveChangesattendancedata" id="SaveChangesattendancedata" value="Submit" class="btn btn-primary" />
          </form>
           </td>
           </tr>
           </tbody></table>
   </div>
      </div>
      <?php if($ALLAttEDANCE <> ""): ?>
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
  var teacherid = "<?php echo $teacher_id ?>"; 
  //alert(teacherid);
  if(teacherid == 'All') {
      $("#hide_date").hide();
         $("#show_date").show(); 
     }else{
           $("#show_date").hide();
  } 
  $(function() {
    $('#teacher_id').change(function(){
        if($('#teacher_id').val() == 'All') {
          $("#hide_date").hide();          
           $("#show_date").show();
        } else {
            $('#hide_date').show(); 
              $("#show_date").hide();
        }   
    });
  });      
  $(document).on('click','#Data_Form .dateData',function(){
    if($('#teacher_id').val() == ''){
         alert('Please select Atleast One Teacher');
    } else if($('#teacher_id').val() == 'All'){
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
    var teacherid   =   '';
   // alert(sectionid);
    get_teacher_data(curobj,classid,sectionid,teacherid);
  });
</script>
<script>
  <?php if($teacherid <> "" || $_POST): ?> 
    $(document).ready(function(){ 
      var curobj      =   $('#class_id');
      var classid     =   '<?php echo $classid; ?>';
      var sectionid   =   '<?php echo $sectionid; ?>';
      var teacherid   =   '<?php echo $teacherid; ?>';
      get_teacher_data(curobj,classid,sectionid,teacherid);
    });
  <?php endif; ?>
</script> 