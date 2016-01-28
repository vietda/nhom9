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

function log_message($text){
	/*echo '<div style="color:green"><h3>Log</h3>'.$text.'</div>';*/
}

class EmrpcServer{
 
  private $db; 
 
  function __construct(){
    
	$this->db = DB();
  
  }
  
  function __destruct(){
	
	$this->db->close();
	
  }
  
  function getTablesList(){
	$this->db->select('tableName');
	$this->db->order_by('tableName');
	$query = $this->db->get('tables');
	return json_encode($query->result());
  }
  
  function getLoginNames(){
	
	$this->db->select('username');
	$this->db->order_by('username');
	$query = $this->db->get('users');
	return json_encode($query->result());
	
  }
  
  function checkLogin($user,$pass,$table){
	
	$query = $this->db->get_where('users',array('username'=>$user,'password'=>md5($pass) ));
	if ($query->num_rows()>0){  // Login OK
		
		$this->db->select('sid');
		$this->db->from('sessions');
		$this->db->join('tables','sessions.fk_idTables=tables.idTables');
		$this->db->where(array('tableName'=>$table,'status'=>'insession'));
		$query = $this->db->get();
			
		if($query->num_rows()>0){
		
			return json_encode($query->result());
			
		}else{
		
			$query = $this->db->get_where('config',array('device'=>'server','key'=>'mininterval'));
			if($query->num_rows()>0)
				$mininterval = $query->row()->value;
			
			$query = $this->db->get_where('config',array('device'=>'server','key'=>'maxitems'));
			if($query->num_rows()>0)
				$maxitems = $query->row()->value;
				
			$query = $this->db->get_where('config',array('device'=>'server','key'=>'maxrounds'));
			if($query->num_rows()>0)
				$maxrounds = $query->row()->value;
		
			
		
			$newsid = md5(uniqid(microtime()).$_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
			$this->db->select('idTables');
			$this->db->where(array('tableName'=>$table));
			$tableIDrow = $this->db->get('tables');
			$idTables = $tableIDrow->row()->idTables;
			
			$this->db->insert('sessions',array('sid'=>$newsid,'fk_idTables'=>$idTables,'extras'=>json_encode(array('mininterval'=>$mininterval,'maxitems'=>$maxitems,'maxrounds'=>$maxrounds,'currentroundts'=>'0','currentround'=>'0','currentitems'=>'0'))));
			
			return json_encode(array(array('sid'=>$newsid)));
		}
	}
	throw new Exception("Unauthorized",401);
	
  }
  
  function setDinersNumber($sid,$adults,$children){
	
	$this->db->select('extras');
	$this->db->where('sid',$sid);
	$query = $this->db->get('sessions');
	if($query->num_rows() > 0)
		$extras = json_decode($query->row()->extras,TRUE);
	
	$extras['diners']=array('adults'=>$adults,'children'=>$children);
	
	$data = array('extras'=>json_encode($extras));
	
	$this->db->where('sid',$sid);
	$this->db->update('sessions',$data);
  
  }
  
  function getItemNumber($sid,$idmenulists){
	
	$this->db->select('idOrders,number,note');
	$this->db->where(array('fk_sid'=>$sid,'ksent'=>'n'));
	$this->db->where('fk_idMenulists',$idmenulists);
	
	$query = $this->db->get('orders');
	if($query->num_rows() == 0)
		return json_encode(array(array('number'=>0,'note'=>null)));
	else
		return json_encode($query->result());	
	
  }
  
  function addToOrder($sid,$idmenulists,$number,$note){
	
	$note = trim($note);
	
	$this->db->select('menuType');
	$this->db->from('menulists');
	$this->db->join('menus','menulists.fk_idMenus=menus.idMenus');
	$this->db->where('idMenulists',$idmenulists);
	$query = $this->db->get();
	
	if($query->num_rows()>0)
		$menuType = $query->row()->menuType;
	else
		throw new Exception("Item not found",401);
		
	$this->db->select('extras');
	$this->db->where(array('sid'=>$sid));
	$query = $this->db->get('sessions');
	
	if($query->num_rows()>0)
		$extras = json_decode($query->row()->extras,TRUE);
	else
		throw new Exception("Extras not found",402);
		
	$this->db->select('idOrders,number');
	$this->db->where(array('fk_sid'=>$sid,'fk_idMenulists'=>$idmenulists,'ksent'=>'n'));
	$query = $this->db->get('orders');
	
	$isUpdate = FALSE;
	$oldNumber = 0;
	
	if($query->num_rows()>0){
		$isUpdate = TRUE;
		$idOrders = $query->row()->idOrders;
		$oldNumber = $query->row()->number;
	}
	
	$maxAllowedItems = $extras['maxitems']*($extras['diners']['adults']+$extras['diners']['children']); 
	$totItems = $extras['currentitems']+$number-$oldNumber;
	$itemsLeft = $maxAllowedItems-$extras['currentitems'];
	
	if($menuType=='f' && ($totItems>$maxAllowedItems)){
		throw new Exception("Error -- maximum per round reached: $itemsLeft left",403); 
	}		
	else if($menuType=='f'){
		$extras['currentitems']+=$number-$oldNumber;
		$data = array('extras'=>json_encode($extras));
		$this->db->where('sid',$sid);
		$this->db->update('sessions',$data);
	}
	if($isUpdate){
		if($number=='0')
			$this->db->delete('orders',array('idOrders'=>$idOrders));
		else{
			$data = array('number'=>$number,'timestamp'=>date("Y-m-d H:i:s"),'note'=>$note);
			$this->db->where(array('idOrders'=>$idOrders));
			$this->db->update('orders',$data);
		}
	}
	else{		
		$data = array('fk_sid'=>$sid,'fk_idMenulists'=>$idmenulists,'number'=>$number,'timestamp'=>date("Y-m-d H:i:s"),'note'=>$note);
		$this->db->insert('orders',$data);
	}
	
	return "ok";
  }
  
  function getOrderItems($type,$sid){
		$mType = $type=='0'?'f':'c';
		$this->db->select("menulists.idMenulists,menulists.price,orders.number,items.label");
		$this->db->from("orders");
		$this->db->where(array('orders.fk_sid'=>$sid,'orders.ksent'=>'n'));
		$this->db->join("menulists","orders.fk_idMenulists=menulists.idMenulists");
		$this->db->join("menus","menulists.fk_idMenus=menus.idMenus");
		$this->db->join("items","menulists.fk_idItems=items.idItems");
		$this->db->where(array('menuType'=>$mType));
		
		$query = $this->db->get();
		
		return json_encode($query->result());
  }
 
 function sendOrder($sid){
	// 1) For fixed items (sushimode): check whether currentround > maxround 
	// 2) For fixed items (sushimode): check whether current-currentroundts>mininterval
	// 3) If everything is ok send, increment currentround, clear currentitems ... else report error
    // 4) A la carte items can always be sent 
	// 5) Inserisci bevande in bqueue e food in kqueue
	
	//Select all a la carte items
	$this->db->select("orders.fk_idMenulists,orders.idOrders");
	$this->db->from("orders");
	$this->db->where(array('orders.fk_sid'=>$sid,'orders.ksent'=>'n'));
	$this->db->join("menulists","orders.fk_idMenulists=menulists.idMenulists");
	$this->db->join("menus","menus.idMenus=menulists.fk_idMenus");
	$this->db->where(array('menuType'=>'c'));
	$query = $this->db->get();
	
	$ksid = md5(uniqid(microtime()).$_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
	
	$result = $query->result();
	$numCarteItems = $query->num_rows();
	
	foreach($result as $row){
		$this->db->where(array('fk_sid'=>$sid,'fk_idMenulists'=>$row->fk_idMenulists));
		$this->db->update('orders',array('ksent'=>'y'));
		
		$this->db->select('foodbev');
		$this->db->from('menus');
		$this->db->join('menulists',"menulists.fk_idMenus=menus.idMenus");
		$this->db->where('menulists.idMenulists',$row->fk_idMenulists);
		$query = $this->db->get();
		$tmpType = $query->row()->foodbev;
		if ($tmpType == 'f'){
			$this->db->insert('kqueue',array('sid'=>$ksid,'fk_idOrders'=>$row->idOrders,'fk_sid'=>$sid));
		}
		
		$this->db->select('foodbev');
		$this->db->from('menus');
		$this->db->join('menulists',"menulists.fk_idMenus=menus.idMenus");
		$this->db->where('menulists.idMenulists',$row->fk_idMenulists);
		$query = $this->db->get();
		$tmpType = $query->row()->foodbev;
		if ($tmpType == 'b'){
			$this->db->insert('bqueue',array('sid'=>$ksid,'fk_idOrders'=>$row->idOrders,'fk_sid'=>$sid));
		}
	}
	/*
	$this->db->select('extras');
	$this->db->where('sid',$sid);
	$query = $this->db->get('sessions');
	
	if($query->num_rows()>0)
		$extras = json_decode($query->row()->extras,TRUE);
	else
		throw new Exception("Extras not found",401);
	
	if(!($extras['currentround']<$extras['maxrounds'])){
		throw new Exception("Max Rounds exceeded. Maximum rounds ".$extras['maxrounds'],404);
	}
	
	if(!(time()-$extras['currentroundts']>$extras['mininterval'])){
		throw new Exception("Mininterval not expired. Mininterval ".$extras['mininterval']." seconds",405);
	}
		
	//Select all fixed menu items
	$this->db->select("orders.fk_idMenulists,orders.idOrders");
	$this->db->from("orders");
	$this->db->where(array('orders.fk_sid'=>$sid,'orders.ksent'=>'n'));
	$this->db->join("menulists","orders.fk_idMenulists=menulists.idMenulists");
	$this->db->join("menus","menus.idMenus=menulists.fk_idMenus");
	$this->db->where(array('menuType'=>'f'));
	$query = $this->db->get();

	$result = $query->result();
	$numFixedItems = $query->num_rows();
	
	if($numFixedItems>0){
		$extras['currentround']++;
		$extras['currentroundts']=time();
		$extras['currentitems']=0;
	}
	
	foreach($result as $row){
		$this->db->where(array('fk_sid'=>$sid,'fk_idMenulists'=>$row->fk_idMenulists));
		$this->db->update('orders',array('ksent'=>'y',));
		
		$this->db->select('foodbev');
		$this->db->from('menus');
		$this->db->join('menulists',"menulists.fk_idMenus=menus.idMenus");
		$this->db->where('menulists.idMenulists',$row->fk_idMenulists);
		$query = $this->db->get();
		$tmpType = $query->row()->foodbev;
		if ($tmpType == 'f'){
			$this->db->insert('kqueue',array('sid'=>$ksid,'fk_idOrders'=>$row->idOrders,'fk_sid'=>$sid));
		}
		
		$this->db->select('foodbev');
		$this->db->from('menus');
		$this->db->join('menulists',"menulists.fk_idMenus=menus.idMenus");
		$this->db->where('menulists.idMenulists',$row->fk_idMenulists);
		$query = $this->db->get();
		$tmpType = $query->row()->foodbev;
		if ($tmpType == 'b'){
			$this->db->insert('bqueue',array('sid'=>$ksid,'fk_idOrders'=>$row->idOrders,'fk_sid'=>$sid));
		}
	}
	
	$data = array('extras'=>json_encode($extras));
	
	if($numCarteItems>0 || $numFixedItems>0){
		$this->db->where('sid',$sid);
		$this->db->update('sessions',$data);
		return "ok";
	}
	else
		throw new Exception("No items to send",406);	*/	
 }
  
  function callWaiter($sid){
	$this->db->where('sid',$sid);
	$this->db->update('sessions',array('bell'=>'y'));
  }
  
  // For checking out
  //1) Display fixed price total
  //2) Display extras (carte) items list total
  
  function getBill($sid){
	
	//$mType = $type=='0'?'f':'c';
	
		$this->db->select('extras');
		$this->db->where('sid',$sid);
		$query = $this->db->get('sessions');
		
		if($query->num_rows()>0)
			$extras = json_decode($query->row()->extras,TRUE);
		else
			throw new Exception("Extras not found",401); 
		
		$adults = $extras['diners']['adults'];
		$children = $extras['diners']['children'];
		
		$this->db->select('value');
		$this->db->where('key','price');
		$query = $this->db->get('config');
		
		if($query->num_rows()>0)
			$price = json_decode($query->row()->value,TRUE);
		else
			throw new Exception("Price not found",407);
		
		$adultsPrice = $price['adults'];
		$childrenPrice = $price['children'];
		
		$totalf = $adultsPrice*$adults + $childrenPrice*$children;
		
		$fixedBill = array('adultsnr'=>$adults,'childrennr'=>$children,'adults'=>$adultsPrice*$adults,'children'=>$childrenPrice*$children);
		//return json_encode($fixedBill);
	
		$queryStr = "SELECT items.label,menulists.idMenulists,orders.idOrders,menulists.price,sum(number) as number ".
		"FROM orders JOIN menulists on menulists.idMenulists=orders.fk_idMenulists ".
		"JOIN menus ON menus.idMenus=menulists.fk_idMenus JOIN items ON items.idItems=menulists.fk_idItems ". 
		"WHERE menus.menuType='c' AND orders.fk_sid='$sid' AND orders.ksent='y'  group  by idMenulists";
		$query = $this->db->query($queryStr);
		$totalc = 0.0;
		foreach($query->result() as $row){
			$totalc+=($row->price*$row->number);
			$row->price = $row->price*$row->number;
		}
		
		$carteBill = $query->result();
		$total = $totalf+$totalc;
		
		$bill = array('total'=>$total,'fixed'=>$fixedBill,'carte'=>$carteBill);
		
		$this->db->where('sid',$sid);
		$this->db->update('sessions',array('ticket'=>json_encode($bill)));
		
		return json_encode($bill);
  }
  
  function payBill($sid){
  
	/* 	1) Crea ticket
		2) Marca session come checkedout
	*/
	
	$this->db->select('ticket');
	$this->db->where('sid',$sid);
	$query = $this->db->get('sessions');
	
	$this->db->insert('tickets',array('fk_sid'=>$sid,'ticket'=>$query->row()->ticket));
	
	$this->db->where('sid',$sid);
	$this->db->update('sessions',array('status'=>'checkedout'));
	
	return "ok";
  }
  
}
?>
