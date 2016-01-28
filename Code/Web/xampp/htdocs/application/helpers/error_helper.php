<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
*Copyright 2012 Gianrico D'Angelis  -- gianrico.dangelis@gmail.com
*
*Licensed under the Apache License, Version 2.0 (the "License");
*you may not use this file except in compliance with the License.
*You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*
*Unless required by applicable law or agreed to in writing, software
*distributed under the License is distributed on an "AS IS" BASIS,
*WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*See the License for the specific language governing permissions and
*limitations under the License.
*/

if (!function_exists('ajax_error_msg')) {
	//$msg - error message;
	function ajax_error_msg($msg) {
		return '<div class="ui-widget" style="margin:0 0 2px 0;font-size:12px">'.
					'<div class="ui-state-error ui-corner-all" style="padding:5px 9px">'.
						'<span class="ui-icon ui-icon-alert" style="float:left;margin-right:.3em"></span>'.
						'<strong>Error:</strong> '.$msg.
					'</div>'.
			'</div>';
	}
}
if (!function_exists('ajax_success_msg')) {
	//$msg - error message;
	function ajax_success_msg($msg) {
		return '<div class="ui-widget" style="margin:0 0 2px 0;font-size:12px">'.
				'<div class="ui-state-highlight ui-corner-all" style="padding:5px 9px">'.
						'<span class="ui-icon ui-icon-info" style="float:left;margin-right:.3em"></span>'.
						'<strong>Success:</strong> '.$msg.
				'</div>'.
			'</div>';
	}
}
 
/* End of file error_helper.php */
/* Location: ./system/application/helpers/error_helper.php */
