<style>
 ul { list-style-type: none; margin: 0 30px 30px 30px; padding: 0;align:center;width:auto;}
 ul  li { margin: 0 6px 6px 6px; padding: 5px 15px 0px 5px ; font-size: .9em; width:auto; text-align:right;}
 li h3{color:black;}
	.ui-state-highlight { height: 4em; line-height: 3em; }
</style>
<script>
$(function(){
	
	$('button').button();
	
	$('#dialogitem').dialog("destroy");
	$('#dialogitem').dialog({autoOpen:false,modal:true,height:600,width:800});
	$('#dialogitem').dialog({buttons:
									{"Close": function(){	
													$(this).dialog('close');
											   }
									}
							})
	
	$('#additem').click(function(){
						var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/items/listItems1"; ?>',
								   function(data){
								    spinner.spin(false);
									$('#dialogitem').html( data);
									$('#dialogitem').dialog('open');
									}, "html");
						});
						
	$('#additemcancel').click( function(){
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/menus/"; ?>',
								   function(data){
								    spinner.spin(false);
									$('#layout-center').html( data);
									}, "html");
							});
							
	$('#additemsave').click( function(){
							 var spinner = $('#layout-center').spin();
							var arrayPositions = [];
							$("li[id*='catli-']").each(function(e){
										var catIndex = $(this).index() + 1;
										$(this).find('li').each(function(e){
													var itemIndex = $(this).index() + 1;
													var menuitemId = $(this).attr('id');
													var tmp = new Object();
													tmp.id = menuitemId.substr(menuitemId.indexOf('-')+1);
													tmp.catPos = catIndex;
													tmp.itemPos = itemIndex;
													arrayPositions.push(tmp);
												});
									});
							
							$.post('<?php echo base_url()."index.php/content/updatePositions"; ?>',
								   {idMenus:<?php echo $idMenus;?>,data:JSON.stringify(arrayPositions)},
								   function(data){
										spinner.spin(false);
										$('#layout-center').html(data);
									}, "html");
							
							$('#additemsave').css('display','none');
							});
	
	$('#menucatlist').sortable({placeholder: "ui-state-highlight",
								update: function(event, ui) { $('#additemsave').css('display',''); }
								});
	
	
	$("ul[id*='catul-']").sortable({placeholder: "ui-state-highlight",
									update: function(event, ui) { $('#additemsave').css('display',''); }
									});
	
	$('li').css('border-color','grey');
	
	$("button[id*='btndel-']").click(function(){
									var tagId = $(this).attr("id");
									var idItem = tagId.substr(tagId.indexOf('-')+1);
									$('#dialog').html('<?php echo $this->lang->line('msg_DelMenuItem');?>');
									$('#dialog').data('idItem',idItem).dialog('open');
								});
								
	$('#dialog').dialog("destroy");
	$('#dialog').dialog({autoOpen:false,modal:true});
	$('#dialog').dialog({ buttons: 
				{
					"Yes": function() {
							$( this ).dialog( "close" );
							 var spinner = $('#layout-center').spin();
							$.post("<?php echo base_url(); ?>index.php/content/deleteMenuItem",
							 {idMenus:<?php echo $idMenus;?>,idMenulists:$(this).data('idItem')},
									function(data){
										spinner.spin('false');
										$("#layout-center").empty();
										$("#layout-center").append(data);
										$('#<?php echo "menulists-$idMenus"; ?>').fadeIn(300);
									}
								);
							},
					"No": function() {
					$( this ).dialog( "close" );
					}
				}});
	
	$('#<?php echo "menulists-$idMenus"; ?>').fadeIn(300);
		
});
</script>

<div id="<?php echo "menulists-$idMenus"; ?>" style="display:none;">
<div id="dialog" title="Confirm"></div>
	<div style="width:100%;text-align:center;align:center;">
		<button id="additem" style="margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_AddFoodDrinkItem');?>
		</button>
		<button id="additemsave" style="display:none;margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_Submit');?>
		</button>
		<button id="additemcancel" style="margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_Cancel');?>
		</button> 
	</div><hr/>
	<div id="menulistscontent"  class="_100" style="background:#821C1C;">
	 <h2><?php echo $menuLabel ;?></h2>
		<ul id="menucatlist">
			<?php if($categories==null) return;
					foreach($categories as $row){
						echo '<li class="ui-state-default" id="catli-'.$row['idCategories'].'"><h3>'.$row['category'].'</h3>'.
							 '<ul id="catul-'.$row['idCategories'].'">';
						foreach ($row['items'] as $row){
							echo "<li class=\"ui-state-hover\" id=\"menuitemid-$row->idMenulists\">".
								'<div style="float:left;height:100%;padding:5px;">'.$row->label."</div>".
								//"<button id=\"btnprice-$row->idMenulists\" style=\"border:1px solid grey;\">".$this->lang->line('msg_SetPrice')."</button>".
								"<button id=\"btndel-$row->idMenulists\" style=\"border:1px solid grey;\">".$this->lang->line('msg_Delete')."</button></li>";
						}
						echo '</ul></li>';
					}
			?>
		</ul>
	</div>
</div>

<div id="dialogitem" title="Select Food or Drink item"></div>
