

<script>
$(function(){
		$('#medialist').selectable();	
		$('button').button();
});

</script>

<?php
	foreach ($mediaList as $row){
	 echo '<li style="display:inline-block;" id="medialist-'.$row->idImages.'"><div style="float:left;"><img style="width:100px;display:block;" src="'.base_url().'/media/'.$row->fileName.'"/></div><div style="float:left;font-size:1.2em;font-weight:bold;padding:1em;">'.$row->label.'</div><div style="float:right;padding:1em;"><button id="mediadel-'.$row->idImages.'">'.$this->lang->line('msg_Delete').'</button></div></li>';
	}
?>
