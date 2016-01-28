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
		
		$('#no_users_new').click( function(){
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/users/newuser"; ?>',
								   function(data){
								    spinner.spin(false);
									$('#layout-center').html( data);
									$('#users_form').fadeIn(300);
									}, "html");
							});
		
	
		
	    $('#dialog').dialog("destroy");
		$('#dialog').dialog({autoOpen:false,modal:true});
		$('#dialog').dialog({ buttons: 
					{
						"Yes": function() {
								$( this ).dialog( "close" );
								 var spinner = $('#layout-center').spin();
								$.post("<?php echo base_url(); ?>index.php/users/deleteUser",{idUser:$(this).data('idUser')},
										function(data){
											spinner.spin('false');
											$("#layout-center").empty();
											$("#layout-center").append(data);
											$('#users_form').fadeIn(300);
										}
									);
								},
						"No": function() {
						$( this ).dialog( "close" );
						}
					}});

		$("button[id*='userdel-']").click(function(){
								var tagId = $(this).attr("id");
								var idUser = tagId.substr(tagId.indexOf('-')+1);
								$('#dialog').html('<?php echo $this->lang->line('msg_DelUser');?>');
								$('#dialog').data('idUser',idUser).dialog('open');
							});	

		$("button[id*='userdtl-']").click(function(){
								var spinner = $('#layout-center').spin();
								var tagId = $(this).attr("id");
								var idUser = tagId.substr(tagId.indexOf('-')+1);
								$.post("<?php echo base_url(); ?>index.php/users/getJSONUserByID",{idUser:idUser},
										function(data){											
											$.post("<?php echo base_url(); ?>index.php/users/updateuser",
												{	name:data[0].firstName,
													surname:data[0].lastName,
													email:data[0].emailAddress,
													role:data[0].role,
													username:data[0].username,
													idUser:data[0].idUsers,
													edit:'y'},
												function(data){
													spinner.spin('false');
													$("#layout-center").empty();
													$("#layout-center").append(data);
													$('#users_form').fadeIn(300);
											});
										},"json"
									);
							});								
					
					
	$('#listuserscontent').fadeIn(300);
	
	});

</script>
<div id="dialog" title="Confirm"></div>
<?php
if(isset($is_Error) && $is_Error==TRUE){
	$data['msg'] = $error_message;
	$this->load->view('includes/errordialog',$data);
}
?>
<div id="listuserscontent"  style="display:none;width: 80%;margin:auto;">

	 <div style="width:100%;text-align:center;align:center;">
		<button id="no_users_new" style="margin-top:10px;font-size:1em;padding:3px;  margin-left: auto; margin-right: auto;"><?php echo $this->lang->line('msg_CreateNewUser');?>
		</button> 
	</div><hr/> 
	
	<div id="listusers" class="_100" style="background: #821C1C;">
	<h2><?php echo $this->lang->line('msg_Users');?></h2>

		<ul id="sortableusers">
	<?php foreach($users as $row){
			$idUsers = $row->idUsers;
			if($row->username=="admin"){
			
			echo '<li id="userli-'.$idUsers.'" class="ui-state-default"><div style="float:left;height:100%;padding:5px;">'.$row->username.nbs(4).'  |  '.nbs(4).$row->firstName.nbs(2).$row->lastName.'</div>'.
			'<button id="userdtl-'.$idUsers.'">'.$this->lang->line('msg_EditDetails').'</button></li>';
		   }
		   else{
			echo '<li id="userli-'.$idUsers.'" class="ui-state-default"><div style="float:left;height:100%;padding:5px;">'.$row->username.nbs(4).'  |  '.nbs(4).$row->firstName.nbs(2).$row->lastName.'</div>'.
			 '<button id="userdtl-'.$idUsers.'">'.$this->lang->line('msg_EditDetails').'</button><button id="userdel-'.$idUsers.'">'.$this->lang->line('msg_Delete').'</button></li>';
		    }
	}
	?>
			
		</ul>
	</div>
	
</div>		
