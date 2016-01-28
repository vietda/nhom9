<style>

#kitchen_header {
				background:#999999;
				color: #ffffff;
				text-align:center;
				text-shadow: 0px 1px 1px #000; margin:5px;
				font-size:26px; font-weight:bold; border-radius:3px;
				}
#kitchen_mtype div{}

 .radio {float:right;}
label{width:auto;}
#kright_content .ui-button-text{font-size:10px;}
.type{text-align:center;font-size:22px;font-weight:bold;margin-top:0px;background:#B24C4C;color:#fff;}

.even {background:#ccc; font-size:18; padding:5px; margin-bottom:1px;font-weight:bold;border-radius:1px;overflow:hidden;}
.odd  {background:#ddd; font-size:18; padding:5px; margin-bottom:1px;font-weight:bold;border-radius:1px;overflow:hidden;}


</style>
<script>
 function printElement(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data) 
    {
        var mywindow = window.open('', 'mydiv', 'height=600,width=800');
        mywindow.document.write('<html><head><title>my div</title>');
        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.close();
        mywindow.print();
        return true;
    }

$(function() {
		$(".radio").buttonset();
		$('button').button();
		
		$("input[name*='radio-']").change(
			function(){
				var tagName = $(this).attr('name');
				var id = tagName.substr(tagName.indexOf('-')+1);
				if(tagName+"-1"==$(this).attr('id')){
					$("#row-"+id).css('text-decoration','none');
					$.post("<?php echo base_url(); ?>index.php/kitchen/updateRowStatus",{ksid:ksid,id:id,status:'received'},
															function(data){
															});
				}
				if(tagName+"-2"==$(this).attr('id')){
					$("#row-"+id).css('text-decoration','none');
					$.post("<?php echo base_url(); ?>index.php/kitchen/updateRowStatus",{ksid:ksid,id:id,status:'inprogress'},
															function(data){
															});
				}
				if(tagName+"-3"==$(this).attr('id')){
					$("#row-"+id).css('text-decoration','line-through');
					$.post("<?php echo base_url(); ?>index.php/kitchen/updateRowStatus",{ksid:ksid,id:id,status:'done'},
															function(data){
															});
				}
			});
			
			$('#dialog').dialog("destroy");
			$('#dialog').dialog({autoOpen:false,modal:true});
			
			$('#dialog').dialog({ buttons: 
					{
						"Yes": function() {
								$( this ).dialog( "close" );
								 var spinner = $('#kright_center').spin();
								 	$.post("<?php echo base_url(); ?>index.php/kitchen/setDone",{ksid:ksid},
										function(data){
												spinner.spin('false');
												$('#table-'+ksid).fadeOut(200);
												/*$('#table-'+ksid).remove();*/
												$('#kright_content').children().fadeOut(200);
												/*$('#kright_content').children().remove();*/
										});
			
							},
						"No": function() {
						$( this ).dialog( "close" );
						}
					}});
			
			$('#kdone').click(function(){
								/*$('#dialog').html('<?php echo $this->lang->line('msg_DelItem');?>');*/
								$('#dialog').dialog('open');
								
						});
			
		    /*$('#kprint').click(function(){printElement('#kright_content');});*/
	});
</script>
<div id="dialog" title="Confirm"></div>
<div style="margin:0px 10px;overflow:hidden;">
	<button id="kprint"><?php echo $this->lang->line('msg_Print');?>
	</button>&nbsp;<button id="kdone"><?php echo $this->lang->line('msg_Done');?></button>
</div>
<div id="kitchen_header" class="kitchen_header">
</div>
	
<div style="margin:0px 10px;">
<?php 
	if(isset($data['f']))
		$fixed = $data['f'];
	if(isset($data['c']))
		$carte = $data['c'];
	
	if(isset($fixed)){
			echo '<div class="type">Fixed</div>';
			$i=0;
			foreach($fixed as $row){
				if($i%2){
					$id = $row['idOrders'];
					//$idOrd = $row['idOrders'];
					$recv = $row['status']=="received"?'checked="checked"':"";
					$inprog = $row['status']=="inprogress"?'checked="checked"':"";
					$done = $row['status']=="done"?'checked="checked"':"";
					$doneCss = $row['status']=="done"?"text-decoration:line-through;":"";
					echo '<div class="odd"><div id="row-'.$id.'" style="float:left;'.$doneCss.'">'.$row['number'].' '.$row['label'].
					 '</div><div class="radio">'.
		'<input type="radio" id="radio-'.$id.'-1" name="radio-'.$id.'"  '.$recv.'/><label for="radio-'.$id.'-1">Received</label>'.
		'<input type="radio" id="radio-'.$id.'-2" name="radio-'.$id.'" '.$inprog.'/><label for="radio-'.$id.'-2">In progress</label>'.
		'<input type="radio" id="radio-'.$id.'-3" name="radio-'.$id.'" '.$done.'/><label for="radio-'.$id.'-3">Done</label>'.
	'</div>'.
					'</div>';
				}
				else{
					$id = $row['idOrders'];
					//$idOrd = $row['idOrders'];
					$recv = $row['status']=="received"?'checked="checked"':"";
					$inprog = $row['status']=="inprogress"?'checked="checked"':"";
					$done = $row['status']=="done"?'checked="checked"':"";
					$doneCss = $row['status']=="done"?"text-decoration:line-through;":"";
					echo '<div class="even"><div id="row-'.$id.'" style="float:left;'.$doneCss.'">'.$row['number'].' '.$row['label'].
					 '</div><div class="radio">'.
		'<input type="radio" id="radio-'.$id.'-1" name="radio-'.$id.'"  '.$recv.'/><label for="radio-'.$id.'-1">Received</label>'.
		'<input type="radio" id="radio-'.$id.'-2" name="radio-'.$id.'"  '.$inprog.'/><label for="radio-'.$id.'-2">In progress</label>'.
		'<input type="radio" id="radio-'.$id.'-3" name="radio-'.$id.'"  '.$done.'/><label for="radio-'.$id.'-3">Done</label>'.
	'</div>'.
					'</div>';
				}
				$i++;
			}
	}
	
	if(isset($carte)){
			echo '<div class="type">'.$this->lang->line('msg_Carte').'</div>';
			$i=0;
			foreach($carte as $row){
					$id = $row['idOrders'];
					//$idOrd = $row['idOrders'];
					$recv = $row['status']=="received"?'checked="checked"':"";
					$inprog = $row['status']=="inprogress"?'checked="checked"':"";
					$done = $row['status']=="done"?'checked="checked"':"";
					$doneCss = $row['status']=="done"?"text-decoration:line-through;":"";
					if($i%2){
						echo '<div class="odd">';
					}
					else{
						echo '<div class="even">';
					}
					
					echo '<div id="row-'.$id.'" style="float:left;'.$doneCss.'">'.$row['number'].' '.$row['label'].
					 '</div><div class="radio">'.
		'<input type="radio" id="radio-'.$id.'-1" name="radio-'.$id.'"  '.$recv.'/><label for="radio-'.$id.'-1">'.$this->lang->line('msg_Print').'</label>'.
		'<input type="radio" id="radio-'.$id.'-2" name="radio-'.$id.'" '.$inprog.'/><label for="radio-'.$id.'-2">'.$this->lang->line('msg_InProgress').'</label>'.
		'<input type="radio" id="radio-'.$id.'-3" name="radio-'.$id.'" '.$done.'/><label for="radio-'.$id.'-3">'.$this->lang->line('msg_Done').'</label>'.
	'</div><br/>&nbsp;&nbsp;&nbsp;&nbsp;<div>'.$this->lang->line('msg_Note').':&nbsp;<i>'.$row['note'].'</i></div>'.
					'</div>';
				
				/*else{
					$id = $row['idOrders'];
					//$idOrd = $row['idOrders'];
					$recv = $row['status']=="received"?'checked="checked"':"";
					$inprog = $row['status']=="inprogress"?'checked="checked"':"";
					$done = $row['status']=="done"?'checked="checked"':"";
					$doneCss = $row['status']=="done"?"text-decoration:line-through;":"";
					echo '<div class="even"><div id="row-'.$id.'" style="float:left;'.$doneCss.'">'.$row['number'].' '.$row['label'].
					 '</div><div class="radio">'.
		'<input type="radio" id="radio-'.$id.'-1" name="radio-'.$id.'"  '.$recv.'/><label for="radio-'.$id.'-1">Received</label>'.
		'<input type="radio" id="radio-'.$id.'-2" name="radio-'.$id.'"  '.$inprog.'/><label for="radio-'.$id.'-2">In progress</label>'.
		'<input type="radio" id="radio-'.$id.'-3" name="radio-'.$id.'"  '.$done.'/><label for="radio-'.$id.'-3">Done</label>'.
	'</div><br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;<div>Note:&nbsp;<i>'.$row['note'].'</i></div>'.
					'</div>';
				}*/
				$i++;
			}
	}
	
?>
</div>
