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

	class Settings extends CI_Controller{
	
		function __construct(){
		
			parent::__construct();
			$this->load->helper('html');
			$this->load->helper('error_helper');
			$this->load->model('lang_model');
			$this->load->model('settings_model');
			$this->lang->load('msg',$this->lang_model->getLang());
			$this->load->library('util');
			
			$is_logged_in = $this->session->userdata('is_logged_in');
			if(!isset($is_logged_in) || $is_logged_in != TRUE)
				redirect('login/');		
		}
		
		function index(){
			$this->save();
		}
		
		function save(){
			$this->load->library('form_validation');
			//if ($this->form_validation->run() == FALSE){
			$this->form_validation->run();
			if($this->input->post('save')!='n'){
				$data[] = array('device'=>'server','key'=>'miniterval','value'=>$this->input->post('mininterval'));
				$data[] = array('device'=>'server','key'=>'maxrounds','value'=>$this->input->post('maxrounds'));
				$data[] = array('device'=>'server','key'=>'maxitems','value'=>$this->input->post('maxitems'));
				$data[] = array('device'=>'server','key'=>'servuilang','value'=>$this->input->post('lang'));
				
				$data[] = array('device'=>'tablet','key'=>'restmode','value'=>$this->input->post('radiom'));
				$data[] = array('device'=>'tablet','key'=>'displaymode','value'=>$this->input->post('radiodisp'));
				$data[] = array('device'=>'tablet','key'=>'currency','value'=>$this->input->post('currency'));
				$data[] = array('device'=>'tablet','key'=>'price','value'=>json_encode(array('adults'=>$this->input->post('priceadults'),'children'=>$this->input->post('pricechildren'))));
				
				$this->settings_model->updateSettings($data);
			}
				
			$this->load->view('settings_form');
			//}
			
		}
		
		function getJSONSettings(){
			echo $this->settings_model->getJSONSettings();		
		}
	}
?>
