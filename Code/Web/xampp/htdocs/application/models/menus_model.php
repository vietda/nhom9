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

class Menus_model extends CI_Model {
	
	function countMenus(){
		
		$this->db->where('deleted','n');
		return $this->db->count_all_results('menus');
	
	}
	
	function saveMenu($data){
		
		$this->db->insert('menus',$data);
	}
	
	function updateMenu($data){
		$this->db->where('idMenus',$data['idMenus']);
		$this->db->update('menus',$data);
	}
	
	function getFoodMenus(){
		
		$this->db->order_by('position','asc');
		$this->db->where('deleted','n');
		$this->db->where('foodbev','f');
		$query = $this->db->get('menus');
		return $query->result();
		
	}
	
	function getDrinksMenus(){
		
		$this->db->order_by('position','asc');
		$this->db->where('deleted','n');
		$this->db->where('foodbev','b');
		$query = $this->db->get('menus');
		return $query->result();
		
	}
	
	function deleteMenu($idMenus){
		$this->db->update("menus",array('deleted'=>'y'),array('idMenus'=>$idMenus));
		if($this->db->_error_message()){
			return 1;
		}
		return 0;
	}
	
	function getJSONMenuByID($idMenus){
		$query = $this->db->get_where('menus',array('idMenus'=>$idMenus));
		return json_encode($query->result());
	}
	
	function updatePositions($idMenus,$data){
		$this->db->where('idMenus',$idMenus);
		$this->db->update('menus',$data);
	}
	
}
