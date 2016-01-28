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

class Upload_model extends CI_Model{

	function saveImage($data){
	
		$this->db->insert('images',$data);
	
	}
	
	function getMedia(){
		
		$this->db->order_by('label','ASC');
		$query = $this->db->get('images');
		return $query->result();
		
	}
	
	function getMediaById($idImages){
		$this->db->where('idImages',$idImages);
		$query = $this->db->get('images');
		if($query->num_rows()>0)
			return $query->row();
		else
			return null;
	}
		
	function deleteMedia($idImages){
		$this->db->delete('images',array('idImages'=>$idImages));
	}
}

?>
