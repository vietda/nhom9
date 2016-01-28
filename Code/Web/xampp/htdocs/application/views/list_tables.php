<style>
ul { list-style-type: none; margin: 0 30px 30px 30px; padding: 0;align:center;width:auto;}
ul li { margin: 0 6px 6px 6px; padding: 5px 15px 0px 5px ; font-size: .9em; width:auto;text-align:right;}
.ui-state-highlight { height: 2em; line-height: 1.2em; }
</style>
<script>

	$(function(){
		
		$('button').button();
		$('li>button').css('border-color','grey');
		
		$('#no_tables_new').click( function(){
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/tables/newtable"; ?>',
								   function(data){
								    spinner.spin(false);
									$('#layout-center').html( data);
									$('#tables_form').fadeIn(300);
									}, "html");
							});
		
		$("button[id*='tabledtl-']").click(function(){
								var spinner = $('#layout-center').spin();
								var tagId = $(this).attr("id");
								var idTables = tagId.substr(tagId.indexOf('-')+1);
								$.post("<?php echo base_url(); ?>index.php/tables/getJSONTableByID",{idTables:idTables},
										function(data){											
											$.post("<?php echo base_url(); ?>index.php/tables/newtable",
												{	tablelabel:data[0].tableName,
													idTables:data[0].idTables,
													edit:'y'},
												function(data){
													spinner.spin('false');
													$("#layout-center").empty();
													$("#layout-center").append(data);
													$('#tables_form').fadeIn(300);
											});
										},"json"
									);
							});

		$("button[id*='tabledel-']").click(function(){
								var tagId = $(this).attr("id");
								var idTables = tagId.substr(tagId.indexOf('-')+1);
								$('#dialog').html('<?php echo $this->lang->line('msg_DelTable');?>');
								$('#dialog').data('idTables',idTables).dialog('open');
							});
							
		$('#dialog').dialog("destroy");
		$('#dialog').dialog({autoOpen:false,modal:true});
		$('#dialog').dialog({ buttons: 
					{
						"Yes": function() {
								$( this ).dialog( "close" );
								 var spinner = $('#layout-center').spin();
								$.post("<?php echo base_url(); ?>index.php/tables/deleteTable",{idTables:$(this).data('idTables')},
										function(data){
											spinner.spin('false');
											$("#layout-center").empty();
											$("#layout-center").append(data);
											$('#tables_form').fadeIn(300);
										}
									);
								},
						"No": function() {
						$( this ).dialog( "close" );
						}
					}});
		
		$('#listtablescontent').fadeIn(300);
		
	});

</script>

<div id="dialog" title="Confirm"></div>
<?php
if(isset($is_Error) && $is_Error==TRUE){
	$data['msg'] = $error_message;
	$this->load->view('includes/errordialog',$data);
}
?>
<div id="listtablescontent" style="display:none;width: 50%;margin:auto;">

<div style="width:100%;text-align:center;align:center;">
<button id="no_tables_new" style="margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_CreateNewTable');?></button> 
<button id="tablessave" style="display:none;margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_Submit');?>
		</button>
</div>
<hr/>

<div id="listtables" class="_100" style="background: #821C1C;">
	<h2><?php echo $this->lang->line('msg_Tables'); ?></h2>

		<ul id="sortabletables">
		<?php foreach($tables as $row){
			$idTables = $row->idTables;
			echo '<li id="tableli-'.$idTables.'" class="ui-state-default"><div style="float:left;height:100%;padding:5px;">'.$row->tableName.nbs(4).'</div>'.
			'<button id="tabledtl-'.$idTables.'">'.$this->lang->line('msg_EditDetails').'</button><button id="tabledel-'.$idTables.'">'.$this->lang->line('msg_Delete').'</button></li>';
		}
		?>
		</ul>
</div>

</div>
