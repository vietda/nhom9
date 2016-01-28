

<div id="settings_form" style="display:none;">
<script>
$(function(){
	
	$('#radio_restmode').buttonset();
	$('#radio_dispmode').buttonset();
	$('input[type=submit]').button();
	$('#settingsformbtnCancel').button();
	
	$("input[name='radiom']").change(
		function(){
			if ($("input[name='radiom']:checked").val()=='allyoucaneat'){
				$("#radio_dispmode").fadeOut(100);
				$("#padults").fadeIn(100);
				$("#pchildren").fadeIn(100);
			}
			else{
				$("#radio_dispmode").fadeIn(100);
				$("#padults").fadeOut(100);
				$("#pchildren").fadeOut(100);
			}
		});
		
		$("#settingsform").submit(function(){
							var spinner = $('#layout-center').spin();
							$.post("<?php echo base_url(); ?>index.php/settings/save",$("#settingsform").serialize(),
								function(data){
										spinner.spin(false);
										$("#layout-center").empty();
										$("#layout-center").append(data);
										$('#settings_form').fadeIn(300);
								});
							return false;
							});
		
	
	$('#settings_form').fadeIn(300);

	if ($("input[name='radiom']:checked").val()=='allyoucaneat'){
			$("#radio_dispmode").fadeOut(100);
	}
	else{
			/*$("#radio_dispmode").fadeIn(100);*/
			$("#padults").fadeOut(100);
			$("#pchildren").fadeOut(100);
		}
});
</script>

<?php
if(validation_errors() != ''){
	$data['msg'] = validation_errors();
	$this->load->view('includes/errordialog',$data);
}

echo form_open('settings/save',array('id'=>'settingsform'));

//SERVER
echo '<div class="_50">';
echo form_fieldset("server");
echo "<h2>Server</h2>";
echo '<div class="_75">';
echo form_label( $this->lang->line('msg_Servuilang'),'lang').form_dropdown('lang',array("italian"=>"Italiano","english"=>"English"),$this->input->post('lang'));
/*echo form_label( $this->lang->line('msg_Maxitems'), 'maxitems').form_input(array('name'=>'maxitems','value'=>set_value('maxitems')));
echo form_label( $this->lang->line('msg_Maxrounds'), 'maxrounds').form_input(array('name'=>'maxrounds','value'=>set_value('maxrounds')));
echo form_label( $this->lang->line('msg_Mininterval'), 'mininterval').form_input(array('name'=>'mininterval','value'=>set_value('mininterval')));*/

echo '</div>';
echo form_fieldset_close();
echo '</div>';
//TABLET
echo '<div class="_50">';
echo form_fieldset("tablet");
echo "<h2>Tablet</h2>";
echo '<div class="_100">';
echo '<div id="radio_restmode" class="radio_patch" style="display:none;">'.
					'<input type="radio" id="radioall" name="radiom" value="allyoucaneat"'.
					 set_radio("radiom","allyoucaneat",TRUE).'/><label for="radioall">'.'Allyoucaneat'.
					'</label>'.
					'<input type="radio" id="radiocarte" name="radiom" value="alacarte"'.
					set_radio("radiom","alacarte").' /><label for="radiocarte">'.'A la carte'.
					'</label>'.
				'</div>';
echo '<div id="radio_dispmode" class="radio_patch" style="display:none;">'.
					'<input type="radio" id="radiolist" name="radiodisp" value="listview"'.
					 set_radio("radiodisp","listview",TRUE).'/><label for="radiolist">'.'List View'.
					'</label>'.
					'<input type="radio" id="radiogrid" name="radiodisp" value="gridview"'.
					set_radio("radiodisp","gridview").' /><label for="radiogrid">'.'Grid View'.
					'</label>'.
				'</div><br/>';
				
echo '<div class="_50" id="padults">';
echo form_label( $this->lang->line('msg_PriceAdults'), 'priceadults').form_input(array('name'=>'priceadults','value'=>set_value('priceadults')));
echo '</div>';
echo '<div class="_50" id="pchildren">';
echo form_label( $this->lang->line('msg_PriceChildren'), 'pricechildren').form_input(array('name'=>'pricechildren','value'=>set_value('pricechildren')));
echo '</div>';	
echo '<div class="_50">';			
echo form_label( $this->lang->line('msg_Currency'), 'currency').form_input(array('name'=>'currency','value'=>set_value('currency')));
echo '</div>';	
echo '</div>';
echo form_fieldset_close();
echo '</div>';
echo '<div class="_100">';
				echo form_submit(array('name'=>'submit','id'=>'settingsformbtnSend'), $this->lang->line('msg_Submit'));
echo '</div>';
echo form_close();

?>
</div>
