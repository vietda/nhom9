
<!DOCTYPE html>
<html>
<head>
<title>Restore</title>
<link type="text/css" href="<?php echo base_url();?>css/custom-theme/jquery-ui-1.8.18.custom.css" rel="Stylesheet" />	
<link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" /> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.layout.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/spin.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.spin.js"></script>
<style>
	#medialist .ui-selecting { background: #DE6464; }
	#medialist .ui-selected { background: #821C1C; color: white; }
	#medialist { list-style-type: none; margin: 0; padding: 0; width: 90%; }
	#medialist li { margin: 3px; padding: 0.4em; font-size: 1.4em; width:100%;border-top:1px solid black;}
	
	input[type=file]{color:#ffffff;}
	
</style>
<script>


$(function(){
		$.ajaxSetup({cache: false});
		$('input[type=submit]').button();
		$('#medialist').selectable({
			stop: function() {
				
				$( ".ui-selected", this ).each(function() {
					var liid = $(this).attr('id');
					var img = $(this).find('img');
					var src = img.attr("src");
					var idImages = liid.substr(liid.indexOf('-')+1);
					parent.setImage(idImages,src);
				});
			}
		});
		
		$('button').button();
		
		$("button[id*='mediadel-']").click(function(){
								var tagId = $(this).attr("id");
								var idImages = tagId.substr(tagId.indexOf('-')+1);
								$('#dialog').html('<?php echo $this->lang->line('msg_DelImage');?>');
								$('#dialog').data('idImages',idImages).dialog('open');
							});	
		
		
		$('#dialog').dialog("destroy");
		$('#dialog').dialog({autoOpen:false,modal:true});
		$('#dialog').dialog({ buttons: 
					{
						"Yes": function() {
								$(this).dialog( "close" );
								var spinner = $('#medialist').spin();
								$.post("<?php echo base_url(); ?>index.php/upload/deleteMedia",{idImages:$(this).data('idImages')},
										function(data){
											spinner.spin('false');
											$("#medialist").empty();
											$("#medialist").html(data);
											$('#medialist').fadeIn(300);
										}
									);
								},
						"No": function() {
						$( this ).dialog( "close" );
						}
					}});
		
		
});
</script>
</head>
<body>
<div id="dialog" title="Confirm"></div>
<?php if (isset($error)){$data['msg']=$error;$this->load->view('includes/errordialog',$data);}?>

<?php 
	 echo '<div id="upload_div" >';
	  echo form_open_multipart('backup/restore');
	  echo form_fieldset("Items",array('style'=>'width:90%;'));
	  echo '<input style="margin-top:2em;" type="file" name="userfile" size="20" />';
	  echo '<input type="submit" value="'.$this->lang->line('msg_Restore').'" />';
	  echo form_fieldset_close();
	  echo form_close();
  echo '</div>';
?>
</body>
</html>
