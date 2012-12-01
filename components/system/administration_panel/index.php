<?php 
global $_global_bread;
global $_PICTURES;
$_global_bread[]=Array("Административная панель","/engine/admin/");
ob_start( );
			
global $H_USER;

$user_info=$H_USER->GetInfo();

if (!isset($_GET['path'])) $_GET['path']="start_page";

$selected_module=$_GET['path'];
$_include_name=FOLDER_ROOT."/engine/admin/".$selected_module.".php";
$_include_name0=FOLDER_ROOT."/site/admin/".$selected_module.".php";
if (file_exists($_include_name0)){
include (FOLDER_ROOT."/site/engine/admin/".$selected_module.".php");
			$global_page_text = ob_get_contents();
			ob_end_clean( );
}elseif (file_exists($_include_name)){
include (FOLDER_ROOT."/engine/admin/".$selected_module.".php");
			$global_page_text = ob_get_contents();
			ob_end_clean( );
}else
SystemAlert("Не найден модуль системы администрирования: <b>".$selected_module."</b>");

//PrePrint($_global_bread);
include($full_template_path);
?>