<!-- bootstrap-css -->
<link rel="stylesheet" href="{ASSET_URL}css/bootstrap.min.css" >
<!-- //bootstrap-css -->
<!-- Custom CSS -->
<link href="{ASSET_URL}css/style.css" rel='stylesheet' type='text/css' />
<link href="{ASSET_URL}css/style-responsive.css" rel="stylesheet"/>
<!-- font CSS -->
<?php /*?><link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'><?php */?>
<!-- font-awesome icons -->
<link rel="stylesheet" href="{ASSET_URL}css/font.css" type="text/css"/>
<link href="{ASSET_URL}css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
<script src="{ASSET_URL}js/jquery2.0.3.min.js"></script>
<link href="{ASSET_URL}css/fullcalendar.css" rel="stylesheet" type="text/css">
<script type="text/ecmascript">
var BASEURL 		=	'<?php echo base_url(); ?>';
var FULLSITEURL 	=	'<?php echo $this->session->userdata('SMS_ADMIN_PATH'); ?>';
var CURRENTCLASS 	=	'<?php echo $this->router->fetch_class(); ?>';
var CURRENTMETHOD 	=	'<?php echo $this->router->fetch_method(); ?>';
var csrf_api_key	=	'<?php echo $this->security->get_csrf_token_name(); ?>';
var csrf_api_value 	=	'<?php echo $this->security->get_csrf_hash(); ?>'; 
</script>