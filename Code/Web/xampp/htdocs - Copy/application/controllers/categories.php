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

class Categories extends CI_Controller {

	function __construct(){
	
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('error_helper');
		$this->load->model('lang_model');
		$this->load->model('categories_model');
		$this->lang->load('msg',$this->lang_model->getLang());
		
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
			redirect('login/');
	}
	
	function index(){

			$this->listCategories();
	}
	
	function listCategories(){
		if($this->categories_model->countCategories()==0)
			$this->load->view('no_categories');
		else{
			$data['category'] = $this->categories_model->getCategories();
			$this->load->view('list_categories',$data);	
		}
	}
	
	function listCategories1(){
		if($this->categories_model->countCategories()>0){
			$data['category'] = $this->categories_model->getCategories();
			$this->load->view('list_categories_add',$data);
		}
	}
	
	function newCategory(){
		
		$this->load->library('form_validation');
		if ($this->form_validation->run() == FALSE || $this->input->post('edit')=='y')
			$this->load->view('categories_form');
		else{
			$data['label']=$this->input->post('categorylabel');
			$data['description']=$this->input->post('categorydescr');
			if($this->input->post('idCategory')!='-1'){
				$data['idCategories'] = $this->input->post('idCategory');
				$this->categories_model->updateCategory($data);
			}
			else{
				$this->categories_model->saveCategory($data);
			}
			$this->listCategories();
		}
	
	}
	
	function deleteCategory(){
	
	/* TO-DO : check if category in use and take appropriate action
	 */
		
		$this->categories_model->deleteCategory($this->input->post('idCategory'));
		$this->listCategories();
	}
	
	function getJSONCategoryByID(){
	
		echo $this->categories_model->getJSONCategoryByID($this->input->post('idCategory'));
		
	}
	
}
?>
