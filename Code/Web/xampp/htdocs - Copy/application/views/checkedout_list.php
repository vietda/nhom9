<style>
#kleft_list .ui-icon { display: none; }
#kright_accordion .ui-icon {display:none;}

.klist {border:1px solid black;background-color:#AAFF55;width:100%;text-align:center;
		color: #000; font-size:20px; padding:10px 0px 10px 0px;
		text-shadow: 0px 1px 1px #000; margin:5px; cursor:pointer; border-radius:3px;
		}

</style>
<script>

$(function() {
	
		clearTimeout(periodicalTimer);
		$( "#kleft_list" ).accordion({fillSpace: true});
		$('#kright_accordion').accordion({fillSpace: true});
		
	(function worker() {
					  $.ajax({
							url: "<?php echo base_url(); ?>index.php/checkedout/getTicketsQueueContent", 
							success: function(data) {
							  $('#kleft_content').children().remove();
							  $('#kleft_content').html(data);
							  $("div[id*='table-']").click(function(){
														var spinner = $('#kright_content').spin();
														var tagId = $(this).attr('id');
														var header = $(this).html();
														var sid = tagId.substr(tagId.indexOf('-')+1);
														$.post("<?php echo base_url(); ?>index.php/checkedout/getTicketById",{id:sid},
															function(data){
																ksid = sid;
																spinner.spin('false');
																$('#kright_content').children().remove();
																$('#kright_content').html(data);
																$("#kitchen_header").html(header);
															});
														
													});	
							},
							complete: function() {
								periodicalTimer = setTimeout(worker, 5000);
							}
						});
					})();
});

</script>
<div id="kleft_list" style="width:20%;float:left;">
<h5 style="height:1px"></h3>
<div id="kleft_content">

</div> 
</div>
<div id="kright_accordion" style="width:80%;float:left;">
<h5 style="height:1px"></h3>
<div id="kright_content">

</div>
</div>
