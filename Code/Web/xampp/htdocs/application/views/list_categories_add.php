<style>
	#catlistcontent .ui-selecting { background: #DE6464; }
	#catlistcontent .ui-selected { background: #821C1C; color: white; }
	#catlistcontent { list-style-type: none; margin: 0; padding: 0; width: 90%; }
	#catlistcontent li { margin: 3px; padding: 0.4em; font-size: 1.4em; width:100%;border-bottom:1px solid black;}
</style>
<script>

	$(function(){
				
	$('#catlistcontent').selectable({
			stop: function() {
				
				$( ".ui-selected", this ).each(function() {
					var id = $(this).attr('id');
					var idCategories = id.substr(id.indexOf('-')+1);
					$("#dialogaddcat").data('idCategories',idCategories);
				});
			}
		});
	});

</script>

<div id="catlist">
	<ol id="catlistcontent">
	<?php 
		foreach($category as $row){
			echo '<li style="display:inline-block;" id="catid-'.$row->idCategories.'">'.$row->label.'</li>';
		}
	?>

	</ol>
</div>
