<style>
ul { list-style-type: none; margin: 0 30px 30px 30px; padding: 0;align:center;width:auto;}
ul li { margin: 0 6px 6px 6px; padding: 5px 15px 0px 5px ; font-size: .9em; width:auto;text-align:right;}
.ui-state-highlight { height: 2em; line-height: 1.2em; }
</style>
<script>
	$(function() {
		$.ajaxSetup({cache: false});
		$('button').button();
		$('#no_menus_new').click( function(){
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/menus/newmenu"; ?>',
								   function(data){
								    spinner.spin(false);
									$('#layout-center').html( data);
									$('#menus_form').fadeIn(300);
									}, "html");
							});
			

		$('ul').sortable({
			placeholder: "ui-state-highlight",
			update: function(event, ui) { $('#menussave').css('display',''); }
			});
			
		$('#menussave').click( function(){
							var spinner = $('#layout-center').spin();
							var arrayPositions = [];
							$("li[id*='foodli-']").each(function(e){
										var menuIndex = $(this).index() + 1;
										var menuId = $(this).attr('id');
										var tmp = new Object();
										tmp.id = menuId.substr(menuId.indexOf('-')+1);
										tmp.position = menuIndex;
										arrayPositions.push(tmp);										
									});
									
							$("li[id*='drinksli-']").each(function(e){
										var menuIndex = $(this).index() + 1;
										var menuId = $(this).attr('id');
										var tmp = new Object();
										tmp.id = menuId.substr(menuId.indexOf('-')+1);
										tmp.position = menuIndex;
										arrayPositions.push(tmp);										
									});
							
							$.post('<?php echo base_url()."index.php/menus/updatePositions"; ?>',
								   {data:JSON.stringify(arrayPositions)},
								   function(data){
										spinner.spin(false);
										$('#layout-center').html(data);
									}, "html");
							
							$('#menussave').css('display','none');
							});

		$( "ul" ).disableSelection();
		$('button').button();
		
		$('li>button').css('border-color','grey');
		
		$("button[id*='menudel-']").click(function(){
								var tagId = $(this).attr("id");
								var idMenu = tagId.substr(tagId.indexOf('-')+1);
								$('#dialog').html('<?php echo $this->lang->line('msg_DelMenu');?>');
								$('#dialog').data('idMenu',idMenu).dialog('open');
							});	

		$("button[id*='menudtl-']").click(function(){
								var spinner = $('#layout-center').spin();
								var tagId = $(this).attr("id");
								var idMenu = tagId.substr(tagId.indexOf('-')+1);
								$.post("<?php echo base_url(); ?>index.php/menus/getJSONMenuByID",{idMenu:idMenu},
										function(data){											
											$.post("<?php echo base_url(); ?>index.php/menus/newmenu",
												{	menulabel:data[0].label,
													menudescr:data[0].description,
													radiot:data[0].menuType,
													radiov:data[0].visible,
													radiofb:data[0].foodbev,
													idMenu:data[0].idMenus,
													edit:'y'},
												function(data){
													spinner.spin('false');
													$("#layout-center").empty();
													$("#layout-center").append(data);
													$('#menus_form').fadeIn(300);
											});
										},"json"
									);
							});	
		
		$("button[id*='menucnt-']").click(function(){
								var spinner = $('#layout-center').spin();
								var tagId = $(this).attr("id");
								var idMenus = tagId.substr(tagId.indexOf('-')+1);
								$.post("<?php echo base_url(); ?>index.php/content/",{idMenus:idMenus},
										function(data){											
													spinner.spin('false');
													$("#layout-center").empty();
													$("#layout-center").append(data);
													$('#menus_form').fadeIn(300);										
										},"html"
									);
							});	
		
		
		$('#dialog').dialog("destroy");
		$('#dialog').dialog({autoOpen:false,modal:true});
		$('#dialog').dialog({ buttons: 
					{
						"Yes": function() {
								$( this ).dialog( "close" );
								 var spinner = $('#layout-center').spin();
								$.post("<?php echo base_url(); ?>index.php/menus/deleteMenu",{idMenu:$(this).data('idMenu')},
										function(data){
											spinner.spin('false');
											$("#layout-center").empty();
											$("#layout-center").append(data);
											$('#menus_form').fadeIn(300);
										}
									);
								},
						"No": function() {
						$( this ).dialog( "close" );
						}
					}});
		
		$('#listmenuscontent').fadeIn(300);
	});
</script>
<div id="dialog" title="Confirm"></div>
<?php
if(isset($is_Error) && $is_Error==TRUE){
	$data['msg'] = $error_message;
	$this->load->view('includes/errordialog',$data);
}
?>
<div id="listmenuscontent"  style="display:none;width: 80%;margin:auto;">
<div style="width:100%;text-align:center;align:center;">
<button id="no_menus_new" style="margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_CreateNewMenu');?></button> 
<button id="menussave" style="display:none;margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_Submit');?>
		</button>
</div>
<hr/>
<div id="listmenusfood" class="_100" style="background: #821C1C;">
	<h2><?php echo $this->lang->line('msg_Food');?></h2>

		<ul id="sortablefood">
<?php foreach($food as $row){
			$idMenus = $row->idMenus;
			echo '<li id="foodli-'.$idMenus.'" class="ui-state-default"><div style="float:left;height:100%;padding:5px;">'.$row->label.'</div>'.
			'<button id="menudtl-'.$idMenus.'">'.$this->lang->line('msg_EditDetails').'</button><button id="menucnt-'.$idMenus.'">'.$this->lang->line('msg_EditContent').'</button><button id="menudel-'.$idMenus.'">'.$this->lang->line('msg_Delete').'</button></li>';
	}
?>
			
		</ul>
</div>
<div class="clear"/>
<div id="listmenusdrinks" class="_100" style="background: #821C1C;">
	<h2><?php echo $this->lang->line('msg_Drinks');?></h2>
	<ul id="sortabledrinks">
<?php foreach($drinks as $row){
		$idMenus = $row->idMenus;
		echo '<li id="drinksli-'.$idMenus.'" class="ui-state-default"><div style="float:left;height:100%;padding:5px;">'.$row->label.nbs(4).'</div>'.
			'<button id="menudtl-'.$idMenus.'">'.$this->lang->line('msg_EditDetails').'</button><button id="menucnt-'.$idMenus.'">'.$this->lang->line('msg_EditContent').'</button><button id="menudel-'.$idMenus.'">'.$this->lang->line('msg_Delete').'</button></li>';
		}
?>
			
		</ul>
</div>
</div>

