<div id="items_form" style="display:none;">
<script>
function setImage(id,url){
	jQuery('#itemimg').find('img').attr('src',url);
	jQuery('#inputidimg').attr('value',id);
}

$(function(){
		$.ajaxSetup({cache: false});
		$('input[type=submit]').button();
		$('#itemsformbtnCancel').button();
		
		$('#itemsformbtnCancel').click(function(){
									var spinner = $('#layout-center').spin();
									$.get('<?php echo base_url()."index.php/items/listItems"; ?>',
									function(data){
										spinner.spin(false);
										$('#layout-center').html( data);
									}, "html");
							});
									
	
		$("#itemsform").submit(function(){
							var spinner = $('#layout-center').spin();
							$.post("<?php echo base_url(); ?>index.php/items/newitem",$("#itemsform").serialize(),
								function(data){
										spinner.spin(false);
										$("#layout-center").empty();
										$("#layout-center").append(data);
										$('#items_form').fadeIn(300);
								});
							return false;
							});	
		
		$('#filedialog').html('<iframe name="upload_iframe" frameborder="no" style="border:0px;width:95%;height:95%;padding:1em;" src="<?php echo base_url()."index.php/upload/mediaUpload";?>"></iframe>');
		
		$('#filedialog').dialog({buttons: 
									{"Close": function(){$('#filedialog').dialog('close');} 
									},
									autoOpen:false,modal:true,height:600,width:800});
		
		$('#itemimg').click(function(){
								$('#filedialog').dialog('open');							
							});
	});

</script>
<?php
if(validation_errors() != ''){
	$data['msg'] = validation_errors();
	$this->load->view('includes/errordialog',$data);
}
echo form_open('items/newitem',array('id'=>'itemsform'));
echo form_fieldset("Items");
echo '<div class="_50">';	
				echo form_label( $this->lang->line('msg_Itemlabel'), 'itemlabel').form_input(array('name'=>'itemlabel','value'=>set_value('itemlabel')));
echo '</div>';
echo '<div class="_25">';	
				echo form_label( $this->lang->line('msg_Price'), 'itemprice').form_input(array('name'=>'itemprice','value'=>set_value('itemprice')));
echo '</div>';
echo '<div class="_50">';
			echo form_label( $this->lang->line('msg_Itemdescr'), 'itemdescr').form_textarea(array('name'=>'itemdescr'),set_value('itemdescr'));
echo '</div>';
echo '<div class="_50">';
			echo form_label( $this->lang->line('msg_Image'), 'itemimage').'<div id="itemimg"><img style="width:80%;" src="'.$imgsrc.'" alt="click me"/></div>';
echo '</div>';
echo form_hidden('idItem',set_value('idItem','-1'));
echo form_input(array('type'=>'hidden','name'=>'idImages','value'=>set_value('idImages','-1'),'id'=>'inputidimg'));
echo '<div class="clear"></div>';
echo '<div class="_100">';
				echo form_submit(array('name'=>'submit','id'=>'itemsformbtnSend'), $this->lang->line('msg_Submit'));
				echo nbs(3);
				echo '<button type="button" id="itemsformbtnCancel">'.$this->lang->line('msg_Cancel').'</button>';
echo '</div>';

echo form_fieldset_close();
echo form_close();

?>
<div id="filedialog" title="File upload"></div>
</div>
