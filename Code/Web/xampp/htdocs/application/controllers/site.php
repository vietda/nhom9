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

class Site extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->lang->load('msg');
		$this->is_logged_in();
		$this->load->model('lang_model');
		$this->lang->load('msg',$this->lang_model->getLang());		
	}

	function members_area()
	{
		//if($this->session->userdata('role')=='0')
		$data['role'] = $this->session->userdata('role');
		$this->load->view('includes/template_s',$data);
	}
	
	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
		{
			echo $this->lang->line('msg_pageaccessdenied');
			die();		
		}		
	}	

}
?>
