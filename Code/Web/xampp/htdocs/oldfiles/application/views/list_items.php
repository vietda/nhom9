<style>
ul { list-style-type: none; margin: 0 30px 30px 30px; padding: 0;align:center;width:auto;}
ul li { margin: 0 6px 6px 6px; padding: 5px 15px 0px 5px ; font-size: .9em; width:auto;text-align:right;}
.ui-state-highlight { height: 2em; line-height: 1.2em; }
</style>
<script>

	$(function(){
		$.ajaxSetup({cache: false});
		$('ul').sortable({placeholder: "ui-state-highlight",'disabled':true});
		$( "ul" ).disableSelection();
		$('button').button();		
		$('li>button').css('border-color','grey');
		
		$('#no_items_new').click( function(){
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/items/newitem"; ?>',
								   function(data){
								    spinner.spin(false);
									$('#layout-center').html( data);
									$('#items_form').fadeIn(300);
									}, "html");
							});
		
		$('#dialog').dialog("destroy");
		$('#dialog').dialog({autoOpen:false,modal:true});
		$('#dialog').dialog({ buttons: 
					{
						"Yes": function() {
								$( this ).dialog( "close" );
								 var spinner = $('#layout-center').spin();
								$.post("<?php echo base_url(); ?>index.php/items/deleteItem",{idItem:$(this).data('idItem')},
										function(data){
											spinner.spin('false');
											$("#layout-center").empty();
											$("#layout-center").append(data);
											$('#items_form').fadeIn(300);
										}
									);
								},
						"No": function() {
						$( this ).dialog( "close" );
						}
					}});

		$("button[id*='itemdel-']").click(function(){
								var tagId = $(this).attr("id");
								var idItem = tagId.substr(tagId.indexOf('-')+1);
								$('#dialog').html('<?php echo $this->lang->line('msg_DelItem');?>');
								$('#dialog').data('idItem',idItem).dialog('open');
							});	

		$("button[id*='itemdtl-']").click(function(){
								var spinner = $('#layout-center').spin();
								var tagId = $(this).attr("id");
								var idItem = tagId.substr(tagId.indexOf('-')+1);
								$.post("<?php echo base_url(); ?>index.php/items/getJSONItemByID",{idItem:idItem},
										function(data){											
											$.post("<?php echo base_url(); ?>index.php/items/newitem",
												{	itemlabel:data[0].label,
													itemdescr:data[0].description,
													idItem:data[0].idItems,
													itemprice:data[0].price,
													idImages:data[0].idImage,
													edit:'y'},
												function(data){
													spinner.spin('false');
													$("#layout-center").empty();
													$("#layout-center").append(data);
													$('#items_form').fadeIn(300);
											});
										},"json"
									);
							});								
					
					
	$('#listitemscontent').fadeIn(300);
		
	});
		

</script>
<div id="dialog" title="Confirm"></div>
<?php
if(isset($is_Error) && $is_Error==TRUE){
	$data['msg'] = $error_message;
	$this->load->view('includes/errordialog',$data);
}
?>
<div id="listitemscontent" style="display:none;">

	<div style="width:100%;text-align:center;align:center;">
		<button id="no_items_new" style="margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_CreateNewItem');?>
		</button> 
	</div><hr/>
	
	<div id="listitems" class="_100" style="background: #821C1C;">
	<h2><?php echo $this->lang->line('msg_Items') ?></h2>

		<ul id="sortableitems">
	<?php foreach($item as $row){
			$idItems = $row->idItems;
			echo '<li id="itemli-'.$idItems.'" class="ui-state-default"><div style="float:left;height:100%;padding:5px;">'.$row->label.nbs(4).'</div>'.
			'<button id="itemdtl-'.$idItems.'">'.$this->lang->line('msg_EditDetails').'</button><button id="itemdel-'.$idItems.'">'.$this->lang->line('msg_Delete').'</button></li>';
	}
	?>
			
		</ul>
	</div>
	
</div>		

