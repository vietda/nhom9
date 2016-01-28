<?php
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

 @define(BASEPATH,'../system/');//folder, contain database classes
 @define(APPPATH,'../application/');
 @define(EXT,'.php');

 require_once('../system/database/DB.php');
 
 include_once("./lib/jsonrpc.lib.php");
 include_once("./rpcmethods/emrpc.methods.php");
 
 header("Content-Type: application/json");
 
 $server = new JsonRpc( new EmrpcServer() );
 $server->process();
 
 
?>
