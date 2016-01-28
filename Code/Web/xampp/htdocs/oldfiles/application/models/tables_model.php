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

class Tables_model extends CI_Model{
	
	function countTables(){
		$this->db->count_all_results('tables');
	}
	
	function getTables(){
	
		$this->db->order_by('tableName','ASC');
		$query = $this->db->get('tables');
		return $query->result();
	}
	
	function getJSONTableByID($idTables){
		$query = $this->db->get_where('tables',array('idTables'=>$idTables));
		return json_encode($query->result());
	}
	
	function updateTable($data){
		$this->db->where('idTables',$data['idTables']);
		$this->db->update('tables',$data);
	}
	
	function saveTable($data){
		$this->db->insert('tables',$data);
	}
	
	function deleteTable($idTables){
		$this->db->delete('tables',array('idTables'=>$idTables));
	}
}

?>
