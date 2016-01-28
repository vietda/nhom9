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

class Menus extends CI_Controller {

	/* Workflow
		
		1) Check login status
		2) If no menus => show "create a new menu"
		   else show list of menus (food ad drinks separated) with edit details,edit content end delete buttons
		   
		   2.a) if click delete show confirm and proceed  
		   2.b) if click edit open edit frame (details or content)
	
	*/	

	function __construct(){
	
		parent::__construct();
		$this->load->helper('html');
		$this->load->helper('error_helper');
		$this->load->model('lang_model');
		$this->load->model('menus_model');
		$this->lang->load('msg',$this->lang_model->getLang());
		$this->load->library('util');
		
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
			redirect('login/');
	}
	
	function index(){
		
			$this->listMenus();
	}
	
	function newMenu(){
		$this->load->library('form_validation');

		if ($this->form_validation->run() == FALSE || $this->input->post('edit')=='y'){
			
			$this->load->view('menus_form');
		}else{
			$data['label']=$this->input->post('menulabel');
			$data['description']=$this->input->post('menudescr');
			$data['visible']= $this->input->post('radiov'); //visiblity
			$data['menuType']= $this->input->post('radiot') ; //fixed price or not
			$data['foodbev']= $this->input->post('radiofb') ; // food or bev
			if($this->input->post('idMenu')!='-1'){
				$data['idMenus'] = $this->input->post('idMenu');
				$this->menus_model->updateMenu($data);
			}
			else{
				$this->menus_model->saveMenu($data);
			}
			$this->listMenus();
		}
	}
	
	function listMenus($data = NULL){
			
		if($this->menus_model->countMenus()==0)
			$this->load->view('no_menus');
		else{
			$foodMenus = $this->menus_model->getFoodMenus();
			$bevMenus = $this->menus_model->getDrinksMenus();
			$data['food']=$foodMenus;
			$data['drinks']=$bevMenus;
			$this->load->view('list_menus',$data);	
		}
	}
	
	function deleteMenu(){
		//echo "OK";
		if(!$this->menus_model->deleteMenu($this->input->post('idMenu')))
			$this->listMenus();
		else{
				$data['is_Error'] = TRUE;
				$data['error_message']="Error";
				$this->listMenus($data);
			}
	}
	
	function getJSONMenuByID(){
	
		echo $this->menus_model->getJSONMenuByID($this->input->post('idMenu'));
		
	}
	
	function updatePositions(){
		$jobj = json_decode($this->input->post('data'));
		foreach($jobj as $row){
			$idMenus = $row->id;
			$data['position'] = $row->position;
			$this->menus_model->updatePositions($idMenus,$data);
		}
		$this->listMenus();
	}
}

?>
