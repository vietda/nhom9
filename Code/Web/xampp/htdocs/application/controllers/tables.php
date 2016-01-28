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

class Tables extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('error_helper');
		$this->load->model('lang_model');
		$this->load->model('tables_model');
		
		$this->lang->load('msg',$this->lang_model->getLang());
		
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
			redirect('login/');
	
	}
	
	function index(){
		$this->listTables();
	}
	
	function listTables(){
		$data['tables'] = $this->tables_model->getTables();
		$this->load->view('list_tables',$data);
	}
	
	function newTable(){
		
		$this->load->library('form_validation');

		if ($this->form_validation->run() == FALSE || $this->input->post('edit')=='y'){			
			$this->load->view('tables_form');
		}else{
			$data['tableName'] = $this->input->post('tablelabel');
			if($this->input->post('idTables')!='-1'){
				$data['idTables'] = $this->input->post('idTables');
				$this->tables_model->updateTable($data);
			}
			else{
				$this->tables_model->saveTable($data);
			}
			$this->listTables();
		}
	}
	
	function getJSONTableByID(){
		echo $this->tables_model->getJSONTableByID($this->input->post('idTables'));
	}
	
	function deleteTable(){
		$this->tables_model->deleteTable($this->input->post('idTables'));
		$this->listTables();
	}
}

?>
