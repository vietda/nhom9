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

class Membership_model extends CI_Model {

	function validate()
	{
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('password', md5($this->input->post('password')));
		$query = $this->db->get('users');
		
		if($query->num_rows == 1)
		{
			return true;
		}
		
	}
	
	function getUserInfo($username){
		
		$this->db->where('username',$username);
		$query = $this->db->get('users');
		
		if($query->num_rows >0)
			
			return $query->result();
		else
			return false;
		
	}
	
	
}
