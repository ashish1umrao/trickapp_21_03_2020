<script src="{ASSET_URL}js/bootstrap.js"></script>
<script src="{ASSET_URL}js/jquery.dcjqaccordion.2.7.js"></script>
<script src="{ASSET_URL}js/scripts.js"></script>
<script src="{ASSET_URL}js/jquery.slimscroll.js"></script>
<script src="{ASSET_URL}js/jquery.nicescroll.js"></script>
<script src="{ASSET_URL}js/jquery.scrollTo.js"></script>
<script type="text/javascript" src="{ASSET_URL}js/jquery.validate.js"></script>
<script type="text/javascript" src="{ASSET_URL}js/ajaxupload.js"></script>
<script type="text/javascript" src="{ASSET_URL}js/manoj-plugin.js"></script>
<?php /*?><script type="text/javascript" src="{ASSET_URL}js/fullcalendar.min.js"></script><?php */?>

<script src="{ASSET_URL}js/ckeditor/ckeditor.js" type="text/javascript"></script>
<script type="text/javascript">
function create_editor_for_textarea(textareaid)
{	
	if (document.getElementById(textareaid)) {
		// Replace the <textarea id="Description"> with a CKEditor
		// instance, using default configuration.
		CKEDITOR.replace(textareaid, {filebrowserBrowseUrl :'{ASSET_URL}js/ckeditor/filemanager/browser/default/browser.html?Connector={ASSET_URL}js/ckeditor/filemanager/connectors/php/connector.php',
		filebrowserImageBrowseUrl : '{ASSET_URL}js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector={ASSET_URL}js/ckeditor/filemanager/connectors/php/connector.php',
		filebrowserFlashBrowseUrl :'{ASSET_URL}js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector={ASSET_URL}js/ckeditor/filemanager/connectors/php/connector.php',
		filebrowserUploadUrl :'{ASSET_URL}js/ckeditor/filemanager/connectors/php/upload.php?Type=File',
		filebrowserImageUploadUrl : '{ASSET_URL}js/ckeditor/filemanager/connectors/php/upload.php?Type=Image',
		filebrowserFlashUploadUrl : '{ASSET_URL}js/ckeditor/filemanager/connectors/php/upload.php?Type=Flash',
		allowedContent:true,
		height: '400px',
		toolbar: [
				{ name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ] },	// Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
				[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],			// Defines toolbar group without name.
				{ name: 'basicstyles', items: [ 'Bold', 'Italic' ] }
			]
		});	
	}
};
</script>