<?php
require_once($_SERVER['DOCUMENT_ROOT']."/engine/engine.php");
POSTPreWork();
global $H_USER;
$user_ifno_holy=$H_USER->GetInfo();
if (isset($_POST['CODE']))
if ($_POST['CODE']="asdasdas.sklfhjsjf.sdjl_akfsdhjf")
//if (!$user_ifno_holy['block_control'])
{
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$el=new DBlockElement($_photo_id);
if (!isset($_POST['parent'])) $_POST['parent']=0;
if (!empty($_FILES)) {
	
	{
		//$el->sql->debug=true;
$el->Add(Array(
			"foto"=>$_POST['Filedata'],
			"name"=>MD5($_POST['Filedata']['name'].time()),
			"caption"=>"&nbsp;",
			"parent"=>$_POST['parent']
			));
		echo '1';
	}
	};
};
?>