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

class Kitchen_model extends CI_Model{
	
	function getOrderQueue(){
		$this->db->select('tables.tableName,sessions.idSessions,kqueue.sid,sessions.bell');
		$this->db->distinct();
		$this->db->from('kqueue');
		$this->db->join('sessions','kqueue.fk_sid=sessions.sid');
		$this->db->join('tables','sessions.fk_idTables=tables.idTables');
		$this->db->order_by('timestamp ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getTicketBySid($sid){
		$this->db->select('fk_idMenulists,number,kqueue.status,idOrders,note');
		$this->db->from('kqueue');
		$this->db->join('orders','fk_idOrders=idOrders');
		$this->db->where('sid',$sid);
		$query = $this->db->get();
		
		$arrItems = NULL;
		foreach($query->result() as $row){
			$idMenulists[$row->fk_idMenulists]['nr']= $row->number;
			$idMenulists[$row->fk_idMenulists]['status']= $row->status;
			$idMenulists[$row->fk_idMenulists]['idOrders']= $row->idOrders;
			$idMenulists[$row->fk_idMenulists]['note']= $row->note;
			$arrItems[] = $row->fk_idMenulists; 
		}
		
		$this->db->select('idMenulists,items.label');
		$this->db->from('menulists');
		$this->db->join('items','menulists.fk_idItems=items.idItems');
		$this->db->join('menus','menulists.fk_idMenus=menus.idMenus');
		$this->db->where_in('idMenulists',$arrItems);
		$this->db->where('menuType','f');
		$query = $this->db->get();
		$fixed = $query->result();
		
		$this->db->select('idMenulists,items.label');
		$this->db->from('menulists');
		$this->db->join('items','menulists.fk_idItems=items.idItems');
		$this->db->join('menus','menulists.fk_idMenus=menus.idMenus');
		$this->db->where_in('idMenulists',$arrItems);
		$this->db->where('menuType','c');
		$query = $this->db->get();
		$carte = $query->result();
		
		//create return data
		
		$returnData = NULL;
		
		foreach($fixed as $row){
			$returnData['f'][] = array('idMenulists'=>$row->idMenulists,'label'=>$row->label,
				'number'=>$idMenulists[$row->idMenulists]['nr'],'note'=>$idMenulists[$row->idMenulists]['note'],
				'status'=>$idMenulists[$row->idMenulists]['status'],'idOrders'=>$idMenulists[$row->idMenulists]['idOrders']); 
		}
		
		foreach($carte as $row){
			$returnData['c'][] = array('idMenulists'=>$row->idMenulists,'label'=>$row->label,
				'number'=>$idMenulists[$row->idMenulists]['nr'],'note'=>$idMenulists[$row->idMenulists]['note'],
				'status'=>$idMenulists[$row->idMenulists]['status'],'idOrders'=>$idMenulists[$row->idMenulists]['idOrders']); 
		}
		
		return $returnData;
	}
	
	function updateRowStatus($ksid,$id,$status){
		$this->db->where(array('sid'=>$ksid,'fk_idOrders'=>$id));
		$data=array('status'=>$status);
		$this->db->update('kqueue',$data);
	}
	
	function setDone($ksid){
		$this->db->where('sid',$ksid);
		$this->db->delete('kqueue');
	}
	
}

?>
