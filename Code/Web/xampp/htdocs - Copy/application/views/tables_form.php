
<div id="tables_form" style="display:none;">
<script>
 $(function(){
	$('input[type=submit]').button();
	$('#tablesformbtnCancel').button();
	
	$('#tablesformbtnCancel').click(function(){
									var spinner = $('#layout-center').spin();
									$.get('<?php echo base_url()."index.php/tables/listTables"; ?>',
									function(data){
										spinner.spin(false);
										$('#layout-center').html( data);
									}, "html");
							});
	
	$("#tablesform").submit(function(){
							var spinner = $('#layout-center').spin();
							$.post("<?php echo base_url(); ?>index.php/tables/newtable",$("#tablesform").serialize(),
								function(data){
										spinner.spin(false);
										$("#layout-center").empty();
										$("#layout-center").append(data);
										$('#tables_form').fadeIn(300);
								});
							return false;
							});
	
 });
</script>

<?php
if(validation_errors() != ''){
	$data['msg'] = validation_errors();
	$this->load->view('includes/errordialog',$data);
}

echo form_open('menus/newmenu',array('id'=>'tablesform'));
echo form_fieldset("Your menu");
echo '<div class="_25">';
		echo form_label( $this->lang->line('msg_Tablelabel'), 'tablelabel').form_input(array('name'=>'tablelabel','value'=>set_value('tablelabel')));
echo '</div>';
echo form_hidden('idTables',set_value('idTables','-1'));
echo '<div class="_100">';
				echo form_submit(array('name'=>'submit','id'=>'menusformbtnSend'), $this->lang->line('msg_Submit'));
				echo nbs(3);
				/*echo '<button id="menuformsbtnSend">'.$this->lang->line('msg_Submit').'</button>';*/
				echo '<button type="button" id="tablesformbtnCancel">'.$this->lang->line('msg_Cancel').'</button>';
echo '</div>';
echo form_fieldset_close();
echo form_close();
?>
</div>
