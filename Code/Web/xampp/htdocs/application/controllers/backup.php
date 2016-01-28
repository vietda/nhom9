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

class Backup extends CI_Controller{

	function __construct(){
		parent::__construct();
		
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
			redirect('login/');	
			
	    $this->load->helper(array('form', 'url','error_helper'));
		
		$this->load->model('lang_model');
		$this->lang->load('msg',$this->lang_model->getLang());
		
		$this->load->dbutil();
		$this->load->library('zip');
		$this->load->helper('file');
		$this->load->library('unzip');
	}
	
	function index(){
		$this->load->view('backup_form');
	}
	
	function doBackup(){
		$prefs = array(				
                'ignore'      => array('em_sessions'),           // List of tables to omit from the backup
                'format'      => 'txt',             // gzip, zip, txt
                'filename'    => 'embackup.sql',    // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );

		$bk_content = &$this->dbutil->backup($prefs);
		write_file('media/embackup.sql', $bk_content);
		
		$embackup = 'tmp/embackup_'.date("d-m-Y").'.zip';
		
		//Clean tmp/ first
		
		$tmpFiles = get_filenames('tmp/');
		foreach ($tmpFiles as $tmpFile){
			$pathinfo = pathinfo($tmpFile);
			if($pathinfo['extension']=='zip')
				unlink('tmp/'.$tmpFile);
		}
		
		$path = 'media/';
		$filenames = get_filenames('media/');
		foreach ($filenames as $file){
			$this->zip->add_data($file,read_file('media/'.$file));
		}
		$result = $this->zip->archive($embackup);
		unlink('media/embackup.sql');
		echo $embackup;
	}
	
	function mediaUpload(){
		$this->load->view('restore_dialog');
	}
	
	function restore(){
		$config['upload_path'] = './tmp/';
		$config['allowed_types'] = 'zip';
		$config['file_name'] = 'restore.zip';
		$config['overwrite'] = TRUE;
		$config['max_size']	= '200000';
		
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$data = array('error' => $this->upload->display_errors());
			$this->load->view('restore_dialog',$data);
		}
		else
		{
			$this->doRestore();
			echo $this->lang->line('msg_ConfirmRestore');
		}
	}
	
	private function doRestore(){
		$this->unzip->extract('tmp/restore.zip', 'media/');
		
		$backup = read_file('media/embackup.sql');
		unlink('media/embackup.sql');
		
		$tmp = explode("\n", $backup);
		foreach($tmp as $i=>$line){
			if(substr($line, 0 ,1) == '#' || trim($line) == '') unset($tmp[$i]);
		}
		
		$query_list = explode(";\n", implode("\n",$tmp));

		foreach($query_list as $query){
			 $query = trim($query);
             //echo  $query.'<br/>============<br/>';
			 $this->db->query($query);	
		}
	}

}
