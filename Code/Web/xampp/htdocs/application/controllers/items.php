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

	class Items extends CI_Controller{
	
		function __construct(){
		
			parent::__construct();
			$this->load->helper('html');
			$this->load->helper('error_helper');
			$this->load->model('lang_model');
			$this->load->model('items_model');
			$this->load->model('upload_model');
			$this->lang->load('msg',$this->lang_model->getLang());
			
			$is_logged_in = $this->session->userdata('is_logged_in');
			if(!isset($is_logged_in) || $is_logged_in != TRUE)
				redirect('login/');		
		}
		
		function index(){
			$this->listItems();
		}
		
		function listItems(){
			if($this->items_model->countItems()==0)
				$this->load->view('no_items');
			else{
				$data['item'] = $this->items_model->getItems();
				$this->load->view('list_items',$data);	
			}
		}
		
		function listItems1(){
			if($this->items_model->countItems()>0){
				$data['item'] = $this->items_model->getItems();
				$this->load->view('list_items_add',$data);	
			}
		}
		
		function newItem(){
			
			$this->load->library('form_validation');
			
			if ($this->form_validation->run() == FALSE || $this->input->post('edit')=='y'){				
				$data['imgsrc']='#';
				if($this->input->post('idImages')!='-1'){
					if($this->upload_model->getMediaById($this->input->post('idImages'))!=null){
						$fileName = $this->upload_model->getMediaById($this->input->post('idImages'))->fileName;
						$data['imgsrc'] = base_url().'media/'.$fileName;
					}
				}
				$this->load->view('items_form',$data);
			
			}else{
				$data['label']=$this->input->post('itemlabel');
				$data['description']=$this->input->post('itemdescr');
				$data['price']=$this->input->post('itemprice');
				$data['idImage']=$this->input->post('idImages');
				if($this->input->post('idItem')!='-1'){
					$data['idItems'] = $this->input->post('idItem');
					$this->items_model->updateItem($data);
				}
				else{
					$this->items_model->saveItem($data);
				}
				$this->listItems();
			}	
		}
		
		function deleteItem(){
			
			// TO-DO : check if item in use and take corrective action
 			
			$this->items_model->deleteItem($this->input->post('idItem'));
			$this->listItems();
		}
		
		function getJSONItemByID(){
	

			echo $this->items_model->getJSONItemByID($this->input->post('idItem'));
		
		}
	
	}

?>
