
<!DOCTYPE html> 
<html > 
<head> 
<link rel="shortcut icon" href="./images/favicon.ico" /> 
<meta http-equiv="Content-Script-Type" content="text/javascript" /> 
<meta name="robots" content="index, follow" /> 
<meta name="keywords" content="" /> 
<meta name="title" content="Easymenu Admin" /> 
<meta name="description" content="Easymenu" /> 
<title>Easymenu</title> 
<!--
/*
*Copyright 2012 Gianrico D'Angelis  -- gianrico.dangelis@gmail.com
*
*Licensed under the Apache License, Version 2.0 (the "License");
*you may not use this file except in compliance with the License.
*You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*
*Unless required by applicable law or agreed to in writing, software
*distributed under the License is distributed on an "AS IS" BASIS,
*WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*See the License for the specific language governing permissions and
*limitations under the License.
*/
-->
<link type="text/css" href="<?php echo base_url();?>css/custom-theme/jquery-ui-1.8.18.custom.css" rel="Stylesheet" />	
<link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" /> 
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.layout.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/spin.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.spin.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/json2.js"></script>
 <style>
 
 .ui-layout-toggler {background-color:#ff0000;}
  #siteheader {width:100%;}
 
 </style>
 <script>
	var periodicalTimer;
	$(function() {
		
		$.ajaxSetup({cache: false});
		$('body').layout({ applyDefaultStyles: true,
						   resizable:	true,
						   spacing_open:	5,
						   spacing_closed:	10
						   });
		
		$( "#leftmenu" ).accordion({
			fillSpace: true
		});
		$('button').button();
		$('#logout').click( function(){
								clearTimeout(periodicalTimer);
								window.location = '<?php echo base_url()."index.php/login/logout" ?>';
							});
		$('#menus').click( function(){
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/menus/"; ?>',
								   function(data){
								    spinner.spin(false);
									clearTimeout(periodicalTimer);
									$('#layout-center').html( data);
									}, "html");
							});
							
		$('#categories').click( function(){
							 var spinner = $('#layout-center').spin();
							 clearTimeout(periodicalTimer);
							 $.get('<?php echo base_url()."index.php/categories/"; ?>',
								   function(data){
								    spinner.spin(false);
									$('#layout-center').html( data);
									}, "html");
							});
							
		$('#items').click( function(){
							 var spinner = $('#layout-center').spin();
							 clearTimeout(periodicalTimer);
							 $.get('<?php echo base_url()."index.php/items/"; ?>',
								   function(data){
								    spinner.spin(false);
									$('#layout-center').html( data);
									}, "html");
							});
		
		$('#tables').click( function(){
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/tables/"; ?>',
								   function(data){
								    spinner.spin(false);
									clearTimeout(periodicalTimer);
									$('#layout-center').html( data);
									}, "html");
							});
							
		/* Administration section */
		
		$('#users').click( function(){
							clearTimeout(periodicalTimer);
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/users/"; ?>',
								   function(data){
								    spinner.spin(false);
									clearTimeout(periodicalTimer);
									$('#layout-center').html( data);
									}, "html");
							});
		
		$('#backup').click( function(){
							clearTimeout(periodicalTimer);
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/backup/"; ?>',
								   function(data){
								    spinner.spin(false);
									clearTimeout(periodicalTimer);
									$('#layout-center').html( data);
									}, "html");
							});
		
		$('#settings').click( function(){
					 var spinner = $('#layout-center').spin();
					 $.get('<?php echo base_url()."index.php/settings/getJSONSettings"; ?>',
						   function(data){
								$.post('<?php echo base_url()."index.php/settings/save"; ?>',
										{lang:data[0].servuilang,
										 mininterval:data[0].mininterval,
										 maxrounds:data[0].maxrounds,
										 maxitems:data[0].maxitems,
										 radiom:data[0].restmode,
										 radiodisp:data[0].displaymode,
										 currency:data[0].currency,
										 priceadults:data[0].adults,
										 pricechildren:data[0].children,
										 save:'n'
										},
										function(data){
											spinner.spin(false);
											clearTimeout(periodicalTimer);
											$('#layout-center').html( data);
										}
								);
							}, "json");
					});
		
		//Kitchen Section
		
		$('#kitchenord').click( function(){
							clearTimeout(periodicalTimer);
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/kitchen/"; ?>',
								   function(data){
								    spinner.spin(false);
									clearTimeout(periodicalTimer);
									$('#layout-center').html( data);
									}, "html");
							});
		
		//Bar section
		$('#barord').click( function(){
							clearTimeout(periodicalTimer);
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/bar/"; ?>',
								   function(data){
								    spinner.spin(false);
									clearTimeout(periodicalTimer);
									$('#layout-center').html( data);
									}, "html");
							});
		//Manage orders section
		$('#checkoutord').click( function(){
							 clearTimeout(periodicalTimer);
							 var spinner = $('#layout-center').spin();
							 $.get('<?php echo base_url()."index.php/checkedout/"; ?>',
								   function(data){
								    spinner.spin(false);
									clearTimeout(periodicalTimer);
									$('#layout-center').html( data);
									}, "html");
							});
		
	});
</script>
 
</head> 
<body>
<!-- <div id="siteheader">HEADER </div> -->
<div id="layout-center" class="ui-layout-center"></div>
<!--<div id="layout-north"  class="ui-layout-north" style="min-height:45px;"> 
	<div style="width:30%;float:left;"><img src='<?php echo base_url()."/img/logo_small_red.png";?>'/></div>
	<div style="width:70%;float:left;">
		<div style="float:right;"><button id="logout">Logout</button></div>
	</div>
</div>-->
<div id="layout-west" class="ui-layout-west">
		<button id="logout">Logout</button>
		&nbsp;&nbsp;<button id="logout"><?php echo $this->lang->line('msg_Help'); ?></button><br/><hr/>
		<?php $data['role']=$role; $this->load->view('includes/leftmenu',$data); ?>
		
		<br/><div style="text-align:right;color:#aaaaaa;font-size:10px;position:absolute;bottom:10px;right:10px;">Powered by <a  href="#">Easymenu</a></div>
		</div>

</body>
