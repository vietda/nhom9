
<div id="menus_form" style="display:none;">
<script>
	$(function(){
		$.ajaxSetup({cache: false});
		$('input[type=submit]').button();
		$('#menusformbtnCancel').button();
		$('#radio_visibility').buttonset();
		$('#radio_type').buttonset();
		$('#radio_foodbev').buttonset();
		
		$('#menusformbtnCancel').click(function(){
									var spinner = $('#layout-center').spin();
									$.get('<?php echo base_url()."index.php/menus/listMenus"; ?>',
									function(data){
										spinner.spin(false);
										$('#layout-center').html( data);
									}, "html");
							});
									
	
		$("#menusform").submit(function(){
							var spinner = $('#layout-center').spin();
							$.post("<?php echo base_url(); ?>index.php/menus/newmenu",$("#menusform").serialize(),
								function(data){
										spinner.spin(false);
										$("#layout-center").empty();
										$("#layout-center").append(data);
										$('#menus_form').fadeIn(300);
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
echo form_open('menus/newmenu',array('id'=>'menusform'));
echo form_fieldset("Your menu");
echo '<div class="_100">';
echo '<div id="radio_visibility" class="radio_patch">'.
					'<input type="radio" id="radiovis" name="radiov" value="y"'.
					 set_radio("radiov","y",TRUE).'/><label for="radiovis">'.$this->lang->line('msg_Visible').
					'</label>'.
					'<input type="radio" id="radioinvis" name="radiov" value="n"'.
					set_radio("radiov","n").' /><label for="radioinvis">'.$this->lang->line('msg_NoVisible').
					'</label>'.
				'</div>'.
				'<div id="radio_type" class="radio_patch" style="display:none;">'.
					'<input type="radio" id="radioc" name="radiot" value="c"'.
					 set_radio("radiot","c").'/><label for="radioc">'.$this->lang->line('msg_NoFixedPrice').
					'</label>'.
					'<input type="radio" id="radiof" name="radiot" value="f"'.
					 set_radio("radiot","f",TRUE).'/><label for="radiof">'.$this->lang->line("msg_FixedPrice").
					'</label>'.	
				'</div>'.
				'<div id="radio_foodbev" class="radio_patch">'.
					'<input type="radio" id="radiofood" name="radiofb" value="f"'.
					 set_radio("radiofb","f",TRUE).'/><label for="radiofood">'.$this->lang->line("msg_Food").
					'</label>'.
					'<input type="radio" id="radiobev" name="radiofb" value="b"'.
					 set_radio("radiofb","b").'/><label for="radiobev">'.$this->lang->line('msg_Drinks').
					'</label>'.
				'</div>';
echo '</div>';

echo '<div class="_50">';	
				echo form_label( $this->lang->line('msg_Menulabel'), 'menulabel').form_input(array('name'=>'menulabel','value'=>set_value('menulabel')));
echo '</div>';
echo '<div class="_50">';
			echo form_label( $this->lang->line('msg_Menudescr'), 'menudescr').form_textarea(array('name'=>'menudescr'),set_value('menudescr'));
echo '</div>';
$config = $this->util->getConfig();
if($config['restmode']=='alacarte'){
echo '<div class="_25" style="display:none;">';
	echo form_label( $this->lang->line('msg_Price'), 'pricelabel').form_input(array('name'=>'pricelabel','id'=>'menuformstxtPrice'),set_value('pricelabel'));
echo '</div>';
}
echo form_hidden('idMenu',set_value('idMenu','-1'));

echo '<div class="_100">';
				echo form_submit(array('name'=>'submit','id'=>'menusformbtnSend'), $this->lang->line('msg_Submit'));
				echo nbs(3);
				/*echo '<button id="menuformsbtnSend">'.$this->lang->line('msg_Submit').'</button>';*/
				echo '<button type="button" id="menusformbtnCancel">'.$this->lang->line('msg_Cancel').'</button>';
echo '</div>';
echo form_fieldset_close();
echo form_close();
?>
</div>
