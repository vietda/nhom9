<script>

$(function(){
		$('button').button();
		$('#no_items_new').click( function(){
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/items/newitem"; ?>',
								   function(data){
								    spinner.spin(false);
									$('#layout-center').html( data);
									$('#items_form').fadeIn(300);
									}, "html");
							});
		
	});

</script>
<div style="width:100%;text-align:center;align:center;">
<button id="no_items_new" style="margin-top:50px;font-size:1.1em;padding:20px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_CreateNewItem');?></button> 
</div>
