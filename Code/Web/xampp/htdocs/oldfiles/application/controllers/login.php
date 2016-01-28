<?php
/*
* Copyright 2012 Gianrico D'Angelis  -- gianrico.dangelis@gmail.com
* 
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
* 
*   http://www.apache.org/licenses/LICENSE-2.0
* 
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

class Login extends CI_Controller {
	
	function __construct(){
	
		parent::__construct();
		$this->load->model('lang_model');
		$this->lang->load('msg',$this->lang_model->getLang());
	}
	
	function index(){
		$data['main_content']="login_form";
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
			$this->load->view('includes/template',$data);
		else
			redirect('site/members_area');
	}
	
	function validate_credentials()
	{		
		$this->load->model('membership_model');
		$query = $this->membership_model->validate();
		
		if($query) // if the user's credentials validated...
		{
			$userData = $this->membership_model->getUserInfo($this->input->post('username'));
			
			if($userData){
				$firstName = $userData[0]->firstName;
				$userRole = $userData[0]->role;
				$uid = $userData[0]->idUsers;
			}
			
			$data = array(
				'uid' => $uid,
				'role' => $userRole,
				'username' => $this->input->post('username'),
				'first_name' => $firstName,
				'is_logged_in' => true
			);
			$this->session->set_userdata($data);
						
			redirect('site/members_area');
		}
		else // incorrect username or password
		{
			$this->index();
		}
	}	
	
	
	function logout()
	{
		$this->session->sess_destroy();
		redirect("login");
	}
}
?>
