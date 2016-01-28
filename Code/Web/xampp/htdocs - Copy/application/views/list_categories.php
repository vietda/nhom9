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
		
		$('#no_categories_new').click( function(){
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/categories/newcategory"; ?>',
								   function(data){
								    spinner.spin(false);
									$('#layout-center').html( data);
									$('#categories_form').fadeIn(300);
									}, "html");
							});
		
	
		
	    $('#dialog').dialog("destroy");
		$('#dialog').dialog({autoOpen:false,modal:true});
		$('#dialog').dialog({ buttons: 
					{
						"Yes": function() {
								$( this ).dialog( "close" );
								 var spinner = $('#layout-center').spin();
								$.post("<?php echo base_url(); ?>index.php/categories/deleteCategory",{idCategory:$(this).data('idCategory')},
										function(data){
											spinner.spin('false');
											$("#layout-center").empty();
											$("#layout-center").append(data);
											$('#categories_form').fadeIn(300);
										}
									);
								},
						"No": function() {
						$( this ).dialog( "close" );
						}
					}});

		$("button[id*='categorydel-']").click(function(){
								var tagId = $(this).attr("id");
								var idCategory = tagId.substr(tagId.indexOf('-')+1);
								$('#dialog').html('<?php echo $this->lang->line('msg_DelCategory');?>');
								$('#dialog').data('idCategory',idCategory).dialog('open');
							});	

		$("button[id*='categorydtl-']").click(function(){
								var spinner = $('#layout-center').spin();
								var tagId = $(this).attr("id");
								var idCategory = tagId.substr(tagId.indexOf('-')+1);
								$.post("<?php echo base_url(); ?>index.php/categories/getJSONCategoryByID",{idCategory:idCategory},
										function(data){											
											$.post("<?php echo base_url(); ?>index.php/categories/newcategory",
												{	categorylabel:data[0].label,
													categorydescr:data[0].description,
													idCategory:data[0].idCategories,
													edit:'y'},
												function(data){
													spinner.spin('false');
													$("#layout-center").empty();
													$("#layout-center").append(data);
													$('#categories_form').fadeIn(300);
											});
										},"json"
									);
							});								
					
					
	$('#listcategoriescontent').fadeIn(300);
	
	});

</script>
<div id="dialog" title="Confirm"></div>
<?php
if(isset($is_Error) && $is_Error==TRUE){
	$data['msg'] = $error_message;
	$this->load->view('includes/errordialog',$data);
}
?>
<div id="listcategoriescontent" style="display:none;width: 80%;margin:auto;">

	<div style="width:100%;text-align:center;align:center;">
		<button id="no_categories_new" style="margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_CreateNewCategory');?>
		</button> 
	</div><hr/>
	
	<div id="listcategories" class="_100" style="background: #821C1C;">
	<h2><?echo $this->lang->line('msg_Categories');?></h2>

		<ul id="sortablecategories">
	<?php foreach($category as $row){
			$idCategories = $row->idCategories;
			echo '<li id="categoryli-'.$idCategories.'" class="ui-state-default"><div style="float:left;height:100%;padding:5px;">'.$row->label.nbs(4).'</div>'.
			'<button id="categorydtl-'.$idCategories.'">'.$this->lang->line('msg_EditDetails').'</button><button id="categorydel-'.$idCategories.'">'.$this->lang->line('msg_Delete').'</button></li>';
	}
	?>
			
		</ul>
	</div>
	
</div>		
