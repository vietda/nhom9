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
<div style="height:60%;">
<div id="leftmenu">
<?php if($role<1){ ?>
<h3><a href="#"><?php echo $this->lang->line('msg_Administration');?></a></h3>
<div>
<button id="users"><?php echo $this->lang->line('msg_Users');?></button><br/>
<button id="backup"><?php echo $this->lang->line('msg_Backup');?></button><br/>
<!-- <button><?php echo $this->lang->line('msg_Apps'); ?></button> -->
<!-- <button><?php //echo $this->lang->line('msg_Licenses');?></button> -->
<button id="settings"><?php echo $this->lang->line('msg_Settings');?></button>
</div>	
<?php }?>
<?php if($role<2){ ?>	
<h3><a href="#"><?php echo $this->lang->line('msg_ConfigMenu');?></a></h3>
<div>
<button id="tables"><?php echo $this->lang->line('msg_Tables');?></button>
<button id="items"><?php echo $this->lang->line('msg_Items');?></button>
<button id="categories"><?php echo $this->lang->line('msg_Categories');?></button><br/>
<button id="menus"><?php echo $this->lang->line('msg_Menus');?></button><br/>
</div>
<?php }?> 
<?php if($role<3){ ?>
<h3><a href="#"><?php echo $this->lang->line('msg_Orders');?></a></h3>
<div>
<button id="checkoutord"><?php echo $this->lang->line('msg_CheckedOut');?></button><br/>
<!-- <button>In session</button><br/> -->
</div>
<?php }?>
<?php if($role<4){ ?>
<h3><a href="#"><?php echo $this->lang->line('msg_Kitchen');?></a></h3>
<div>
 <button id="kitchenord"><?php echo $this->lang->line('msg_KitchenOrders');?></button><br/>
</div>
<?php }?>
<?php if($role<5){ ?>
<h3><a href="#"><?php echo $this->lang->line('msg_Bar');?></a></h3>
<div>
<?php }?>
	<button id="barord"><?php echo $this->lang->line('msg_BarOrders');?></button><br/>
</div>
</div>
</div>
