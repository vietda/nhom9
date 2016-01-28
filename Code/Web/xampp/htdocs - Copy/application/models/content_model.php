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

class Content_model extends CI_Model{

	function countMenuItems($idMenu){
		
		$this->db->select('idMenulists');
		$this->db->from('menulists');
		$this->db->where('menulists.fk_idMenus',$idMenu);
		$this->db->join('items','menulists.fk_idItems=items.idItems');
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	function getMenuRow($idMenu){
	
		$this->db->select('idMenus,label');
		$query = $this->db->get_where('menus',array('idMenus'=>$idMenu));
		return $query->row();
	}
	
	function addMenuItem($data){
	
		$this->db->insert('menulists',$data);
	}
	
	function getMenulists($idMenus){
		
		$this->db->select('categories.idCategories,categories.label');
		$this->db->distinct();
		$this->db->from('menulists');
		$this->db->join('categories','menulists.fk_idCategories=categories.idCategories');
		$this->db->where('menulists.fk_idMenus',$idMenus);
		$this->db->order_by('categoryPosition','ASC');
		$query = $this->db->get();
		$catRows = $query->result();
		$compound = null;
		foreach ($catRows as $row){
			$this->db->select('menulists.idMenulists,items.label,items.idItems,items.price');
			$this->db->from('menulists');
			$this->db->join('items','menulists.fk_idItems=items.idItems');
			$this->db->where('menulists.fk_idMenus',$idMenus);
			$this->db->where('menulists.fk_idCategories',$row->idCategories);
			$this->db->order_by('itemPosition','ASC');
			$query = $this->db->get();
			$itemsRows = $query->result();
			$compound[] =array('category'=>$row->label,'idCategories'=>$row->idCategories,'items'=>$itemsRows); 
		}
		
		return $compound;
	
	}
	
	function updatePositions($idMenulists,$data){
		$this->db->where('idMenulists',$idMenulists);
		$this->db->update('menulists',$data);
	}

	function deleteMenuItem($idMenulists){
		$this->db->where('idMenulists',$idMenulists);
		$this->db->delete('menulists');
	}
	
}

?>
