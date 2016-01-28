
<!DOCTYPE html>

<!-- 
*Copyright 2012 Gianrico D'Angelis  -- gianrico.dangelis@gmail.com
*
*Licensed under the Apache License, Version 2.0 (the "License");
*you may not use this file except in compliance with the License.
*You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*
*Unless required by applicable law or agreed to in writing, software
*distributed under the License is distributed on an "AS IS" BASIS,
*WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*See the License for the specific language governing permissions and
*limitations under the License.
 -->

<html>
<head>
<title>Upload/List Form</title>
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
											$("button[id*='mediadel-']").click(function(){
												var tagId = $(this).attr("id");
												var idImages = tagId.substr(tagId.indexOf('-')+1);
												$('#dialog').html('<?php echo $this->lang->line('msg_DelImage');?>');
												$('#dialog').data('idImages',idImages).dialog('open');
											});
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
	  echo form_open_multipart('upload/domediaupload');
	  echo form_fieldset("Items",array('style'=>'width:90%;'));
	  echo '<div class="_50">';	
	  echo form_label( $this->lang->line('msg_Filelabel'), 'filelabel').form_input(array('name'=>'filelabel','value'=>$filelabel));
	  echo '</div>';
	  echo '<div class="_50" style="float:left;">';	
	  echo '<input style="margin-top:2em;" type="file" name="userfile" size="20" />';
	  echo '</div>';
	  echo '<div class="clear"></div>';
	  echo '<div class="_50">';	
	  echo '<input type="submit" value="upload" />';
	  echo '</div>';
	  echo form_fieldset_close();
	  echo form_close();
  echo '</div>';
?>

<ol id="medialist">
<?php

	foreach ($mediaList as $row){
	 echo '<li style="display:inline-block;" id="medialist-'.$row->idImages.'"><div style="float:left;"><img style="width:100px;display:block;" src="'.base_url().'/media/'.$row->fileName.'"/></div><div style="float:left;font-size:1.2em;font-weight:bold;padding:1em;">'.$row->label.'</div><div style="float:right;padding:1em;"><button id="mediadel-'.$row->idImages.'">'.$this->lang->line('msg_Delete').'</button></div></li>';
	}
?>
</ol>
</body>
</html>
