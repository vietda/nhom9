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

table {width:100%;}
thead {text-align:center;}
th {colspan:4;}
td.label{width:50%;text-align:left; padding:5px;}
td.unitprice,td.qty,td.value {width:15%; text-align:right;padding:5px;} 

</style>
<script>
 function printElement(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data) 
    {
        var mywindow = window.open('', 'mydiv', 'height=600,width=800');
        mywindow.document.write('<html><head><title>InnerDiv</title>');
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
					$.post("<?php echo base_url(); ?>index.php/bar/updateRowStatus",{ksid:ksid,id:id,status:'received'},
															function(data){
															});
				}
				if(tagName+"-2"==$(this).attr('id')){
					$("#row-"+id).css('text-decoration','none');
					$.post("<?php echo base_url(); ?>index.php/bar/updateRowStatus",{ksid:ksid,id:id,status:'inprogress'},
															function(data){
															});
				}
				if(tagName+"-3"==$(this).attr('id')){
					$("#row-"+id).css('text-decoration','line-through');
					$.post("<?php echo base_url(); ?>index.php/bar/updateRowStatus",{ksid:ksid,id:id,status:'done'},
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
								 	$.post("<?php echo base_url(); ?>index.php/checkedout/setDone",{ksid:ksid},
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
<div style="margin:0px 10px;overflow:hidden;"><button id="kprint"><?php echo $this->lang->line('msg_Print');?>
		</button>&nbsp;<button id="kdone"><?php echo $this->lang->line('msg_Done');?></button></div>
<div id="kitchen_header" class="kitchen_header">
</div>
<div style="margin:0px 10px;">
<?php 
	
	$arrData = json_decode($data->ticket,TRUE);

	//echo var_dump($arrData);

	$total = $arrData['total'];
	$fixedArr = $arrData['fixed'];
	$carteArr = $arrData['carte'];
	
	echo '<table>';
	
	if($fixedArr['adultsnr']>0 || $fixedArr['childrennr']>0){
		echo '<thead>'.
			 '<tr><th colspan="4">Fixed</th></tr>'.
	         '</thead>';
	}
	
	
	if($fixedArr['adultsnr']>0){
		echo '<tr class="even">'.
				'<td class="label">Adults</td>'.
				'<td class="unitprice">'.number_format($fixedArr['adults']/$fixedArr['adultsnr'],2).'</td>'.
				'<td class="qty">x'.$fixedArr['adultsnr'].'</td>'.
				'<td class="value">'.number_format($fixedArr['adults'],2).'</td>'.
				'</tr>';
	}
	if($fixedArr['childrennr']>0){
		echo '<tr class="odd">'.
				'<td class="label">Children</td>'.
				'<td class="unitprice">'.number_format($fixedArr['children']/$fixedArr['childrennr'],2).'</td>'.
				'<td class="qty">x'.$fixedArr['childrennr'].'</td>'.
				'<td class="value">'.number_format($fixedArr['children'],2).'</td>'.
				'</tr>';
	}
	
	if(sizeof($carteArr)>0){
		echo '<thead>'.
			 '<tr><th colspan="4">'.$this->lang->line('msg_Carte').'</th></tr>'.
	         '</thead>';
		$i=0;
		foreach($carteArr as $row){
		  if($i%2)
			echo '<tr class="odd">';
		  else
			echo '<tr class="even">';
		echo '<td class="label">'.$row['label'].'</td>'.
			'<td class="unitprice">'.number_format($row['price']/$row['number'],2).'</td>'.
			'<td class="qty">x'.$row['number'].'</td>'.
			'<td class="value">'.number_format($row['price'],2).'</td>'.
			'</tr>';
		$i++;
		}
	}
	
	 echo '<tr class="type"><td class="value" colspan="3">TOTAL</td><td class="value">'.number_format($total,2).'</td></tr>';
	
	echo '</table>';
	
?>
</div>
