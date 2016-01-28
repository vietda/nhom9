<script>
	clearTimeout(periodicalTimer);
</script>

<div id="login_form">

	<h1>Login</h1>
    <?php 
	echo form_open('login/validate_credentials');
	//echo form_input('username', $this->lang->line('msg_Username'));
	$data = array(
				'name'=>'username',
				'id'=>'lgn_username',
				'value'=>$this->lang->line('msg_Username'),
				'onclick'=>"javascript: document.getElementById('lgn_username').value = '';"
				);
	echo form_input($data);			
	//echo form_password('password', $this->lang->line('msg_Password'));
	$data = NULL;
	$data = array(
				'name'=>'password',
				'id'=>'lgn_password',
				'value'=>$this->lang->line('msg_Password'),
				'onclick'=>"javascript: document.getElementById('lgn_password').value = '';"
				);
	echo form_password($data);
	echo form_submit('submit', $this->lang->line('msg_Login'));
	echo form_close();
	?>
	<div style="text-align:right;color:#aaaaaa;font-size:10px;">Powered By <a  href="#">Easymenu</a></div>
</div><!-- end login_form-->

	
