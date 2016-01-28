<style>
</style>
<script>
$(function(){

	$('#btn_backup').button();
	$('#btn_restore').button();
	
		$('#restoredialog').dialog({buttons: 
									{"Close": function(){$('#restoredialog').dialog('close');} 
									},
									autoOpen:false,modal:true,height:200,width:500});
		
		$('#btn_restore').click(function(){
								$('#restoredialog').html('<iframe name="upload_iframe" frameborder="no" style="border:0px;width:90%;height:90%;padding:1em;" src="<?php echo base_url()."index.php/backup/mediaUpload";?>"></iframe>');
								$('#restoredialog').dialog('open');							
							});
	
	
	$('#btn_backup').click(function(){
							
							spinner = null;
							spinner = $("#download_file").spin('small');
							$.post("<?php echo base_url();?>index.php/backup/doBackup",
															function(data){	
																spinner.spin('false');															
																$('#download_file').attr('href',"<?php echo base_url();?>"+data);
																$('#download_file').html("<?php echo $this->lang->line('msg_DownloadBackup');?>")
															},'html'); 
								});
	
	$("#backupcontent").fadeIn(300);
	
	
});
</script>
<div id="backupcontent" style="display:none;">

	<div id='btn_backup_container' style="width:100%;text-align:center;align:center;">
		<button id="btn_backup" style="margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_Backup');?>
		</button><a id="download_file" href="#"></a>
	</div>
	<hr/>
	<div style="width:100%;text-align:center;align:center;">
		<button id="btn_restore" style="margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_Restore');?>
		</button> 
	</div>
	<div id="restoredialog" title="Restore"></div>
</div>
