<?php
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

function log_message($text){
	/*echo '<div style="color:green"><h3>Log</h3>'.$text.'</div>';*/
}

class SyncServer{
 
  private $db; 
 
  function __construct(){
    
	$this->db = DB();
  
  }
  
  function __destruct(){
	
	$this->db->close();
	
  }
 
  public function getMenus(){
	
	$this->db->where('deleted','n');
	$query = $this->db->get('menus');
	return json_encode($query->result());

  }
  
  public function getItems(){
  
	$this->db->where('deleted','n');
  	$query = $this->db->get('items');
	return json_encode($query->result());
  
  }
  
  public function getCategories(){
  
	$query = $this->db->get('categories');
	return json_encode($query->result());
  
  }
  
  public function getMenulists(){
	
	//$this->db->where('deleted','n');
  	$query = $this->db->get('menulists');
	return json_encode($query->result());
  
  }
  
  public function getImages(){
  
	$query = $this->db->get('images');
	return json_encode($query->result());
  
  }
  
  public function getConfig(){
  
	$this->db->where('device','tablet');
	$query = $this->db->get('config');
	return json_encode($query->result());
  
  }
  
 }
 ?>
