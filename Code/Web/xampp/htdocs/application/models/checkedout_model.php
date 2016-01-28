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

class Checkedout_Model extends CI_Model{
	
	function getTicketsList(){
	
		$this->db->select('tables.tableName,tickets.idTickets,sessions.bell,sessions.sid');
		$this->db->from('tickets');
		$this->db->join('sessions','sessions.sid=tickets.fk_sid');
		$this->db->join('tables','sessions.fk_idTables=tables.idTables');
		$query = $this->db->get();
		return($query->result());
	}
	
	function getTicketById($idTickets){
		$this->db->where('idTickets',$idTickets);
		$query = $this->db->get('tickets');
		return $query->row();
	}
	
	function setDone($id){
		$this->db->where('idTickets',$id);
		$this->db->delete('tickets');
	}
	
}

?>
