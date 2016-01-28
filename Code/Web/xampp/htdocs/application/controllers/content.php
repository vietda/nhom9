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

	 //Crea
	//1) Pulsante add item.
	//2) Quando si aggiunge item chiedere gruppo di appartenenza
	 //List
	//1) Trova i gruppi associati a menulists di idMenus (ordina per posizione)
	//2) per ogni gruppo crea <li>.sortable e poi al suo interno carica tutti gli items
	//
	
class Content extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('error_helper');
		$this->load->model('lang_model');
		$this->load->model('content_model');
		
		$this->lang->load('msg',$this->lang_model->getLang());
		
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
			redirect('login/');
	
	}
	
	function index(){
		//echo $this->content_model->countMenuItems($this->input->post('idMenu'));
		$data = $this->prepareIndexData($this->input->post('idMenus'));
		$this->load->view('content_menus',$data);
	}
	
	function addMenuItem(){
		$data['fk_idMenus'] = $this->input->post('idMenus');
		$data['fk_idItems'] = $this->input->post('idItems');
		$data['fk_idCategories'] = $this->input->post('idCategories');
		$data['price'] = $this->input->post('price');
		
		$this->content_model->addMenuItem($data);
		
		$data = $this->prepareIndexData($this->input->post('idMenus'));
		$this->load->view('content_menus',$data);
		
	}
	
	function getCategories($idMenus){
		return $this->content_model->getMenulists($idMenus);
	}
	
	function prepareIndexData($idMenus){
		$data['menuLabel'] = $this->content_model->getMenuRow($idMenus)->label;
		$data['idMenus'] = $this->content_model->getMenuRow($idMenus)->idMenus;
		$data['categories'] = $this->getCategories($idMenus);
		return $data;
	}
	
	function updatePositions(){
		$jobj = json_decode($this->input->post('data'));
		foreach($jobj as $row){
			$idMenulists = $row->id;
			$data['categoryPosition'] = $row->catPos;
			$data['itemPosition'] =  $row->itemPos;
			$this->content_model->updatePositions($idMenulists,$data);
		}
		$data = $this->prepareIndexData($this->input->post('idMenus'));
		$this->load->view('content_menus',$data);
	}
	
	function deleteMenuItem(){
		$this->content_model->deleteMenuItem($this->input->post('idMenulists'));
		$data = $this->prepareIndexData($this->input->post('idMenus'));
		$this->load->view('content_menus',$data);
	}
}

?>
