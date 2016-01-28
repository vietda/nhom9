<div id="categories_form" style="display:none;">
<script>

$(function(){
		$.ajaxSetup({cache: false});
		$('input[type=submit]').button();
		$('#categoriesformbtnCancel').button();
		
		$('#categoriesformbtnCancel').click(function(){
									var spinner = $('#layout-center').spin();
									$.get('<?php echo base_url()."index.php/categories/listCategories"; ?>',
									function(data){
										spinner.spin(false);
										$('#layout-center').html( data);
									}, "html");
							});
									
	
		$("#categoriesform").submit(function(){
							var spinner = $('#layout-center').spin();
							$.post("<?php echo base_url(); ?>index.php/categories/newcategory",$("#categoriesform").serialize(),
								function(data){
										spinner.spin(false);
										$("#layout-center").empty();
										$("#layout-center").append(data);
										$('#categories_form').fadeIn(300);
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
echo form_open('categories/newcategories',array('id'=>'categoriesform'));
echo form_fieldset("Categories");
echo '<div class="_50">';	
				echo form_label( $this->lang->line('msg_Categorylabel'), 'categorylabel').form_input(array('name'=>'categorylabel','value'=>set_value('categorylabel')));
echo '</div>';
echo '<div class="_50">';
			echo form_label( $this->lang->line('msg_Categorydescr'), 'categorydescr').form_textarea(array('name'=>'categorydescr'),set_value('categorydescr'));
echo '</div>';

echo form_hidden('idCategory',set_value('idCategory','-1'));

echo '<div class="_100">';
				echo form_submit(array('name'=>'submit','id'=>'categoriesformbtnSend'), $this->lang->line('msg_Submit'));
				echo nbs(3);
				echo '<button type="button" id="categoriesformbtnCancel">'.$this->lang->line('msg_Cancel').'</button>';
echo '</div>';

echo form_fieldset_close();
echo form_close();

?>
</div>
