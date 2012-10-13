<?php 
if (!defined('HCMS')) die();
$user=HolyUser::getInstance();
if (!isset($_POST['login'])) $_POST['login']="";
if (!isset($_POST['pass'])) $_POST['pass']="";
if (!isset($params['cabinet_url'])) $params['cabinet_url']="/";
if (isset($_POST['submit']))
	{
		if (isset($_POST['login'])) if ($_POST['login']=="") $error[]="Не введён логин";
		if (isset($_POST['pass'])) if ($_POST['pass']=="") $error[]="Не введён пароль";
	};
if (($_POST['login']!="") && ($_POST['pass']!=""))
	if (!$user->Auth($_POST['login'],$_POST['pass']))
		$error[]="Неверный пароль или логин";
	if (!$user->IsAuth())
	include($full_template_path);
        else
            Redirect($params['cabinet_url'], true);
?>