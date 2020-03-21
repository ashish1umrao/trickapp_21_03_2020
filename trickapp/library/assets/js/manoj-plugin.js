/* 
Developed By		:	Manoj Kumar
Date				:	11 JANUARY 2018
*/
// accordian start 
$(document).ready(function(){ 
	if(CURRENTCLASS == "admin"){
		var currclass		=	''; 
	} else {
		var currclass		=	CURRENTCLASS;
	}
	
	if(CURRENTMETHOD.indexOf("ddedit")>0){
	   var activelink	=	'/index'; 
	} else if(CURRENTMETHOD.indexOf("iewdata")>0){
	   var activelink	=	'/index';
	} else if(CURRENTMETHOD.indexOf("ashboard")>0){
	   var activelink	=	CURRENTMETHOD; 
	} else {
	  var activelink	=	'/'+CURRENTMETHOD; 
	}
   
	var url = FULLSITEURL+currclass+activelink; 
    $('#sidebar .sidebar-menu li a[href^="' + url + '"]').addClass('active'); 
	$('#sidebar .sidebar-menu li a[href^="' + url + '"]').addClass('active').parent().parent('.nav-second-level').css('display','block').prev('a').addClass('active');
});

 // Cookies
function createCookie(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = "; expires=" + date.toGMTString();
	}
	else var expires = "";               
	document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length).replace(/%2F/gi,'/').replace(/\+/gi,' ').replace(/\%26%23xa3%3B/gi,'&#xa3;');
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name, "", -1);
}

$(function(){	
	if(readCookie('smsAdminSidebarToggleBox') == 'YES'){
		$('#sidebar.nav-collapse').addClass('hide-left-bar');
		$('#main-content').addClass('merge-left');
	} else {
		$('#sidebar.nav-collapse').removeClass('hide-left-bar');
		$('#main-content').removeClass('merge-left');
	}	   
});		   

///////////			ALERT MESSAGE 		///////////////////
$(function(){
	setTimeout(AlertMessageTimedOut, 5000);
});

function AlertMessageTimedOut() { 
	$('.alert.alert-warning').hide();
	$('.alert.alert-danger').hide();
	$('.alert.alert-success').hide();
}

///////////			ALERT MESSAGE MODEL 		///////////////////
function alertMessageModelPopup(message,type){	
	var wheight		=	(($( window ).height()/2)-50);
	$("#alertMessageModal").modal('show');
	$("#alertMessageModal .modal-dialog").css('margin-top',wheight);
	if(type == 'red')
		$("#alertMessageModal .message-detail-data").html('<font color="red">'+message+'</span>');
	else
		$("#alertMessageModal .message-detail-data").html('<font color="green">'+message+'</span>');
	setTimeout(AlertMessageModelPopupTimedOut, 5000);
}
function AlertMessageModelPopupTimedOut() { 
	$("#alertMessageModal").modal('hide');
}

///////////			VIEW DETAILS MODEL 		///////////////////
$(document).on('click','.table-responsive .view-details-data',function(){ 
	var title	=	$(this).attr('title');
	$("#myViewDetailsModal").modal();
	$("#myViewDetailsModal .view-detail-title").html('<h3><center>'+title+'</center></h3>');
	$("#myViewDetailsModal .view-detail-data").html('<h3><center>Loading...</center></h3>');
	var viewid	=	$(this).attr('data-id');
	$.ajax({
		type: 'post',
		 url: FULLSITEURL+CURRENTCLASS+'/get_view_data',
		data: {csrf_api_key:csrf_api_value,viewid:viewid},
	 success: function(response){
	 		$("#myViewDetailsModal .view-detail-data").html(response);
	 	}	
	});
});

///////////			VIEW SUB DETAILS MODEL 		///////////////////
$(document).on('click','a.view-sub-details-data',function(){ 
	var title	=	$(this).attr('title');
	$("#myViewSubDetailsModal").modal();
	$("#myViewSubDetailsModal .view-detail-title").html('<h3><center>'+title+'</center></h3>');
	$("#myViewSubDetailsModal .view-detail-data").html('<h3><center>Loading...</center></h3>');
	var viewid	=	$(this).attr('data-id');
	$.ajax({
		type: 'post',
		 url: FULLSITEURL+CURRENTCLASS+'/get_view_sub_data',
		data: {csrf_api_key:csrf_api_value,viewid:viewid},
	 success: function(response){
	 		$("#myViewSubDetailsModal .view-detail-data").html(response);
	 	}	
	});
});

//////////////////////////////////   Image Upload Through Ajax
function UploadImage(count)
{	
	var imgcount = count;
	if (document.getElementById('uploadIds'+imgcount)) 
	{
		var btnUpload=$('#uploadIds'+imgcount);
		var status=$('#uploadstatus'+imgcount);
	
		new AjaxUpload(btnUpload, {
			action: FULLSITEURL+CURRENTCLASS+'/uplode_image',
			name: 'uploadfile',
			data: {count:count},
			onSubmit: function(file, ext){
				if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
					status.text('Only JPG, PNG, GIF files are allowed');
					return false;
				}			
				status.text('Uploding.....');
			},
			onComplete: function(file, response){ 
				//alert(response);
				responsedata	=	response.split('_____');
				if(responsedata[0] == "ERROR"){ 
					status.text(responsedata[1]);
					return false;
				}
				status.text('');
				if(responsedata[0] != "UPLODEERROR"){ 
					document.getElementById('uploadimage'+imgcount).value = responsedata[0];
					document.getElementById('uploadphoto'+imgcount).innerHTML ='<img src="'+responsedata[0]+'" border="0" width="114" /><a href="javascript:void(0);" onClick="DeleteImage(\''+responsedata[0]+'\',\''+imgcount+'\');"><img src="'+BASEURL+'assets/images/cross.png" border="0" alt="" /></a>';
				}
			}
		});
	}
}

//////////////////////////////////   Image delete Through Ajax
function DeleteImage(imagename,imgcount)
{
    if(confirm("Sure to delete?"))
    {
        $.ajax({
		     type: 'post',
              url: FULLSITEURL+CURRENTCLASS+'/DeleteImage',
			 data: {csrf_api_key:csrf_api_value,imagename:imagename},
              success: function(response) { 
			  	document.getElementById('uploadimage'+imgcount).value = '';
				document.getElementById('uploadphoto'+imgcount).innerHTML ='';
              }
            });
    }
}


//////////////////////////////////   Customer Registration Form Valiation
if($("#currentPageForm").length) {
	$("#currentPageForm").validate({ 
		rules: {
			new_password: { minlength: 6, maxlength: 25 },
			conf_password: { minlength: 6, equalTo: "#new_password" },
	        admin_mobile_number:{ minlength:10, maxlength:15, numberandsign:true }
		},
		messages: {
		  new_password: { minlength: "Password at least 6 chars!" },
		  conf_password: { equalTo: "Password fields have to match !!", minlength: "Confirm password at least 6 chars!" }
		}
	});
}

function check_form_error(){	
	$('#currentPageForm .form-group').each(function(){	
		if($(this).find('input.error').length){	
			$(this).addClass('has-error');	
		} else if($(this).find('select.error').length){	
			$(this).addClass('has-error');
		} else if($(this).find('textarea.error').length){	
			$(this).addClass('has-error');
		}
		
		if($(this).find('input.valid').length){	
			$(this).removeClass('has-error');	
		} else if($(this).find('select.valid').length){	
			$(this).removeClass('has-error');
		} else if($(this).find('textarea.valid').length){	
			$(this).removeClass('has-error');
		}
	});
}




$(document).on('click','#Data_Form .showdataLength',function(){
	$('#Data_Form').submit();														 
});
  
  $('#Data_Form #start_date').on('change', function (ev) {
   var start_date = $(this).val();
 
});
$('#Data_Form #end_date').on('change', function (ev) {
   var end_date = $(this).val();
 
});

 
        
$(document).on('click','#Data_Form .dateData',function(){
    if($('#Data_Form #start_date').val() == '' || $('#Data_Form #end_date').val() == ''){
		alert('Please enter start date and end date');
	} else {
            
            
             start_date =$('#Data_Form #start_date').val();
              end_date =$('#Data_Form #end_date').val();
  
       
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
    
    
														 
});


$(document).on('click','#Data_Form .searchData',function(){
	if($('#Data_Form #searchValue').val() == '' && prevSerchValue == ''){
		alert('Please enter seatch text');
	} else {
		$('#Data_Form').submit();
	}
});


//////////////////////////////////   Get City Data Through Ajax
function get_city_data(curobj,state,city)
{
  curobj.closest('.address-parent').find('.selectcity').parent().parent().show();
  curobj.closest('.address-parent').find('.selectcity').html('<option value="">Select city</option>');
  curobj.closest('.address-parent').find('.selectlocality').html('<option value="">Select locality</option>');
  curobj.closest('.address-parent').find('.selectzip').val('');
  
  if(state !=''){
    $.ajax({
      type: 'post',
       url: BASEURL+'login/get_city_data',
      data: {csrf_api_key:csrf_api_value,state:state,city:city},
     success: function(cresponse){
        if(cresponse == 'ERROR'){
          curobj.closest('.address-parent').find('.selectcity').html('<option value="">Select city</option>');
          curobj.closest('.address-parent').find('.selectcity').parent().parent().hide();
          var locality  = '';
          $.ajax({
            type: 'post',
             url: BASEURL+'login/get_locality_data_by_state',
            data: {csrf_api_key:csrf_api_value,state:state,locality:locality},
           success: function(lresponse){
			  curobj.closest('.address-parent').find('.selectlocality').html(lresponse);
            }
          });
        } else {
          curobj.closest('.address-parent').find('.selectcity').parent().parent().show();
          curobj.closest('.address-parent').find('.selectcity').html(cresponse);
        }
      }
    });
  }	
}

//////////////////////////////////   Get Locality Data Through Ajax
function get_locality_data(curobj,state,city,locality)
{
  curobj.closest('.address-parent').find('.selectlocality').html('<option value="">Select locality</option>');
  curobj.closest('.address-parent').find('.selectzip').val('');
  
  if(state !='' && city !=''){
    $.ajax({
      type: 'post',
       url: BASEURL+'login/get_locality_data',
      data: {csrf_api_key:csrf_api_value,state:state,city:city,locality:locality},
     success: function(lresponse){
         curobj.closest('.address-parent').find('.selectlocality').html(lresponse);
      }
    });
  }
}


//////////////////////////////////   Get Shelf Data Through Ajax
function get_shelf_row_data(curobj,shelfid,shelfrowid)
{
   if(shelfid !=''){
    $.ajax({
      type: 'post',
       url: BASEURL+'login/get_shelf_row_data',
      data: {csrf_api_key:csrf_api_value,shelfid:shelfid,shelfrowid:shelfrowid},
     success: function(zresponse){ 
          curobj.closest('.class-parent').find('#shelf_row_id').html(zresponse);
      }
    });
  }
}
 
 
 
 //////////////////////////////////   Get Student Data Through Ajax
 function get_student_data(curobj,classid,sectionid,studentid)
{
   if((classid !='') &&(sectionid !='')){
    $.ajax({
      type: 'post',
       url: BASEURL+'login/get_student_data',
      data: {csrf_api_key:csrf_api_value,classid:classid,sectionid:sectionid,studentid:studentid},
     success: function(zresponse){ 
          curobj.closest('.class-parent').find('#student_id').html(zresponse);
      }
    });
  }
}
  
  
