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

class Bar_model extends CI_Model{
	
	function getOrderQueue(){
		$this->db->select('tables.tableName,sessions.idSessions,bqueue.sid,sessions.bell');
		$this->db->distinct();
		$this->db->from('bqueue');
		$this->db->join('sessions','bqueue.fk_sid=sessions.sid');
		$this->db->join('tables','sessions.fk_idTables=tables.idTables');
		$this->db->order_by('timestamp ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function getTicketBySid($sid){
		$this->db->select('fk_idMenulists,number,note,bqueue.status,idOrders,bqueue.fk_sid');
		$this->db->from('bqueue');
		$this->db->join('orders','fk_idOrders=idOrders');
		$this->db->where('sid',$sid);
		$query = $this->db->get();
		
		$sessionid = $query->row()->fk_sid;
		
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
		
		$returnData['sid']=$sessionid;
		
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
	
	function getBellY(){
		$this->db->select('fk_sid');
		$this->db->distinct();
		$query = $this->db->get('bqueue');
		
		$data = $query->result();
		$dataArr = NULL;
		foreach($data as $row){
			$dataArr[] = $row->fk_sid;
		}
		
		$this->db->select('sid,bell,tableName');
		$this->db->from('sessions');
		$this->db->join('tables','sessions.fk_idTables=tables.idTables');
		$this->db->where('bell','y');
		$this->db->where_not_in('sid',$dataArr);
		$query = $this->db->get();
		return $query->result();
	}
	
	function updateRowStatus($ksid,$id,$status){
		$this->db->where(array('sid'=>$ksid,'fk_idOrders'=>$id));
		$data=array('status'=>$status);
		$this->db->update('bqueue',$data);
	}
	
	function setDone($ksid){
		$this->db->where('sid',$ksid);
		$this->db->delete('bqueue');
	}
	
	function clearCallBySid($sid){
		$this->db->where('sid',$sid);
		$this->db->set('bell','n');
		$this->db->update('sessions');
	}
}

?>
