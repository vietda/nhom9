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

class Users extends CI_Controller{
	function __construct(){
		parent::__construct();
		
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
			redirect('login/');	
		
		$this->load->model('lang_model');
		$this->lang->load('msg',$this->lang_model->getLang());
		
	    $this->load->helper(array('form', 'url','error_helper','html'));
		$this->load->model('users_model');
	}
	
	function index(){
		$this->listUsers();
	}
	
	function listUsers(){
		if($this->users_model->countUsers()==0)
			$this->load->view('no_users');
		else{
			$data['users'] =  $this->users_model->getUsers();
			$this->load->view('list_users',$data);
		}
	}
	
	function updateUser(){
		
		$this->load->library('form_validation');
		if ($this->form_validation->run('updateuser') == FALSE || $this->input->post('edit')=='y' ){
			$data['save'] = 'updateuser';
			$this->load->view('users_form',$data);
		}
		else{
			$data['firstName']=$this->input->post('name');
			$data['lastName']=$this->input->post('surname');
			$data['emailAddress']=$this->input->post('email');
			$data['role']=$this->input->post('role');
			$data['username']=$this->input->post('username');
			$data['idUsers'] = $this->input->post('idUser');
			if($this->input->post('password1')!="")
				$data['password'] = md5($this->input->post('password1'));
			$this->users_model->updateUser($data);
			$this->listUsers();
		}
	}
	
	function newUser(){
		
		$this->load->library('form_validation');
		
		if ($this->form_validation->run('newuser') == FALSE){
			$data['save'] = 'newuser';
			$this->load->view('users_form',$data);
		}else{
			$data['firstName']=$this->input->post('name');
			$data['lastName']=$this->input->post('surname');
			$data['emailAddress']=$this->input->post('email');
			$data['role']=$this->input->post('role');
			$data['username']=$this->input->post('username');
			$data['password']=md5($this->input->post('password1'));
			$this->users_model->saveUser($data);
			$this->listUsers();
		}
	}
	
	function getJSONUserByID(){
		echo $this->users_model->getJSONUserByID($this->input->post('idUser'));
	}
	
	function deleteUser(){
		$this->users_model->deleteUser($this->input->post('idUser'));
		$this->listUsers();
	}
	
} 

?>