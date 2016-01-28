
<div id="users_form" style="display:none;width: 50%;margin:auto;">
<script>
	$(function(){
		$.ajaxSetup({cache: false});
		$('input[type=submit]').button();
		$('#usersformbtnCancel').button();
		
		$('#usersformbtnCancel').click(function(){
									var spinner = $('#layout-center').spin();
									$.get('<?php echo base_url()."index.php/users/listusers"; ?>',
									function(data){
										spinner.spin(false);
										$('#layout-center').html( data);
									}, "html");
							});
									
	
		$("#usersform").submit(function(){
							var spinner = $('#layout-center').spin();
							$.post("<?php echo base_url(); ?>index.php/users/<?php echo $save;?>",$("#usersform").serialize(),
								function(data){
										spinner.spin(false);
										$("#layout-center").empty();
										$("#layout-center").append(data);
										$('#users_form').fadeIn(300);
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
echo form_open('users/newuser',array('id'=>'usersform'));
echo form_fieldset("Your user");

echo '<div class="_50">';	
				echo form_label( $this->lang->line('msg_Name'), 'name').form_input(array('name'=>'name','value'=>set_value('name')));
echo '</div>';
echo '<div class="_50">';	
				echo form_label( $this->lang->line('msg_Surname'), 'surname').form_input(array('name'=>'surname','value'=>set_value('surname')));
echo '</div>';
echo '<div class="_50">';	
				echo form_label( $this->lang->line('msg_Email'), 'email').form_input(array('name'=>'email','value'=>set_value('email')));
echo '</div>';
echo '<div class="_50" >';	
				echo form_label( $this->lang->line('msg_Role'), 'role').form_input(array('name'=>'role','value'=>set_value('role')));
echo '</div>';
echo '<div class="_50" style="clear:both;display:inline-block;">';	
				echo form_label( $this->lang->line('msg_Username'), 'username').form_input(array('name'=>'username','value'=>set_value('username')));
echo '</div>';
echo '<div class="_50" style="clear:both;display:inline-block;">';	
				echo form_label( $this->lang->line('msg_Password1'), 'password1').form_input(array('name'=>'password1','value'=>set_value('password1')));
echo '</div>';
echo '<div class="_50" style="clear:both;display:inline-block;">';	
				echo form_label( $this->lang->line('msg_Password2'), 'password2').form_input(array('name'=>'password2','value'=>set_value('password2')));
echo '</div>';
echo form_hidden('idUser',set_value('idUser','-1'));

echo '<div class="_100">';
				echo form_submit(array('name'=>'submit','id'=>'usersformbtnSend'), $this->lang->line('msg_Submit'));
				echo nbs(3);
				/*echo '<button id="menuformsbtnSend">'.$this->lang->line('msg_Submit').'</button>';*/
				echo '<button type="button" id="usersformbtnCancel">'.$this->lang->line('msg_Cancel').'</button>';
echo '</div>';
echo form_fieldset_close();
echo form_close();
?>
</div>
