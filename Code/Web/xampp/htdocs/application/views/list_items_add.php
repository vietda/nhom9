<style>
 #sortableitems ul { list-style-type: none; margin: 0 30px 30px 30px; padding: 0;align:center;width:auto;}
 #sortableitems ul, #sortableitems li { margin: 0 6px 6px 6px; padding: 5px 15px 0px 5px ; font-size: .9em; width:auto;text-align:right;}

</style>
<script>

	$(function(){
		$.ajaxSetup({cache: false});
		/*$('ul').sortable({placeholder: "ui-state-highlight",'disabled':true});
		$( "ul" ).disableSelection();*/
		$('button').button();		
		$('li>button').css('border-color','grey');
		
		$("button[id*='itemadd-']").click(function(){
									var spinner = $('#listitems').spin();
									var id = $(this).attr('id');
									var idItems = id.substr(id.indexOf('-')+1);
									var price = $("#price-"+idItems).text();
									$('#dialogitem').data('price',price);
									$('#dialogitem').data('idItems',idItems);
								    $.get('<?php echo base_url()."index.php/categories/listCategories1"; ?>',
								   function(data){
										spinner.spin(false);
										$('#dialogaddcat').html(data);
										$('#dialogaddcat').dialog('open');
										}, "html");
									});
		
		$('#dialogaddcat').dialog('destroy');
		$('#dialogaddcat').dialog({autoOpen:false,modal:true,width:640,height:480});
		$('#dialogaddcat').dialog({buttons:{"OK":function(){
														
														var id = $("div[id*='menulists-']").attr('id');
														var idMenus = id.substr(id.indexOf('-')+1);
														var idItems = $('#dialogitem').data('idItems');
														var price = $('#dialogitem').data('price');
														var idCategories = $('#dialogaddcat').data('idCategories');
														if(idCategories==null){
															alert('Please select a category');
															return false;
														}
														$('#dialogaddcat').dialog('close');
														$('#dialogitem').dialog('close');
														var spinner = $('#layout-center').spin();
														$.post("<?php echo base_url(); ?>index.php/content/addMenuItem",
																{idMenus:idMenus,idItems:idItems,idCategories:idCategories,price:price},
																function(data){
																	spinner.spin('false');
																	$("#layout-center").empty();
																	$("#layout-center").append(data);
																	$('#menus_form').fadeIn(300);		
																});
													}
											}});	
		
			$('#search').keyup(function(event) {
						var search_text = $('#search').val();
						var rg = new RegExp(search_text,'i');
						$("li[id*='itemli-']>div:first-child").each(function(){
							if($.trim($(this).html()).search(rg) == -1) {
								$(this).parent().css('display', 'none');
								//$(this).css('display', 'none');
								//$(this).next().css('display', 'none');
								//$(this).next().next().css('display', 'none');
							}	
							else {
								$(this).parent().css('display', '');
								//$(this).css('display', '');
								//$(this).next().css('display', '');
								//$(this).next().next().css('display', '');
							}
						});
					});
					
		/*$('#search_clear').click(function() {
							$('#search').val('');	
							
							$('#product_list .product-list .product').each(function(){
								$(this).parent().css('display', '');
								$(this).css('display', '');
								$(this).next().css('display', '');
								$(this).next().next().css('display', '');
							});
						});*/
					
		$('#listitemscontent').fadeIn(300);
		
	});
		

</script>

<?php
if(isset($is_Error) && $is_Error==TRUE){
	$data['msg'] = $error_message;
	$this->load->view('includes/errordialog',$data);
}
?>
<div id="listitemscontent" style="display:none;">
	
	<div id="listitems" class="_100" style="background: #821C1C;">
	<h2>Items</h2>
		<div class="_100">
            <div class="search-box" style="float:left; width:257px;">
                <!--<div style="float: left; width: 70px; padding-top: 4px; font-weight: bold;">Search&nbsp;</div>--> 
                <div class="text-field-box">
                   <label for='searchlabel'><?php echo $this->lang->line('msg_Search');?></label> <input name="searchlabel" type="text"  id="search" />
                </div>
                <!--<div class="text-field-cancel-button">
                    <a href="javascript:void(0);" id="search_clear" ><img src="images/search-box-cancel.png" border="0" id="search_clear" /></a>
                </div>-->
            </div>
        </div>
		<div class="_100">
		<ul id="sortableitems">
	<?php foreach($item as $row){
			$idItems = $row->idItems;
			echo '<li id="itemli-'.$idItems.'" class="ui-state-default"><div style="float:left;height:100%;padding:5px;">'.$row->label.nbs(4).'</div><div style="display:none;float:left;" id="price-'.$idItems.'">'.$row->price.'</div>'.
			'<button id="itemadd-'.$idItems.'">'.$this->lang->line('msg_AddToMenu').'</button></li>';
	}
	?>
			
		</ul>
		</div>
	</div>
<div id="dialogaddcat" title="Select a category"></div>	
</div>		

