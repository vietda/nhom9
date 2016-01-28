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

class Categories_model extends CI_Model {

	function countCategories(){
	
		return $this->db->count_all('categories');
	
	}
	
	function saveCategory($data){
		
		$this->db->insert('categories',$data);
	}
	
	function updateCategory($data){
		$this->db->where('idCategories',$data['idCategories']);
		$this->db->update('categories',$data);
	}
	
	function getCategories(){
		
		$query = $this->db->get('categories');
		return $query->result();
	}
	
	function deleteCategory($idCategory){
	
		$this->db->delete('categories',array("idCategories"=>$idCategory));
		
	}
	
	function getJSONCategoryByID($idCategories){
		$query = $this->db->get_where('categories',array('idCategories'=>$idCategories));
		return json_encode($query->result());
	}
}

?>
